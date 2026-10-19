<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Facturación Corporativa | Sistema de Gestión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 font-inter">

    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-block p-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-slate-800 mb-2">Gestión de Facturas</h1>
            <p class="text-slate-500 text-lg">Sistema corporativo de facturación</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Formulario -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Nueva Factura</h2>
                    <p class="text-blue-100 text-sm">Ingrese los datos de la factura</p>
                </div>
                
                <form id="facturaForm" class="p-6 space-y-5">
                    @csrf
                    
                    <!-- Número de Factura -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Número de Factura
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="C7 20h10M5 20h14M5 4h14M5 4v16M19 4v16M8 8h8M8 12h6"></path>
                                </svg>
                            </div>
                            <input type="text" name="numero_factura" id="numero_factura" required
                                class="w-full pl-10 pr-4 py-3 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none"
                                placeholder="FAC-001">
                        </div>
                    </div>

                    <!-- Proveedor -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Proveedor / Cliente
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"></path>
                                </svg>
                            </div>
                            <select name="proveedor" id="proveedor" required
                                class="w-full pl-10 pr-4 py-3 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none appearance-none bg-white">
                                <option value="">Seleccione un proveedor</option>
                                <option value="Proveedor A">Proveedor A - Tecnología S.L.</option>
                                <option value="Proveedor B">Proveedor B - Servicios Corp.</option>
                                <option value="Proveedor C">Proveedor C - Suministros Global</option>
                                <option value="Proveedor D">Proveedor D - Consultoría Elite</option>
                                <option value="Proveedor E">Proveedor E - Logística Express</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Monto de Factura -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Monto de Factura
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <span class="text-slate-500 font-semibold">$</span>
                            </div>
                            <input type="number" name="monto" id="monto" required step="0.01" min="0"
                                class="w-full pl-8 pr-4 py-3 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Botón Submit -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-xl hover:from-blue-700 hover:to-indigo-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Registrar Factura
                        </div>
                    </button>
                </form>
            </div>

            <!-- Gráfico -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Dashboard por Proveedor</h2>
                    <p class="text-indigo-100 text-sm">Totales segmentados por cliente/proveedor</p>
                </div>
                <div class="p-6">
                    <canvas id="graficoProveedores" class="w-full h-80"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabla de Facturas Recientes -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Facturas Recientes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200" id="tablaFacturas">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">N° Factura</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Proveedor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200" id="facturasBody">
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-400">No hay facturas registradas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let chart;

        async function cargarDatos() {
            try {
                const response = await fetch('/api/facturas');
                const facturas = await response.json();
                actualizarTabla(facturas);
                actualizarGrafico(facturas);
            } catch (error) {
                console.error('Error al cargar datos:', error);
            }
        }

        function actualizarTabla(facturas) {
            const tbody = document.getElementById('facturasBody');
            if (facturas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-slate-400">No hay facturas registradas</td></tr>';
                return;
            }

            tbody.innerHTML = facturas.map(factura => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">${factura.numero_factura}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">${factura.proveedor}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">$${parseFloat(factura.monto).toFixed(2)}</td>
                </tr>
            `).join('');
        }

        function actualizarGrafico(facturas) {
            const proveedoresMap = new Map();
            
            facturas.forEach(factura => {
                const proveedor = factura.proveedor;
                const monto = parseFloat(factura.monto);
                proveedoresMap.set(proveedor, (proveedoresMap.get(proveedor) || 0) + monto);
            });

            const labels = Array.from(proveedoresMap.keys());
            const datos = Array.from(proveedoresMap.values());

            const ctx = document.getElementById('graficoProveedores').getContext('2d');
            
            if (chart) {
                chart.destroy();
            }

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total por Proveedor (USD)',
                        data: datos,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(192, 132, 252, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(99, 102, 241)',
                            'rgb(139, 92, 246)',
                            'rgb(168, 85, 247)',
                            'rgb(192, 132, 252)'
                        ],
                        borderWidth: 2,
                        borderRadius: 8,
                        barPercentage: 0.7
                    }]
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
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Total: $${context.raw.toFixed(2)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(2);
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Envío del formulario
        document.getElementById('facturaForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                numero_factura: document.getElementById('numero_factura').value,
                proveedor: document.getElementById('proveedor').value,
                monto: document.getElementById('monto').value,
                _token: document.querySelector('input[name="_token"]').value
            };

            try {
                const response = await fetch('/api/facturas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': formData._token
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    // Limpiar formulario
                    document.getElementById('facturaForm').reset();
                    // Recargar datos
                    await cargarDatos();
                    
                    // Mostrar notificación de éxito
                    const btn = e.submitter;
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<div class="flex items-center justify-center gap-2"><svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Registrando...</div>';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                    }, 2000);
                } else {
                    alert('Error al registrar la factura');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        });

        // Cargar datos iniciales
        cargarDatos();
    </script>
</body>
</html>