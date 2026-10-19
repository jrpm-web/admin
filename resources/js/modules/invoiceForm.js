export class InvoiceForm {
    constructor() {
        this.form = document.getElementById('facturaForm');
        if (this.form) {
            this.init();
        }
    }

    init() {
        this.setupEventListeners();
        this.loadSuppliers();
    }

    setupEventListeners() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        const mesSelect = document.getElementById('mes');
        if (mesSelect) {
            mesSelect.addEventListener('change', (e) => this.onMonthChange(e.target.value));
        }
    }

    async loadSuppliers() {
        try {
            const response = await fetch('/api/suppliers');
            const data = await response.json();
            
            const select = document.getElementById('proveedor');
            if (select && data.data) {
                const options = data.data.map(supplier => 
                    `<option value="${supplier.id}">${supplier.code} - ${supplier.name}</option>`
                ).join('');
                
                select.innerHTML = '<option value="">Seleccione un proveedor</option>' + options;
            }
        } catch (error) {
            console.error('Error loading suppliers:', error);
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch('/facturas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            if (response.ok) {
                this.showNotification('Factura registrada exitosamente', 'success');
                setTimeout(() => location.reload(), 1500);
            }
        } catch (error) {
            this.showNotification('Error al registrar factura', 'error');
        }
    }

    updateDateFromMonth(monthData) {
        const fechaInput = document.getElementById('fecha_factura');
        if (fechaInput && monthData.suggestedDate) {
            fechaInput.value = monthData.suggestedDate;
        }
    }

    onMonthChange(monthValue) {
        // Implementar lógica si es necesario
    }

    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-lg text-white
            ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 3000);
    }
}