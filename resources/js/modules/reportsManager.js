import { ApiService } from '../services/apiService.js';
import { DateService } from '../services/dateService.js';

export class ReportsManager {
    constructor() {
        this.api = new ApiService();
        this.dateService = new DateService();
        this.chartInstance = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        const generateBtn = document.getElementById('generateReport');
        if (generateBtn) {
            generateBtn.addEventListener('click', () => this.generateReport());
        }

        const reportType = document.getElementById('reportType');
        if (reportType) {
            reportType.addEventListener('change', (e) => this.onReportTypeChange(e.target.value));
        }
    }

    async generateReport() {
        const reportType = document.getElementById('reportType')?.value;
        const startDate = document.getElementById('startDate')?.value;
        const endDate = document.getElementById('endDate')?.value;
        const supplierId = document.getElementById('supplierFilter')?.value;

        const params = { startDate, endDate };
        if (supplierId) params.supplier_id = supplierId;

        try {
            const response = await this.api.getReport(reportType, params);
            this.renderReport(response.data, reportType);
        } catch (error) {
            console.error('Error generating report:', error);
            this.showError('Error al generar el reporte');
        }
    }

    renderReport(data, type) {
        if (type === 'financial') {
            this.renderFinancialReport(data);
        } else if (type === 'supplier') {
            this.renderSupplierReport(data);
        } else if (type === 'trends') {
            this.renderTrendsReport(data);
        }
    }

    renderFinancialReport(data) {
        const container = document.getElementById('reportContent');
        if (!container) return;

        container.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                    <div class="text-sm opacity-90">Total Facturado</div>
                    <div class="text-2xl font-bold">$${data.totalAmount?.toFixed(2) || '0.00'}</div>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white">
                    <div class="text-sm opacity-90">N° Facturas</div>
                    <div class="text-2xl font-bold">${data.totalInvoices || 0}</div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                    <div class="text-sm opacity-90">Promedio por Factura</div>
                    <div class="text-2xl font-bold">$${data.averageAmount?.toFixed(2) || '0.00'}</div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Proveedor</th>
                            <th class="px-4 py-2 text-left">Facturas</th>
                            <th class="px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.bySupplier?.map(item => `
                            <tr class="border-t">
                                <td class="px-4 py-2">${item.supplier}</td>
                                <td class="px-4 py-2">${item.count}</td>
                                <td class="px-4 py-2 font-semibold">$${item.total?.toFixed(2)}</td>
                            </tr>
                        `).join('') || '<tr><td colspan="3" class="text-center py-4">No hay datos</td></tr>'}
                    </tbody>
                </table>
            </div>
        `;
    }

    renderTrendsReport(data) {
        const container = document.getElementById('reportContent');
        if (!container) return;

        container.innerHTML = `
            <canvas id="trendsChart" class="w-full h-96"></canvas>
            <div class="mt-4 text-center text-sm text-slate-500">
                Evolución mensual de facturación
            </div>
        `;

        this.createTrendsChart(data);
    }

    createTrendsChart(data) {
        const ctx = document.getElementById('trendsChart')?.getContext('2d');
        if (!ctx) return;

        if (this.chartInstance) {
            this.chartInstance.destroy();
        }

        this.chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [{
                    label: 'Facturación Mensual',
                    data: data.values || [],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => `$${context.raw.toFixed(2)}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => `$${value.toFixed(2)}`
                        }
                    }
                }
            }
        });
    }

    onReportTypeChange(type) {
        const filters = document.getElementById('reportFilters');
        if (!filters) return;

        if (type === 'supplier') {
            filters.innerHTML = `
                <select id="supplierFilter" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Todos los proveedores</option>
                </select>
            `;
            this.loadSuppliersToFilter();
        } else {
            filters.innerHTML = `
                <input type="date" id="startDate" class="w-full px-4 py-2 border rounded-lg" placeholder="Fecha inicio">
                <input type="date" id="endDate" class="w-full px-4 py-2 border rounded-lg" placeholder="Fecha fin">
            `;
        }
    }

    async loadSuppliersToFilter() {
        try {
            const response = await this.api.getSuppliers();
            const select = document.getElementById('supplierFilter');
            if (select && response.data) {
                select.innerHTML = '<option value="">Todos los proveedores</option>' +
                    response.data.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            }
        } catch (error) {
            console.error('Error loading suppliers:', error);
        }
    }

    showError(message) {
        const container = document.getElementById('reportContent');
        if (container) {
            container.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 text-center">
                    ❌ ${message}
                </div>
            `;
        }
    }

    openModal() {
        const modal = document.getElementById('reportModal');
        if (modal) modal.classList.remove('hidden');
    }

    closeModal() {
        const modal = document.getElementById('reportModal');
        if (modal) modal.classList.add('hidden');
    }
}