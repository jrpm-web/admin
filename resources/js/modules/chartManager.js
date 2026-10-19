// Módulo para manejar gráficos de manera eficiente
export class ChartManager {
    constructor(chartData) {
        this.chartInstance = null;
        this.chartData = chartData;
        this.init();
    }

    init() {
        const canvas = document.getElementById('graficoLineas');
        if (!canvas) {
            console.warn('Canvas element not found');
            return;
        }
        
        this.createChart(this.chartData);
    }

    createChart(data) {
        const ctx = document.getElementById('graficoLineas').getContext('2d');
        
        // Destruir instancia anterior si existe
        if (this.chartInstance) {
            this.chartInstance.destroy();
        }

        // Configuración optimizada del gráfico
        this.chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: this.processDatasets(data.datasets || [])
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                family: 'Inter'
                            },
                            usePointStyle: true,
                            boxWidth: 10,
                            padding: 15
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('es-CO', {
                                        style: 'currency',
                                        currency: 'USD',
                                        minimumFractionDigits: 2
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: true,
                            borderDash: [5, 5]
                        },
                        title: {
                            display: true,
                            text: 'Monto Facturado (USD)',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: 'Inter'
                            },
                            color: '#64748b'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('es-CO', {
                                    style: 'currency',
                                    currency: 'USD',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value);
                            },
                            stepSize: this.calculateStepSize(data.maxValue || 10000)
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Meses del Año',
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: 'Inter'
                            },
                            color: '#64748b'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                elements: {
                    line: {
                        tension: 0.3,
                        borderWidth: 2
                    },
                    point: {
                        radius: 4,
                        hoverRadius: 6,
                        backgroundColor: 'white',
                        borderWidth: 2,
                        hoverBorderWidth: 3
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                },
                responsive: true,
                maintainAspectRatio: true,
                layout: {
                    padding: {
                        top: 20,
                        bottom: 20,
                        left: 10,
                        right: 10
                    }
                }
            }
        });
    }

    processDatasets(datasets) {
        // Colores optimizados para mejor visualización
        const colors = [
            { border: '#3b82f6', background: 'rgba(59, 130, 246, 0.1)' },  // Azul
            { border: '#ef4444', background: 'rgba(239, 68, 68, 0.1)' },   // Rojo
            { border: '#10b981', background: 'rgba(16, 185, 129, 0.1)' },   // Verde
            { border: '#f59e0b', background: 'rgba(245, 158, 11, 0.1)' },   // Naranja
            { border: '#8b5cf6', background: 'rgba(139, 92, 246, 0.1)' }     // Morado
        ];

        return datasets.map((dataset, index) => {
            const color = colors[index % colors.length];
            return {
                ...dataset,
                borderColor: color.border,
                backgroundColor: color.background,
                borderWidth: 2,
                pointBackgroundColor: color.border,
                pointBorderColor: '#ffffff',
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: color.border,
                pointHoverBorderWidth: 2,
                tension: 0.3,
                fill: true
            };
        });
    }

    calculateStepSize(maxValue) {
        // Calcular paso automático para el eje Y
        if (maxValue <= 1000) return 200;
        if (maxValue <= 5000) return 1000;
        if (maxValue <= 10000) return 2000;
        if (maxValue <= 50000) return 10000;
        return Math.ceil(maxValue / 10);
    }

    updateChart(newData) {
        if (!this.chartInstance) {
            this.createChart(newData);
            return;
        }

        // Actualizar datos de forma eficiente
        if (newData.labels) {
            this.chartInstance.data.labels = newData.labels;
        }
        
        if (newData.datasets) {
            const processedDatasets = this.processDatasets(newData.datasets);
            this.chartInstance.data.datasets = processedDatasets;
        }
        
        // Solo actualizar si hay cambios
        this.chartInstance.update('active');
    }

    addDataset(newDataset) {
        if (!this.chartInstance) return;
        
        const processed = this.processDatasets([newDataset]);
        this.chartInstance.data.datasets.push(processed[0]);
        this.chartInstance.update();
    }

    removeDataset(datasetIndex) {
        if (!this.chartInstance) return;
        
        this.chartInstance.data.datasets.splice(datasetIndex, 1);
        this.chartInstance.update();
    }

    destroy() {
        if (this.chartInstance) {
            this.chartInstance.destroy();
            this.chartInstance = null;
        }
    }

    exportAsImage() {
        const canvas = document.getElementById('graficoLineas');
        if (!canvas) return;
        
        const link = document.createElement('a');
        link.download = 'grafico-facturacion.png';
        link.href = canvas.toDataURL();
        link.click();
    }

    print() {
        const canvas = document.getElementById('graficoLineas');
        if (!canvas) return;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Reporte Gráfico - Facturación</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #333; text-align: center; }
                        .container { text-align: center; }
                        img { max-width: 100%; height: auto; }
                        .footer { margin-top: 20px; text-align: center; color: #666; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>Evolución de Facturación</h1>
                        <img src="${canvas.toDataURL()}" />
                        <div class="footer">
                            Generado: ${new Date().toLocaleString('es-ES')}
                        </div>
                    </div>
                    <script>
                        window.onload = () => window.print();
                    <\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    }
}