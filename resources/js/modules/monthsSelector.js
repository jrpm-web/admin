export class MonthsSelector {
    constructor(containerId, onMonthSelect) {
        this.container = document.getElementById(containerId);
        this.onMonthSelect = onMonthSelect;
        this.currentYear = new Date().getFullYear();
        this.monthsNames = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];
        
        if (this.container) {
            this.render();
        }
    }

    getMonths() {
        return this.monthsNames.map((nombre, index) => ({
            id: index,
            nombre: nombre,
            numero: index + 1,
            nombreCompleto: `${nombre} ${this.currentYear}`,
            ultimoDia: this.getLastDayOfMonth(index)
        }));
    }

    getLastDayOfMonth(monthIndex) {
        return new Date(this.currentYear, monthIndex + 1, 0).getDate();
    }

    render() {
        const months = this.getMonths();
        const currentMonth = new Date().getMonth();
        
        this.container.innerHTML = months.map(month => `
            <div class="month-card cursor-pointer rounded-xl p-3 text-center min-w-[90px] 
                        bg-gradient-to-br from-slate-50 to-white border-2 border-slate-200 
                        hover:border-blue-300 transition-all duration-300
                        ${currentMonth === month.id ? 'selected' : ''}"
                 data-month-id="${month.id}">
                <div class="month-number text-2xl font-bold text-slate-600">${month.numero}</div>
                <div class="month-name text-sm font-medium text-slate-700 mt-1">${month.nombre}</div>
                <div class="text-xs text-slate-400 mt-1">${this.currentYear}</div>
            </div>
        `).join('');
        
        this.attachEvents();
    }

    attachEvents() {
        this.container.querySelectorAll('.month-card').forEach(card => {
            card.addEventListener('click', (e) => {
                const monthId = parseInt(card.dataset.monthId);
                this.selectMonth(monthId);
                
                if (this.onMonthSelect) {
                    const months = this.getMonths();
                    const monthData = months[monthId];
                    const suggestedDate = this.getSuggestedDate(monthId);
                    
                    this.onMonthSelect({
                        ...monthData,
                        suggestedDate: suggestedDate.date
                    });
                }
            });
        });
    }

    selectMonth(monthId) {
        this.container.querySelectorAll('.month-card').forEach(card => {
            card.classList.remove('selected');
            if (parseInt(card.dataset.monthId) === monthId) {
                card.classList.add('selected');
            }
        });
    }

    getSuggestedDate(monthIndex) {
        const today = new Date();
        let day = this.getLastDayOfMonth(monthIndex);
        
        if (today.getMonth() === monthIndex && today.getFullYear() === this.currentYear) {
            day = Math.min(today.getDate(), day);
        }
        
        const date = new Date(this.currentYear, monthIndex, day);
        return {
            date: date.toISOString().split('T')[0],
            readable: date.toLocaleDateString('es-ES')
        };
    }
}