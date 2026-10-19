// Servicio centralizado para manejo de fechas
export class DateService {
    constructor() {
        this.currentYear = window.year || new Date().getFullYear();
        this.monthsNames = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];
    }

    getMonths() {
        return this.monthsNames.map((nombre, index) => ({
            id: index,
            nombre: nombre,
            numero: index + 1,
            nombreCompleto: `${nombre} ${this.currentYear}`,
            ultimoDia: this.getLastDayOfMonth(index),
            primerDia: 1
        }));
    }

    getLastDayOfMonth(monthIndex) {
        return new Date(this.currentYear, monthIndex + 1, 0).getDate();
    }

    getCurrentMonth() {
        const currentDate = new Date();
        return {
            index: currentDate.getMonth(),
            nombre: this.monthsNames[currentDate.getMonth()],
            year: currentDate.getFullYear()
        };
    }

    formatDate(year, month, day) {
        return `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    getSuggestedDate(monthIndex, useLastDay = true) {
        const today = new Date();
        let day = useLastDay ? this.getLastDayOfMonth(monthIndex) : 1;
        
        if (today.getMonth() === monthIndex && today.getFullYear() === this.currentYear) {
            day = Math.min(today.getDate(), this.getLastDayOfMonth(monthIndex));
        }
        
        return {
            date: this.formatDate(this.currentYear, monthIndex, day),
            readable: new Date(this.currentYear, monthIndex, day).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })
        };
    }
}