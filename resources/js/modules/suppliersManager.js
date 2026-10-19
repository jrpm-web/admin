import { ApiService } from '../services/apiService.js';

export class SuppliersManager {
    constructor() {
        this.api = new ApiService();
        this.suppliers = [];
        this.init();
    }

    async init() {
        await this.loadSuppliers();
        this.renderTable();
        this.setupEventListeners();
    }

    async loadSuppliers() {
        try {
            const response = await this.api.getSuppliers();
            this.suppliers = response.data || [];
            return this.suppliers;
        } catch (error) {
            console.error('Error loading suppliers:', error);
            return [];
        }
    }

    renderTable() {
        const tableBody = document.getElementById('suppliersTableBody');
        if (!tableBody) return;

        if (this.suppliers.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                        No hay proveedores registrados
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = this.suppliers.map(supplier => `
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">${supplier.code}</td>
                <td class="px-6 py-4 text-sm text-slate-600">
                    <div class="font-medium">${supplier.name}</div>
                    <div class="text-xs">${supplier.email || ''}</div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">${supplier.phone || '-'}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold 
                        ${supplier.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                        ${supplier.status === 'active' ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    <button onclick="window.suppliersManager.editSupplier(${supplier.id})" 
                            class="text-blue-600 hover:text-blue-800 mr-3">
                        ✏️ Editar
                    </button>
                    <button onclick="window.suppliersManager.deleteSupplier(${supplier.id})" 
                            class="text-red-600 hover:text-red-800">
                        🗑️ Eliminar
                    </button>
                </td>
            </tr>
        `).join('');
    }

    async createSupplier(data) {
        try {
            const response = await this.api.createSupplier(data);
            if (response.success) {
                await this.loadSuppliers();
                this.renderTable();
                this.showNotification('Proveedor creado exitosamente', 'success');
                this.closeModal();
            }
        } catch (error) {
            this.showNotification('Error al crear proveedor', 'error');
        }
    }

    async deleteSupplier(id) {
        if (!confirm('¿Estás seguro de eliminar este proveedor?')) return;
        
        try {
            const response = await this.api.deleteSupplier(id);
            if (response.success) {
                await this.loadSuppliers();
                this.renderTable();
                this.showNotification('Proveedor eliminado', 'success');
            }
        } catch (error) {
            this.showNotification('Error al eliminar proveedor', 'error');
        }
    }

    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg transition-all transform translate-x-0
            ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    setupEventListeners() {
        const form = document.getElementById('supplierForm');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                await this.createSupplier(data);
            });
        }
    }

    openModal() {
        const modal = document.getElementById('supplierModal');
        if (modal) modal.classList.remove('hidden');
    }

    closeModal() {
        const modal = document.getElementById('supplierModal');
        if (modal) modal.classList.add('hidden');
    }
}