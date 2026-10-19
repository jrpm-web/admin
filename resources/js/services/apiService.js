// Servicio centralizado para llamadas API
export class ApiService {
    constructor() {
        this.baseUrl = window.apiUrl;
        this.headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        };
    }

    async request(endpoint, options = {}) {
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                ...options,
                headers: { ...this.headers, ...options.headers }
            });
            
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error(`API Error [${endpoint}]:`, error);
            throw error;
        }
    }

    // Facturas
    async getInvoices(filters = {}) {
        const params = new URLSearchParams(filters).toString();
        return this.request(`/invoices?${params}`);
    }

    async createInvoice(data) {
        return this.request('/invoices', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    // Proveedores
    async getSuppliers() {
        return this.request('/suppliers');
    }

    async createSupplier(data) {
        return this.request('/suppliers', {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async updateSupplier(id, data) {
        return this.request(`/suppliers/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async deleteSupplier(id) {
        return this.request(`/suppliers/${id}`, {
            method: 'DELETE'
        });
    }

    // Reportes
    async getReport(type, params = {}) {
        return this.request(`/reports/${type}`, {
            method: 'POST',
            body: JSON.stringify(params)
        });
    }
}