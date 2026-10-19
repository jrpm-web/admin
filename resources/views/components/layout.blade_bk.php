<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistema de Gestión | Resilia Agency' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/menu-improvements.css') }}">
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/menu-improvements.js') }}"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>

    <!-- Overlay para móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR MEJORADO -->
    <div class="sidebar" id="sidebar">
        <!-- Header del Sidebar -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-chart-line text-2xl mb-1 d-block"></i>
                    <h5 class="fw-bold mb-0">Sistema Gestión</h5>
                    <small class="opacity-75">Facturación Corporativa</small>
                </div>
                <div class="d-flex gap-2">
                    <!-- Botón de notificaciones -->
                    <div class="position-relative">
                        <button class="btn btn-link text-white p-0 border-0 position-relative" onclick="toggleNotifications()">
                            <i class="fas fa-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                  id="menuNotificationBadge" style="display: none;">0</span>
                        </button>
                    </div>
                    <!-- Botón de cerrar -->
                    <button onclick="toggleSidebar()" class="btn btn-link text-white p-0 border-0" aria-label="Cerrar menú">
                        <i class="fas fa-times fs-4"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Búsqueda en el menú -->
        <div class="p-3 border-bottom">
            <div class="position-relative">
                <input type="text" id="menuSearch" class="form-control form-control-sm" 
                       placeholder="🔍 Buscar en el menú..." 
                       onkeyup="searchMenu(this.value)"
                       autocomplete="off">
                <div id="menuSearchResults" class="position-absolute w-100 bg-white shadow-lg rounded-3 mt-1 d-none" 
                     style="max-height: 300px; overflow-y: auto; z-index: 1000;"></div>
            </div>
        </div>

        <!-- Sección de Favoritos -->
        <div class="p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">
                    <i class="fas fa-star text-warning me-1"></i> Favoritos
                </h6>
                <button class="btn btn-sm btn-link p-0 text-decoration-none" onclick="favoriteManager.toggleEdit()">
                    <i class="fas fa-edit" id="favoritesEditIcon"></i>
                </button>
            </div>
            <div id="favoritesContainer" class="d-flex flex-wrap gap-1">
                <!-- Los favoritos se cargarán dinámicamente -->
            </div>
        </div>

        <!-- Menú Principal -->
        <div class="p-3" style="overflow-y: auto; flex: 1;">
            <!-- Dashboard -->
            <div class="mb-2">
                <a href="{{ route('facturas.index') }}" 
                   class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 text-decoration-none text-dark">
                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="fas fa-home"></i>
                    </span>
                    <span class="fw-medium flex-grow-1">Dashboard</span>
                    <i class="fas fa-star text-muted favorite-star" style="font-size: 12px; cursor: pointer;" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Dashboard', 'fas fa-home', '{{ route('facturas.index') }}')"></i>
                </a>
            </div>

            <!-- Facturas -->
            <div class="mb-2">
                <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" 
                        onclick="toggleAccordion('facturasMenu')" 
                        type="button">
                    <span class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                        <i class="fas fa-file-invoice"></i>
                    </span>
                    <span class="fw-medium flex-grow-1">Facturas</span>
                    <span class="badge bg-danger rounded-pill me-2" id="pendingInvoicesBadge" style="display: none;">0</span>
                    <i class="fas fa-chevron-down transition-smooth" id="facturasMenuIcon"></i>
                    <i class="fas fa-star text-muted favorite-star" style="font-size: 12px; cursor: pointer; margin-left: 5px;" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Facturas', 'fas fa-file-invoice', '#')"></i>
                </button>
                <div id="facturasMenu" style="display: none; padding-left: 1.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    <button onclick="openModal('facturaModal')" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-plus-circle text-primary" style="width: 20px;"></i>
                        <span class="small">Crear Factura</span>
                    </button>
                    <button onclick="cargarFacturasDelServidor()" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-list text-success" style="width: 20px;"></i>
                        <span class="small">Ver Facturas</span>
                    </button>
                    <button onclick="window.location.href='/facturas/reportes'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-chart-bar text-info" style="width: 20px;"></i>
                        <span class="small">Reportes</span>
                    </button>
                    <button onclick="window.location.href='/facturas/estadisticas'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-chart-pie text-purple-500" style="width: 20px;"></i>
                        <span class="small">Estadísticas</span>
                    </button>
                </div>
            </div>
            
            <!-- Proveedores -->
            <div class="mb-2">
                <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" 
                        onclick="toggleAccordion('proveedoresMenu')" 
                        type="button">
                    <span class="badge bg-success bg-opacity-10 text-success p-2 rounded-3">
                        <i class="fas fa-truck"></i>
                    </span>
                    <span class="fw-medium flex-grow-1">Proveedores</span>
                    <i class="fas fa-chevron-down transition-smooth" id="proveedoresMenuIcon"></i>
                    <i class="fas fa-star text-muted favorite-star" style="font-size: 12px; cursor: pointer; margin-left: 5px;" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Proveedores', 'fas fa-truck', '#')"></i>
                </button>
                <div id="proveedoresMenu" style="display: none; padding-left: 1.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    <a href="{{ route('index') }}" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 text-decoration-none text-dark" tabindex="0">
                        <i class="fas fa-plus-circle text-primary" style="width: 20px;"></i>
                        <span class="small">Crear Proveedor</span>
                    </a>
                    <a href="{{ route('show') }}" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 text-decoration-none text-dark" tabindex="0">
                        <i class="fas fa-address-book text-info" style="width: 20px;"></i>
                        <span class="small">Lista Proveedores</span>
                    </a>
                    <button onclick="window.location.href='/proveedores/reportes'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-file-alt text-warning" style="width: 20px;"></i>
                        <span class="small">Reportes</span>
                    </button>
                </div>
            </div>

            <!-- Inventario -->
            <div class="mb-2">
                <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" 
                        onclick="toggleAccordion('inventarioMenu')" 
                        type="button">
                    <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                        <i class="fas fa-boxes"></i>
                    </span>
                    <span class="fw-medium flex-grow-1">Inventario</span>
                    <span class="badge bg-danger rounded-pill me-2" id="lowStockBadge" style="display: none;">0</span>
                    <i class="fas fa-chevron-down transition-smooth" id="inventarioMenuIcon"></i>
                    <i class="fas fa-star text-muted favorite-star" style="font-size: 12px; cursor: pointer; margin-left: 5px;" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Inventario', 'fas fa-boxes', '#')"></i>
                </button>
                <div id="inventarioMenu" style="display: none; padding-left: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    
                    <!-- Categorías -->
                    <div class="mb-1">
                        <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" 
                                onclick="toggleAccordion('categoriasInvMenu')" 
                                type="button" style="padding-left: 1rem;" tabindex="0">
                            <i class="fas fa-tags text-primary" style="width: 20px;"></i>
                            <span class="small flex-grow-1">Categorías</span>
                            <i class="fas fa-chevron-down transition-smooth" id="categoriasInvMenuIcon" style="font-size: 10px;"></i>
                        </button>
                        <div id="categoriasInvMenu" style="display: none; padding-left: 1.5rem;">
                            <a href="{{ route('categorias.index') }}" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-list text-info" style="width: 20px;"></i>
                                <span class="small">Ver Categorías</span>
                            </a>
                            <a href="{{ route('categorias.create') }}" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-plus-circle text-success" style="width: 20px;"></i>
                                <span class="small">Nueva Categoría</span>
                            </a>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="mb-1">
                        <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" 
                                onclick="toggleAccordion('productosInvMenu')" 
                                type="button" style="padding-left: 1rem;" tabindex="0">
                            <i class="fas fa-cube text-primary" style="width: 20px;"></i>
                            <span class="small flex-grow-1">Productos</span>
                            <i class="fas fa-chevron-down transition-smooth" id="productosInvMenuIcon" style="font-size: 10px;"></i>
                        </button>
                        <div id="productosInvMenu" style="display: none; padding-left: 1.5rem;">
                            <a href="{{ route('productos.index') }}" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-list text-info" style="width: 20px;"></i>
                                <span class="small">Ver Productos</span>
                            </a>
                            <a href="{{ route('productos.create') }}" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-plus-circle text-success" style="width: 20px;"></i>
                                <span class="small">Nuevo Producto</span>
                            </a>
                            <a href="{{ route('productos.index') }}" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-search text-primary" style="width: 20px;"></i>
                                <span class="small">Buscar Producto</span>
                            </a>
                        </div>
                    </div>

                    <!-- Movimientos -->
                    <div class="mb-1">
                        <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" 
                                onclick="toggleAccordion('movimientosInvMenu')" 
                                type="button" style="padding-left: 1rem;" tabindex="0">
                            <i class="fas fa-exchange-alt text-primary" style="width: 20px;"></i>
                            <span class="small flex-grow-1">Movimientos</span>
                            <i class="fas fa-chevron-down transition-smooth" id="movimientosInvMenuIcon" style="font-size: 10px;"></i>
                        </button>
                        <div id="movimientosInvMenu" style="display: none; padding-left: 1.5rem;">
                            <button onclick="window.location.href='/inventario/movimientos/entradas'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-arrow-down text-success" style="width: 20px;"></i>
                                <span class="small">Entradas</span>
                            </button>
                            <button onclick="window.location.href='/inventario/movimientos/salidas'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-arrow-up text-danger" style="width: 20px;"></i>
                                <span class="small">Salidas</span>
                            </button>
                            <button onclick="window.location.href='/inventario/movimientos/historial'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-history text-info" style="width: 20px;"></i>
                                <span class="small">Historial</span>
                            </button>
                        </div>
                    </div>

                    <!-- Reportes Inventario -->
                    <div class="mb-1">
                        <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" 
                                onclick="toggleAccordion('reportesInvMenu')" 
                                type="button" style="padding-left: 1rem;" tabindex="0">
                            <i class="fas fa-chart-pie text-primary" style="width: 20px;"></i>
                            <span class="small flex-grow-1">Reportes</span>
                            <i class="fas fa-chevron-down transition-smooth" id="reportesInvMenuIcon" style="font-size: 10px;"></i>
                        </button>
                        <div id="reportesInvMenu" style="display: none; padding-left: 1.5rem;">
                            <button onclick="window.location.href='/inventario/reportes/stock'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-boxes text-warning" style="width: 20px;"></i>
                                <span class="small">Stock Actual</span>
                            </button>
                            <button onclick="window.location.href='/inventario/reportes/valoracion'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-dollar-sign text-success" style="width: 20px;"></i>
                                <span class="small">Valoración</span>
                            </button>
                            <button onclick="window.location.href='/inventario/reportes/mas-vendidos'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-fire text-danger" style="width: 20px;"></i>
                                <span class="small">Más Vendidos</span>
                            </button>
                            <button onclick="window.location.href='/inventario/reportes/graficos'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                                <i class="fas fa-chart-line text-info" style="width: 20px;"></i>
                                <span class="small">Gráficos</span>
                            </button>
                        </div>
                    </div>

                    <hr class="my-2 border-light">
                    
                    <button onclick="window.location.href='/inventario/ajustes'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                        <i class="fas fa-sliders-h text-secondary" style="width: 20px;"></i>
                        <span class="small">Ajustes de Inventario</span>
                    </button>
                    <button onclick="window.location.href='/inventario/importar'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                        <i class="fas fa-file-import text-success" style="width: 20px;"></i>
                        <span class="small">Importar Productos</span>
                    </button>
                    <button onclick="window.location.href='/inventario/exportar'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                        <i class="fas fa-file-export text-info" style="width: 20px;"></i>
                        <span class="small">Exportar Inventario</span>
                    </button>
                    <button onclick="window.location.href='/inventario/escaneo'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3 submenu-item" tabindex="0">
                        <i class="fas fa-qrcode text-primary" style="width: 20px;"></i>
                        <span class="small">Escanear Códigos</span>
                    </button>
                </div>
            </div>

            <!-- Configuración -->
            <div class="mb-2">
                <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" 
                        onclick="toggleAccordion('configuracionMenu')" 
                        type="button">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary p-2 rounded-3">
                        <i class="fas fa-cog"></i>
                    </span>
                    <span class="fw-medium flex-grow-1">Configuración</span>
                    <i class="fas fa-chevron-down transition-smooth" id="configuracionMenuIcon"></i>
                    <i class="fas fa-star text-muted favorite-star" style="font-size: 12px; cursor: pointer; margin-left: 5px;" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Configuración', 'fas fa-cog', '#')"></i>
                </button>
                <div id="configuracionMenu" style="display: none; padding-left: 1.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    <button onclick="window.location.href='/configuracion/empresa'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-building text-primary" style="width: 20px;"></i>
                        <span class="small">Datos Empresa</span>
                    </button>
                    <button onclick="window.location.href='/configuracion/usuarios'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-users-cog text-success" style="width: 20px;"></i>
                        <span class="small">Usuarios</span>
                    </button>
                    <button onclick="window.location.href='/configuracion/impuestos'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-percent text-warning" style="width: 20px;"></i>
                        <span class="small">Impuestos</span>
                    </button>
                    <button onclick="window.location.href='/configuracion/backup'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-database text-info" style="width: 20px;"></i>
                        <span class="small">Backup</span>
                    </button>
                </div>
            </div>

            <!-- Reportes -->
            <div class="mb-2">
                <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" 
                        onclick="toggleAccordion('reportesMenu')" 
                        type="button">
                    <span class="badge bg-info bg-opacity-10 text-info p-2 rounded-3">
                        <i class="fas fa-chart-pie"></i>
                    </span>
                    <span class="fw-medium flex-grow-1">Reportes</span>
                    <i class="fas fa-chevron-down transition-smooth" id="reportesMenuIcon"></i>
                    <i class="fas fa-star text-muted favorite-star" style="font-size: 12px; cursor: pointer; margin-left: 5px;" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Reportes', 'fas fa-chart-pie', '#')"></i>
                </button>
                <div id="reportesMenu" style="display: none; padding-left: 1.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    <button onclick="window.location.href='/reportes/facturas'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-file-invoice text-primary" style="width: 20px;"></i>
                        <span class="small">Reporte Facturas</span>
                    </button>
                    <button onclick="window.location.href='/reportes/proveedores'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-truck text-success" style="width: 20px;"></i>
                        <span class="small">Reporte Proveedores</span>
                    </button>
                    <button onclick="window.location.href='/reportes/financieros'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-coins text-warning" style="width: 20px;"></i>
                        <span class="small">Reportes Financieros</span>
                    </button>
                    <button onclick="window.location.href='/reportes/graficos'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-chart-line text-info" style="width: 20px;"></i>
                        <span class="small">Gráficos Estadísticos</span>
                    </button>
                </div>
            </div>

            <!-- Herramientas -->
            <div class="mb-2">
                <button class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" 
                        onclick="toggleAccordion('herramientasMenu')" 
                        type="button">
                    <span class="badge bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                        <i class="fas fa-tools"></i>
                    </span>
                    <span class="fw-medium flex-grow-1">Herramientas</span>
                    <i class="fas fa-chevron-down transition-smooth" id="herramientasMenuIcon"></i>
                    <i class="fas fa-star text-muted favorite-star" style="font-size: 12px; cursor: pointer; margin-left: 5px;" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Herramientas', 'fas fa-tools', '#')"></i>
                </button>
                <div id="herramientasMenu" style="display: none; padding-left: 1.5rem; padding-right: 0.5rem; padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    <button onclick="cargarTodosLosDatos()" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-sync-alt text-primary" style="width: 20px;"></i>
                        <span class="small">Actualizar Datos</span>
                    </button>
                    <button onclick="window.location.href='/herramientas/importar'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-file-import text-success" style="width: 20px;"></i>
                        <span class="small">Importar Datos</span>
                    </button>
                    <button onclick="window.location.href='/herramientas/exportar'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-file-export text-info" style="width: 20px;"></i>
                        <span class="small">Exportar Datos</span>
                    </button>
                    <button onclick="window.location.href='/herramientas/limpiar'" class="menu-item w-100 text-start border-0 bg-transparent d-flex align-items-center gap-3 py-2 px-3 rounded-3" tabindex="0">
                        <i class="fas fa-broom text-danger" style="width: 20px;"></i>
                        <span class="small">Limpiar Cache</span>
                    </button>
                </div>
            </div>

            <!-- Footer del menú con atajos -->
            <div class="mt-3 pt-3 border-top">
                <button class="btn btn-sm btn-outline-secondary w-100 mb-1" onclick="showMenuShortcutsHelp()">
                    <i class="fas fa-keyboard me-2"></i> Atajos de Teclado
                </button>
                <button class="btn btn-sm btn-outline-secondary w-100" onclick="toggleDarkMode()">
                    <i class="fas fa-moon me-2" id="darkModeIcon"></i> Modo Oscuro
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div id="mainWrapper" class="main-wrapper">
        <nav class="navbar navbar-expand-lg bg-white rounded-3 shadow-sm p-3 mb-4">
            <div class="container-fluid">
                <button class="btn btn-outline-secondary border-0" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <i class="fas fa-bars fs-4"></i>
                </button>
                
                <!-- Indicador de página actual -->
                <div class="d-none d-md-block ms-3">
                    <small class="text-muted" id="currentPageIndicator">
                        <i class="fas fa-arrow-right me-1"></i> 
                        <span id="currentPageName">Dashboard</span>
                    </small>
                </div>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-calendar-alt me-1"></i> {{ date('d/m/Y') }}
                    </span>
                    
                    <!-- Estado de conexión -->
                    <span id="connectionStatus" class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill d-none">
                        <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> Conectado
                    </span>
                    
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none text-dark p-0" data-bs-toggle="dropdown">
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="d-none d-md-inline">Usuario</span>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container">
        <div id="toast" class="toast-custom bg-white border-0 shadow-lg" role="alert" aria-live="polite" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-check-circle text-success fs-5" id="toastIcon"></i>
                    <span id="toastMessage">Mensaje de notificación</span>
                </div>
                <button type="button" class="btn-close btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>