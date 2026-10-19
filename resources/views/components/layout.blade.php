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
    
    <style>
        /* ======================================================
           ESTILOS DEL MENÚ - CON MEJORAS
           ====================================================== */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .custom-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 280px !important;
            height: 100vh !important;
            background: #ffffff !important;
            z-index: 999999 !important;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            transform: translateX(0) !important;
        }

        .custom-sidebar.hidden {
            transform: translateX(-100%) !important;
        }

        /* Scrollbar personalizado */
        .custom-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-sidebar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        /* ===== MAIN WRAPPER ===== */
        .custom-main-wrapper {
            margin-left: 280px !important;
            padding: 20px !important;
            min-height: 100vh !important;
            background: #f8f9fa !important;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            width: calc(100% - 280px) !important;
        }

        .custom-main-wrapper.expanded {
            margin-left: 0 !important;
            width: 100% !important;
        }

        /* ===== HEADER DEL SIDEBAR MEJORADO ===== */
        .custom-sidebar-header {
            background: linear-gradient(135deg, #2563eb, #4f46e5) !important;
            padding: 20px !important;
            color: white !important;
            flex-shrink: 0 !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .custom-sidebar-header::after {
            content: '' !important;
            position: absolute !important;
            top: -50% !important;
            right: -20% !important;
            width: 200px !important;
            height: 200px !important;
            background: rgba(255,255,255,0.05) !important;
            border-radius: 50% !important;
            pointer-events: none !important;
        }

        .custom-sidebar-header .logo-icon {
            font-size: 28px !important;
            margin-bottom: 4px !important;
            display: block !important;
            background: rgba(255,255,255,0.15) !important;
            width: 48px !important;
            height: 48px !important;
            border-radius: 12px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .custom-sidebar-header .brand-title {
            font-weight: 700 !important;
            font-size: 18px !important;
            margin: 0 !important;
            letter-spacing: -0.5px !important;
        }

        .custom-sidebar-header .brand-subtitle {
            opacity: 0.8 !important;
            font-size: 12px !important;
            font-weight: 400 !important;
        }

        /* ===== BOTÓN TOGGLE ===== */
        .custom-menu-toggle {
            background: transparent !important;
            border: none !important;
            padding: 8px 12px !important;
            font-size: 20px !important;
            cursor: pointer !important;
            color: #1a1a2e !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 10px !important;
            position: relative !important;
            z-index: 999999 !important;
            font-weight: 500 !important;
        }

        .custom-menu-toggle:hover {
            background: rgba(0,0,0,0.05) !important;
        }

        .custom-menu-toggle .toggle-icon {
            font-size: 22px !important;
        }

        .custom-menu-toggle .toggle-label {
            font-size: 13px !important;
            font-weight: 500 !important;
            display: none !important;
        }

        @media (min-width: 768px) {
            .custom-menu-toggle .toggle-label {
                display: inline !important;
            }
        }

        /* ===== BOTÓN DE OCULTAR EN SIDEBAR ===== */
        .custom-sidebar-toggle-btn {
            background: rgba(255,255,255,0.15) !important;
            border: none !important;
            color: white !important;
            padding: 6px 10px !important;
            cursor: pointer !important;
            font-size: 16px !important;
            border-radius: 6px !important;
            transition: all 0.2s ease !important;
            position: relative !important;
            z-index: 1 !important;
        }

        .custom-sidebar-toggle-btn:hover {
            background: rgba(255,255,255,0.25) !important;
            transform: scale(1.05) !important;
        }

        /* ===== BÚSQUEDA MEJORADA ===== */
        .custom-search-wrapper {
            padding: 12px 16px !important;
            border-bottom: 1px solid #e5e7eb !important;
            flex-shrink: 0 !important;
        }

        .custom-search-wrapper .form-control {
            border-radius: 10px !important;
            border: 1px solid #e5e7eb !important;
            padding: 8px 14px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            background: #f9fafb !important;
        }

        .custom-search-wrapper .form-control:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1) !important;
            background: white !important;
        }

        .custom-search-wrapper .search-shortcut {
            position: absolute !important;
            right: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 10px !important;
            color: #9ca3af !important;
            background: #e5e7eb !important;
            padding: 2px 8px !important;
            border-radius: 4px !important;
            font-weight: 600 !important;
            pointer-events: none !important;
        }

        /* ===== FAVORITOS MEJORADOS ===== */
        .custom-favorites-wrapper {
            padding: 12px 16px !important;
            border-bottom: 1px solid #e5e7eb !important;
            flex-shrink: 0 !important;
        }

        .custom-favorites-wrapper .favorites-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 8px !important;
        }

        .custom-favorites-wrapper .favorites-title {
            font-weight: 600 !important;
            font-size: 13px !important;
            margin: 0 !important;
            color: #374151 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .custom-favorites-wrapper .favorites-edit-btn {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            font-size: 13px !important;
            color: #6b7280 !important;
            transition: color 0.2s ease !important;
        }

        .custom-favorites-wrapper .favorites-edit-btn:hover {
            color: #2563eb !important;
        }

        .custom-favorite-item {
            background: #f3f4f6 !important;
            border-radius: 6px !important;
            padding: 4px 10px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            font-size: 12px !important;
            transition: all 0.2s ease !important;
            margin: 2px !important;
        }

        .custom-favorite-item:hover {
            background: #e5e7eb !important;
        }

        .custom-favorite-item a {
            color: #1a1a2e !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
        }

        .custom-favorite-item .remove-fav {
            background: none !important;
            border: none !important;
            color: #ef4444 !important;
            padding: 0 2px !important;
            cursor: pointer !important;
            font-size: 11px !important;
            opacity: 0 !important;
            transition: opacity 0.2s ease !important;
        }

        .custom-favorite-item:hover .remove-fav {
            opacity: 1 !important;
        }

        /* ===== MENÚ ITEMS MEJORADOS ===== */
        .custom-menu-item {
            transition: all 0.2s ease !important;
            cursor: pointer !important;
            font-size: 0.9rem !important;
            border: none !important;
            background: transparent !important;
            color: #1a1a2e !important;
            width: 100% !important;
            text-align: left !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            text-decoration: none !important;
            position: relative !important;
        }

        .custom-menu-item:hover {
            background-color: #f0f7ff !important;
            transform: translateX(3px) !important;
        }

        .custom-menu-item.active {
            background-color: #e8f0fe !important;
            border-left: 3px solid #2563eb !important;
        }

        .custom-menu-item .menu-icon {
            width: 32px !important;
            height: 32px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 8px !important;
            flex-shrink: 0 !important;
            font-size: 14px !important;
        }

        .custom-menu-item .menu-text {
            flex-grow: 1 !important;
            font-weight: 500 !important;
        }

        .custom-menu-item .menu-badge {
            font-size: 10px !important;
            padding: 2px 8px !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
        }

        .custom-submenu-item {
            padding-left: 2rem !important;
            font-size: 0.85rem !important;
        }

        .custom-submenu-item .menu-icon {
            width: 24px !important;
            height: 24px !important;
            font-size: 12px !important;
        }

        .custom-chevron {
            transition: transform 0.3s ease !important;
            font-size: 12px !important;
            color: #9ca3af !important;
        }

        .custom-chevron.rotated {
            transform: rotate(180deg) !important;
        }

        /* ===== SECCIÓN DE USUARIO EN EL MENÚ ===== */
        .custom-sidebar-user {
            padding: 16px !important;
            border-top: 1px solid #e5e7eb !important;
            flex-shrink: 0 !important;
            background: #f9fafb !important;
        }

        .custom-sidebar-user .user-info {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
        }

        .custom-sidebar-user .user-avatar {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #2563eb, #4f46e5) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            font-weight: 600 !important;
            font-size: 16px !important;
            flex-shrink: 0 !important;
        }

        .custom-sidebar-user .user-name {
            font-weight: 600 !important;
            font-size: 14px !important;
            color: #1a1a2e !important;
        }

        .custom-sidebar-user .user-role {
            font-size: 12px !important;
            color: #6b7280 !important;
        }

        .custom-sidebar-user .user-status {
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
            font-size: 10px !important;
            color: #22c55e !important;
        }

        .custom-sidebar-user .user-status .dot {
            width: 6px !important;
            height: 6px !important;
            border-radius: 50% !important;
            background: #22c55e !important;
            animation: pulse-dot 2s infinite !important;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .custom-sidebar {
                transform: translateX(-100%) !important;
                width: 320px !important;
            }

            .custom-sidebar.open {
                transform: translateX(0) !important;
            }

            .custom-main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .custom-main-wrapper.expanded {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .custom-sidebar-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 999998 !important;
                display: none !important;
                opacity: 0 !important;
                transition: opacity 0.3s ease !important;
            }

            .custom-sidebar-overlay.show {
                display: block !important;
                opacity: 1 !important;
            }

            body.sidebar-open .custom-sidebar {
                transform: translateX(0) !important;
            }

            body.sidebar-open .custom-sidebar-overlay {
                display: block !important;
                opacity: 1 !important;
            }

            body.sidebar-open {
                overflow: hidden !important;
            }
        }

        /* ===== TOAST MEJORADO ===== */
        .custom-toast-container {
            position: fixed !important;
            bottom: 20px !important;
            right: 20px !important;
            z-index: 9999999 !important;
        }

        .custom-toast {
            min-width: 280px !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
            transform: translateX(120%) !important;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
            background: white !important;
            padding: 16px 20px !important;
            border-left: 4px solid #22c55e !important;
        }

        .custom-toast.error {
            border-left-color: #ef4444 !important;
        }

        .custom-toast.info {
            border-left-color: #3b82f6 !important;
        }

        .custom-toast.warning {
            border-left-color: #eab308 !important;
        }

        .custom-toast.show {
            transform: translateX(0) !important;
        }

        /* ===== MODO OSCURO MEJORADO ===== */
        .dark-mode .custom-sidebar {
            background: #1a1a2e !important;
        }

        .dark-mode .custom-sidebar-header {
            background: linear-gradient(135deg, #1e293b, #0f172a) !important;
        }

        .dark-mode .custom-sidebar-user {
            background: #0f172a !important;
            border-top-color: #2d2d44 !important;
        }

        .dark-mode .custom-sidebar-user .user-name {
            color: #e2e8f0 !important;
        }

        .dark-mode .custom-sidebar-user .user-role {
            color: #94a3b8 !important;
        }

        .dark-mode .custom-menu-item {
            color: #e2e8f0 !important;
        }

        .dark-mode .custom-menu-item:hover {
            background-color: #2d2d44 !important;
        }

        .dark-mode .custom-menu-item.active {
            background-color: #1e293b !important;
        }

        .dark-mode .custom-main-wrapper {
            background: #0f0f1a !important;
        }

        .dark-mode .custom-favorite-item {
            background: #2d2d44 !important;
        }

        .dark-mode .custom-favorite-item a {
            color: #e2e8f0 !important;
        }

        .dark-mode .custom-search-wrapper .form-control {
            background: #2d2d44 !important;
            color: #e2e8f0 !important;
            border-color: #3d3d54 !important;
        }

        .dark-mode .custom-search-wrapper .form-control::placeholder {
            color: #94a3b8 !important;
        }

        .dark-mode .custom-search-wrapper .search-shortcut {
            background: #3d3d54 !important;
            color: #94a3b8 !important;
        }

        .dark-mode .custom-toast {
            background: #1a1a2e !important;
            color: #e2e8f0 !important;
        }

        .dark-mode .custom-menu-toggle {
            color: #e2e8f0 !important;
        }

        .dark-mode .custom-menu-toggle:hover {
            background: rgba(255,255,255,0.05) !important;
        }

        .dark-mode .custom-favorites-wrapper .favorites-title {
            color: #94a3b8 !important;
        }

        .dark-mode .navbar {
            background: #1a1a2e !important;
        }

        .dark-mode .navbar .text-dark {
            color: #e2e8f0 !important;
        }

        .dark-mode .navbar .text-muted {
            color: #94a3b8 !important;
        }

        /* ===== TOOLTIP PARA EL MENÚ ===== */
        .custom-tooltip {
            position: relative !important;
        }

        .custom-tooltip::after {
            content: attr(data-tooltip) !important;
            position: absolute !important;
            left: 100% !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: #1a1a2e !important;
            color: white !important;
            padding: 4px 10px !important;
            border-radius: 4px !important;
            font-size: 11px !important;
            white-space: nowrap !important;
            opacity: 0 !important;
            pointer-events: none !important;
            transition: opacity 0.2s ease !important;
            margin-left: 8px !important;
        }

        .custom-tooltip:hover::after {
            opacity: 1 !important;
        }
        /* ============================================================
   ESTILOS PARA EL DROPDOWN DE USUARIO
   ============================================================ */

/* Gradiente para el avatar */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Estilos para los items del dropdown */
.dropdown-item {
    transition: all 0.2s ease !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
}

.dropdown-item:hover {
    background-color: #f8f9fa !important;
    transform: translateX(3px) !important;
}

.dropdown-item.text-danger:hover {
    background-color: #fef2f2 !important;
    color: #dc2626 !important;
}

/* Dropdown shadow y bordes */
.dropdown-menu {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12) !important;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
    animation: dropdownFadeIn 0.25s ease !important;
}

@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modo oscuro */
.dark-mode .dropdown-menu {
    background: #1a1a2e !important;
    border-color: #2d2d44 !important;
}

.dark-mode .dropdown-item {
    color: #e2e8f0 !important;
}

.dark-mode .dropdown-item:hover {
    background-color: #2d2d44 !important;
}

.dark-mode .dropdown-item.text-danger:hover {
    background-color: #2d1a1a !important;
}

.dark-mode .border-bottom {
    border-color: #2d2d44 !important;
}

.dark-mode .border-top {
    border-color: #2d2d44 !important;
}
    </style>
</head>
<body>

    <!-- ===== OVERLAY ===== -->
    <div class="custom-sidebar-overlay" id="customSidebarOverlay"></div>

    <!-- ===== SIDEBAR MEJORADO ===== -->
    <div class="custom-sidebar" id="customSidebar">
        <!-- Header mejorado -->
        <div class="custom-sidebar-header">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div class="logo-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="brand-title">Sistema Gestión</h5>
                    <span class="brand-subtitle">Administración Corporativa</span>
                </div>
                <div style="display: flex; gap: 6px; align-items: center;">
                    <button onclick="toggleNotifications()" style="background: rgba(255,255,255,0.15); border: none; color: white; padding: 6px 8px; border-radius: 6px; cursor: pointer; position: relative; transition: background 0.2s ease;">
                        <i class="fas fa-bell" style="font-size: 16px;"></i>
                        <span id="customNotificationBadge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 50%; padding: 1px 5px; font-size: 9px;">0</span>
                    </button>
                    <button onclick="toggleSidebar()" class="custom-sidebar-toggle-btn" title="Ocultar menú">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Búsqueda mejorada -->
        <div class="custom-search-wrapper">
            <div style="position: relative;">
                <input type="text" id="customMenuSearch" class="form-control form-control-sm" 
                       placeholder="🔍 Buscar en el menú..." 
                       onkeyup="searchMenu(this.value)"
                       autocomplete="off">
                <span class="search-shortcut">Ctrl+/</span>
                <div id="customSearchResults" class="custom-search-results" 
                     style="display: none; position: absolute; width: 100%; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px; margin-top: 4px; max-height: 300px; overflow-y: auto; z-index: 9999999;"></div>
            </div>
        </div>

        <!-- Favoritos mejorados -->
        <div class="custom-favorites-wrapper">
            <div class="favorites-header">
                <h6 class="favorites-title">
                    <i class="fas fa-star" style="color: #fbbf24; margin-right: 6px;"></i>Favoritos
                </h6>
                <button onclick="favoriteManager.toggleEdit()" class="favorites-edit-btn">
                    <i class="fas fa-edit" id="customFavoritesEditIcon"></i>
                </button>
            </div>
            <div id="customFavoritesContainer" style="display: flex; flex-wrap: wrap; gap: 4px; min-height: 30px;">
                <!-- Se llena dinámicamente -->
            </div>
        </div>

        <!-- ===== MENÚ PRINCIPAL ===== -->
        <div class="custom-menu-scroll" style="overflow-y: auto; flex: 1; padding: 8px 12px;">

            <!-- Dashboard -->
            <div style="margin-bottom: 4px;">
                <a href="{{ route('dashboard') }}" 
                   class="custom-menu-item"
                   data-menu-item="dashboard">
                    <span class="menu-icon" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                        <i class="fas fa-home"></i>
                    </span>
                    <span class="menu-text">Dashboard</span>
                    <i class="fas fa-star custom-favorite-star" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Dashboard', 'fas fa-home', '{{ route('dashboard') }}')"></i>
                </a>
            </div>

            <!-- Facturas -->
            <div style="margin-bottom: 4px;" data-menu-group="facturas">
                <button class="custom-menu-item" 
                        onclick="toggleAccordion('customFacturasMenu')" 
                        type="button"
                        aria-expanded="false"
                        data-menu-item="facturas">
                    <span class="menu-icon" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                        <i class="fas fa-file-invoice"></i>
                    </span>
                    <span class="menu-text">Facturas</span>
                    <span class="menu-badge bg-danger text-white" id="customPendingBadge" style="display: none;">0</span>
                    <i class="fas fa-chevron-down custom-chevron" id="customFacturasMenuIcon"></i>
                    <i class="fas fa-star custom-favorite-star" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Facturas', 'fas fa-file-invoice', '#facturas')"></i>
                </button>
                <div id="customFacturasMenu" style="display: none; padding-left: 0.5rem; padding-top: 4px; padding-bottom: 4px;">
    <!-- Crear Factura -->
    <a href="{{ route('facturas.create') }}" class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
        <span class="menu-icon" style="color: #2563eb;"><i class="fas fa-plus-circle"></i></span>
        <span class="menu-text" style="font-size: 0.85rem;">Crear Factura</span>
    </a>
    
    <!-- Ver Facturas -->
    <a href="{{ route('facturas.index') }}" class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
        <span class="menu-icon" style="color: #22c55e;"><i class="fas fa-list"></i></span>
        <span class="menu-text" style="font-size: 0.85rem;">Ver Facturas</span>
    </a>
    
    <!-- Reportes -->
    <a href="{{ route('facturas.reportes') }}" class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
        <span class="menu-icon" style="color: #3b82f6;"><i class="fas fa-chart-bar"></i></span>
        <span class="menu-text" style="font-size: 0.85rem;">Reportes</span>
    </a>
    
    <!-- Estadísticas -->
    <a href="{{ route('facturas.estadisticas') }}" class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
        <span class="menu-icon" style="color: #8b5cf6;"><i class="fas fa-chart-pie"></i></span>
        <span class="menu-text" style="font-size: 0.85rem;">Estadísticas</span>
    </a>
</div>
            </div>

            <!-- Proveedores -->
            <div style="margin-bottom: 4px;" data-menu-group="proveedores">
                <button class="custom-menu-item" 
                        onclick="toggleAccordion('customProveedoresMenu')" 
                        type="button"
                        aria-expanded="false"
                        data-menu-item="proveedores">
                    <span class="menu-icon" style="background: rgba(34,197,94,0.1); color: #22c55e;">
                        <i class="fas fa-truck"></i>
                    </span>
                    <span class="menu-text">Proveedores</span>
                    <i class="fas fa-chevron-down custom-chevron" id="customProveedoresMenuIcon"></i>
                    <i class="fas fa-star custom-favorite-star" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Proveedores', 'fas fa-truck', '#proveedores')"></i>
                </button>
                <div id="customProveedoresMenu" style="display: none; padding-left: 0.5rem; padding-top: 4px; padding-bottom: 4px;">
                    <a href="{{ route('proveedores.create') }}" class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #2563eb;"><i class="fas fa-plus-circle"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Crear Proveedor</span>
                    </a>
                    <a href="{{ route('proveedores.index') }}" class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #3b82f6;"><i class="fas fa-address-book"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Lista Proveedores</span>
                    </a>
                    <button onclick="window.location.href='/proveedores/reportes'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #eab308;"><i class="fas fa-file-alt"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Reportes</span>
                    </button>
                </div>
            </div>

            <!-- Inventario -->
            <div style="margin-bottom: 4px;" data-menu-group="inventario">
                <button class="custom-menu-item" 
                        onclick="toggleAccordion('customInventarioMenu')" 
                        type="button"
                        aria-expanded="false"
                        data-menu-item="inventario">
                    <span class="menu-icon" style="background: rgba(234,179,8,0.1); color: #eab308;">
                        <i class="fas fa-boxes"></i>
                    </span>
                    <span class="menu-text">Inventario</span>
                    <span class="menu-badge bg-danger text-white" id="customLowStockBadge" style="display: none;">0</span>
                    <i class="fas fa-chevron-down custom-chevron" id="customInventarioMenuIcon"></i>
                    <i class="fas fa-star custom-favorite-star" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Inventario', 'fas fa-boxes', '#inventario')"></i>
                </button>
                <div id="customInventarioMenu" style="display: none; padding-left: 0.5rem; padding-top: 4px; padding-bottom: 4px;">
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
            <div style="margin-bottom: 4px;" data-menu-group="configuracion">
                <button class="custom-menu-item" 
                        onclick="toggleAccordion('customConfiguracionMenu')" 
                        type="button"
                        aria-expanded="false"
                        data-menu-item="configuracion">
                    <span class="menu-icon" style="background: rgba(107,114,128,0.1); color: #6b7280;">
                        <i class="fas fa-cog"></i>
                    </span>
                    <span class="menu-text">Configuración</span>
                    <i class="fas fa-chevron-down custom-chevron" id="customConfiguracionMenuIcon"></i>
                    <i class="fas fa-star custom-favorite-star" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Configuración', 'fas fa-cog', '#configuracion')"></i>
                </button>
                <div id="customConfiguracionMenu" style="display: none; padding-left: 0.5rem; padding-top: 4px; padding-bottom: 4px;">
                    <button onclick="window.location.href='/configuracion/empresa'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #2563eb;"><i class="fas fa-building"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Datos Empresa</span>
                    </button>
                    <button onclick="window.location.href='/configuracion/usuarios'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #22c55e;"><i class="fas fa-users-cog"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Usuarios</span>
                    </button>
                    <button onclick="window.location.href='/configuracion/impuestos'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #eab308;"><i class="fas fa-percent"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Impuestos</span>
                    </button>
                </div>
            </div>

            <!-- Reportes -->
            <div style="margin-bottom: 4px;" data-menu-group="reportes">
                <button class="custom-menu-item" 
                        onclick="toggleAccordion('customReportesMenu')" 
                        type="button"
                        aria-expanded="false"
                        data-menu-item="reportes">
                    <span class="menu-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                        <i class="fas fa-chart-pie"></i>
                    </span>
                    <span class="menu-text">Reportes</span>
                    <i class="fas fa-chevron-down custom-chevron" id="customReportesMenuIcon"></i>
                    <i class="fas fa-star custom-favorite-star" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Reportes', 'fas fa-chart-pie', '#reportes')"></i>
                </button>
                <div id="customReportesMenu" style="display: none; padding-left: 0.5rem; padding-top: 4px; padding-bottom: 4px;">
                    <button onclick="window.location.href='/reportes/facturas'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #2563eb;"><i class="fas fa-file-invoice"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Reporte Facturas</span>
                    </button>
                    <button onclick="window.location.href='/reportes/proveedores'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #22c55e;"><i class="fas fa-truck"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Reporte Proveedores</span>
                    </button>
                    <button onclick="window.location.href='/reportes/financieros'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #eab308;"><i class="fas fa-coins"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Financieros</span>
                    </button>
                </div>
            </div>
            <!-- ===== INCIDENCIAS (MEJORADO) ===== -->
            <div style="margin-bottom: 4px;" data-menu-group="incidencias">
                <button class="custom-menu-item"
                        onclick="toggleAccordion('customIncidenciasMenu')"
                        type="button"
                        aria-expanded="false"
                        data-menu-item="incidencias">
                    <span class="menu-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                        <i class="fas fa-triangle-exclamation"></i>
                    </span>
                    <span class="menu-text">Incidencias</span>
                    <span class="menu-badge bg-danger text-white" id="customIncidenciasBadge" style="display: none;">0</span>
                    <i class="fas fa-chevron-down custom-chevron" id="customIncidenciasMenuIcon"></i>
                    <i class="fas fa-star custom-favorite-star"
                       onclick="event.stopPropagation(); addToFavorites(this, 'Incidencias', 'fas fa-triangle-exclamation', '{{ route('incidencias.index') }}')"></i>
                </button>

                <div id="customIncidenciasMenu" style="display: none; padding-left: 0.5rem; padding-top: 4px; padding-bottom: 4px;">

                    <!-- Nueva Incidencia -->
                    <a href="{{ route('incidencias.create') }}"
                       class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #ef4444;"><i class="fas fa-plus-circle"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Nueva Incidencia</span>
                    </a>

                    <!-- Todas -->
                    <a href="{{ route('incidencias.index') }}"
                       class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #2563eb;"><i class="fas fa-list"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Todas las Incidencias</span>
                    </a>

                    <!-- Abiertas -->
                    <a href="{{ route('incidencias.abiertas') }}"
                       class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #f59e0b;"><i class="fas fa-folder-open"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Abiertas</span>
                        <span class="menu-badge bg-warning text-dark" id="customIncidenciasOpenBadge" style="display: none;">0</span>
                    </a>

                    <!-- Asignadas a mí -->
                    <a href="{{ route('incidencias.asignadas') }}"
                       class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #3b82f6;"><i class="fas fa-user-check"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Asignadas a mí</span>
                    </a>

                    <!-- Finalizadas -->
                    <a href="{{ route('incidencias.finalizadas') }}"
                       class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #22c55e;"><i class="fas fa-circle-check"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Finalizadas</span>
                    </a>

                    <hr class="my-2 border-light">

                    <!-- Estadísticas -->
                    <a href="{{ route('incidencias.estadisticas') }}"
                       class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #8b5cf6;"><i class="fas fa-chart-pie"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Estadísticas</span>
                    </a>

                    <!-- Reportes -->
                    <a href="{{ route('incidencias.reportes') }}"
                       class="custom-menu-item custom-submenu-item text-decoration-none" tabindex="0">
                        <span class="menu-icon" style="color: #6366f1;"><i class="fas fa-chart-bar"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Reportes</span>
                    </a>

                </div>
            </div>

            <!-- Herramientas -->
            <div style="margin-bottom: 4px;" data-menu-group="herramientas">
                <button class="custom-menu-item" 
                        onclick="toggleAccordion('customHerramientasMenu')" 
                        type="button"
                        aria-expanded="false"
                        data-menu-item="herramientas">
                    <span class="menu-icon" style="background: rgba(234,179,8,0.1); color: #eab308;">
                        <i class="fas fa-tools"></i>
                    </span>
                    <span class="menu-text">Herramientas</span>
                    <i class="fas fa-chevron-down custom-chevron" id="customHerramientasMenuIcon"></i>
                    <i class="fas fa-star custom-favorite-star" 
                       onclick="event.stopPropagation(); addToFavorites(this, 'Herramientas', 'fas fa-tools', '#herramientas')"></i>
                </button>
                <div id="customHerramientasMenu" style="display: none; padding-left: 0.5rem; padding-top: 4px; padding-bottom: 4px;">
                    <button onclick="cargarTodosLosDatos()" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #2563eb;"><i class="fas fa-sync-alt"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Actualizar Datos</span>
                    </button>
                    <button onclick="window.location.href='/herramientas/importar'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #22c55e;"><i class="fas fa-file-import"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Importar</span>
                    </button>
                    <button onclick="window.location.href='/herramientas/exportar'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #3b82f6;"><i class="fas fa-file-export"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Exportar</span>
                    </button>
                    <button onclick="window.location.href='/herramientas/limpiar'" class="custom-menu-item custom-submenu-item" tabindex="0">
                        <span class="menu-icon" style="color: #ef4444;"><i class="fas fa-broom"></i></span>
                        <span class="menu-text" style="font-size: 0.85rem;">Limpiar Cache</span>
                    </button>
                </div>
            </div>

            <!-- Separador visual -->
            <hr style="margin: 12px 0; border-color: #e5e7eb;">

            
        </div>

        <!-- ===== USUARIO EN EL MENÚ ===== -->
        <div class="custom-sidebar-user">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div style="flex: 1;">
                    <div class="user-name">{{ strtoupper(Auth::user()->name) }}</div>
                    <div class="user-role">Super Admin</div>
                    <div class="user-status">
                        <span class="dot"></span>
                        <span>En línea</span>
                    </div>
                </div>
                <button onclick="window.location.href='/logout'" style="background: transparent; border: none; color: #6b7280; cursor: pointer; padding: 4px; border-radius: 4px; transition: all 0.2s ease;">
                    <i class="fas fa-sign-out-alt" style="font-size: 14px;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="custom-main-wrapper" id="customMainWrapper">
        <nav class="navbar navbar-expand-lg bg-white rounded-3 shadow-sm p-3 mb-4">
            <div class="container-fluid">
                <!-- BOTÓN TOGGLE MEJORADO -->
                <button class="custom-menu-toggle" id="customMenuToggleBtn" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <span class="toggle-icon"><i class="fas fa-bars"></i></span>
                    <span class="toggle-label" id="toggleLabel">Ocultar menú</span>
                </button>
                
                <div class="d-none d-md-block ms-3">
                    <small class="text-muted" id="customPageIndicator">
                        <i class="fas fa-arrow-right me-1"></i> 
                        <span id="customPageName">Dashboard</span>
                    </small>
                </div>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fas fa-calendar-alt me-1"></i> {{ date('d/m/Y') }}
                    </span>
                    
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill" id="connectionStatus">
                        <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> Conectado
                    </span>
                    
                    <div class="dropdown">
    <button class="btn btn-link text-decoration-none text-dark p-0" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="d-flex align-items-center gap-2">
            <!-- Avatar con iniciales del usuario -->
            <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                 style="width: 40px; height: 40px; font-weight: 600; font-size: 16px;">
                {{ Auth::user()->iniciales ?? strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="d-none d-md-block text-start">
                <div class="fw-semibold small" style="line-height: 1.2;">
                    {{ Auth::user()->nombre_completo ?? Auth::user()->name }}
                </div>
                <div class="text-muted small" style="font-size: 10px; line-height: 1.2;">
                    <i class="fas fa-circle text-success me-1" style="font-size: 6px;"></i>
                    {{ Auth::user()->rol ?? 'Usuario' }}
                </div>
            </div>
            <i class="fas fa-chevron-down text-muted ms-1" style="font-size: 10px;"></i>
        </div>
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2" style="min-width: 220px; margin-top: 8px;">
        <!-- Header del dropdown -->
        <li class="px-3 pb-2 mb-1 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 36px; height: 36px; font-weight: 600; font-size: 14px;">
                    {{ Auth::user()->iniciales ?? strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-semibold small">{{ Auth::user()->name }}</div>
                    <div class="text-muted small" style="font-size: 11px;">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </li>

        <!-- Perfil -->
        <li>
            <a class="dropdown-item py-2 px-3 rounded-2 mx-1" href="{{ route('profile.edit') }}" style="transition: all 0.2s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px;">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Mi Perfil</div>
                        <div class="text-muted small" style="font-size: 10px;">Ver y editar tu perfil</div>
                    </div>
                </div>
            </a>
        </li>

        <!-- Soporte -->
        <li>
            <a class="dropdown-item py-2 px-3 rounded-2 mx-1" href="#" onclick="openSupport()" style="transition: all 0.2s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px;">
                        <i class="fas fa-headset text-info"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Soporte</div>
                        <div class="text-muted small" style="font-size: 10px;">Ayuda y contacto</div>
                    </div>
                </div>
            </a>
        </li>

        <li><hr class="dropdown-divider my-2"></li>

        <!-- Cerrar Sesión -->
        <li>
            <a class="dropdown-item py-2 px-3 rounded-2 mx-1 text-danger" 
               href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               style="transition: all 0.2s ease;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px;">
                        <i class="fas fa-sign-out-alt text-danger"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Cerrar Sesión</div>
                        <div class="text-muted small" style="font-size: 10px;">Salir del sistema</div>
                    </div>
                </div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

        <!-- Footer del dropdown -->
        <li class="px-3 pt-2 mt-1 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted" style="font-size: 9px;">
                    <i class="fas fa-shield-alt me-1"></i>
                    Versión 1.0.0
                </small>
                <small class="text-muted" style="font-size: 9px;">
                    {{ now()->format('d/m/Y H:i') }}
                </small>
            </div>
        </li>
    </ul>
</div>
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- ===== TOAST MEJORADO ===== -->
    <div class="custom-toast-container">
        <div id="customToast" class="custom-toast" role="alert">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-check-circle text-success fs-5" id="customToastIcon"></i>
                <span id="customToastMessage" style="flex: 1; font-size: 14px;">Mensaje</span>
                <button onclick="this.closest('.custom-toast').classList.remove('show')" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #6b7280;">×</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ============================================================
        // 1. TOGGLE SIDEBAR
        // ============================================================
        let isSidebarVisible = true;

        function toggleSidebar() {
            const sidebar = document.getElementById('customSidebar');
            const mainWrapper = document.getElementById('customMainWrapper');
            const overlay = document.getElementById('customSidebarOverlay');
            const toggleLabel = document.getElementById('toggleLabel');
            const body = document.body;
            
            if (!sidebar || !mainWrapper) return;

            if (window.innerWidth >= 992) {
                isSidebarVisible = !isSidebarVisible;
                
                if (isSidebarVisible) {
                    sidebar.classList.remove('hidden');
                    mainWrapper.classList.remove('expanded');
                    if (toggleLabel) toggleLabel.textContent = 'Ocultar menú';
                } else {
                    sidebar.classList.add('hidden');
                    mainWrapper.classList.add('expanded');
                    if (toggleLabel) toggleLabel.textContent = 'Mostrar menú';
                }
                
                localStorage.setItem('sidebarVisible', isSidebarVisible);
            } else {
                const isOpen = sidebar.classList.contains('open');
                
                if (isOpen) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                    body.style.overflow = '';
                } else {
                    sidebar.classList.add('open');
                    overlay.classList.add('show');
                    body.classList.add('sidebar-open');
                    body.style.overflow = 'hidden';
                }
            }
        }

        // ============================================================
        // 2. INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('customSidebar');
            const mainWrapper = document.getElementById('customMainWrapper');
            const overlay = document.getElementById('customSidebarOverlay');
            const toggleLabel = document.getElementById('toggleLabel');
            
            if (window.innerWidth >= 992) {
                const savedState = localStorage.getItem('sidebarVisible');
                if (savedState === 'false') {
                    isSidebarVisible = false;
                    sidebar.classList.add('hidden');
                    mainWrapper.classList.add('expanded');
                    if (toggleLabel) toggleLabel.textContent = 'Mostrar menú';
                } else {
                    isSidebarVisible = true;
                    sidebar.classList.remove('hidden');
                    mainWrapper.classList.remove('expanded');
                    if (toggleLabel) toggleLabel.textContent = 'Ocultar menú';
                }
            } else {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                document.body.classList.remove('sidebar-open');
                document.body.style.overflow = '';
            }
            
            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (window.innerWidth < 992) {
                        toggleSidebar();
                    }
                });
            }
            
            favoriteManager.init();
            
            // Detectar conexión
            updateConnectionStatus();
            
            console.log('✅ Menú mejorado cargado correctamente');
        });

        // ============================================================
        // 3. ESTADO DE CONEXIÓN
        // ============================================================
        function updateConnectionStatus() {
            const statusEl = document.getElementById('connectionStatus');
            if (!statusEl) return;
            
            // Simular detección de conexión
            const isOnline = navigator.onLine;
            
            if (isOnline) {
                statusEl.className = 'badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill';
                statusEl.innerHTML = '<i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> Conectado';
            } else {
                statusEl.className = 'badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill';
                statusEl.innerHTML = '<i class="fas fa-circle text-danger me-1" style="font-size: 8px;"></i> Sin conexión';
            }
        }

        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);

        // ============================================================
        // 4. RESPONSIVE
        // ============================================================
        let customResizeTimeout;

        window.addEventListener('resize', function() {
            clearTimeout(customResizeTimeout);
            customResizeTimeout = setTimeout(function() {
                const sidebar = document.getElementById('customSidebar');
                const mainWrapper = document.getElementById('customMainWrapper');
                const overlay = document.getElementById('customSidebarOverlay');
                const toggleLabel = document.getElementById('toggleLabel');
                const body = document.body;
                
                if (window.innerWidth >= 992) {
                    overlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                    body.style.overflow = '';
                    
                    const savedState = localStorage.getItem('sidebarVisible');
                    if (savedState === 'false') {
                        isSidebarVisible = false;
                        sidebar.classList.add('hidden');
                        mainWrapper.classList.add('expanded');
                        if (toggleLabel) toggleLabel.textContent = 'Mostrar menú';
                    } else {
                        isSidebarVisible = true;
                        sidebar.classList.remove('hidden');
                        mainWrapper.classList.remove('expanded');
                        if (toggleLabel) toggleLabel.textContent = 'Ocultar menú';
                    }
                } else {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                    body.style.overflow = '';
                }
            }, 200);
        });

        // ============================================================
        // 5. ACCORDION
        // ============================================================
        function toggleAccordion(menuId) {
            const content = document.getElementById(menuId);
            const icon = document.getElementById(menuId + 'Icon');
            
            if (!content) return;
            
            const isOpen = content.style.display !== 'none';
            content.style.display = isOpen ? 'none' : 'block';
            
            if (icon) {
                icon.classList.toggle('rotated');
            }
        }

        // ============================================================
        // 6. BÚSQUEDA
        // ============================================================
        function searchMenu(query) {
            const resultsContainer = document.getElementById('customSearchResults');
            if (!resultsContainer) return;
            
            const trimmedQuery = query.trim().toLowerCase();
            
            if (trimmedQuery.length === 0) {
                resultsContainer.style.display = 'none';
                return;
            }
            
            const menuItems = document.querySelectorAll('.custom-menu-item');
            const results = [];
            
            menuItems.forEach(item => {
                const text = item.textContent.trim().toLowerCase();
                if (text.includes(trimmedQuery)) {
                    results.push(item);
                }
            });
            
            if (results.length === 0) {
                resultsContainer.innerHTML = `
                    <div style="padding: 16px; text-align: center; color: #6b7280; font-size: 13px;">
                        <i class="fas fa-search mb-2 d-block" style="font-size: 20px;"></i>
                        No se encontraron resultados para "<strong>${query}</strong>"
                    </div>
                `;
                resultsContainer.style.display = 'block';
                return;
            }
            
            let html = '';
            results.forEach(item => {
                const clone = item.cloneNode(true);
                const text = clone.textContent.trim();
                const highlightedText = text.replace(
                    new RegExp(`(${trimmedQuery})`, 'gi'),
                    '<span class="custom-search-match">$1</span>'
                );
                clone.innerHTML = clone.innerHTML.replace(text, highlightedText);
                html += `
                    <div class="custom-search-result" onclick="navigateToMenuItem(this)" data-target="${item.getAttribute('data-menu-item') || ''}" 
                         style="padding: 10px 14px; cursor: pointer; transition: background 0.2s ease; border-bottom: 1px solid #f3f4f6;">
                        ${clone.innerHTML}
                    </div>
                `;
            });
            
            resultsContainer.innerHTML = html;
            resultsContainer.style.display = 'block';
        }

        function navigateToMenuItem(element) {
            const target = element.getAttribute('data-target');
            if (!target) return;
            
            const menuItem = document.querySelector(`[data-menu-item="${target}"]`);
            if (menuItem) {
                const parentContent = menuItem.closest('[id$="Menu"]');
                if (parentContent && parentContent.style.display === 'none') {
                    const parentId = parentContent.id;
                    if (parentId) toggleAccordion(parentId);
                }
                
                menuItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                setTimeout(() => {
                    if (menuItem.tagName === 'A' || menuItem.tagName === 'BUTTON') {
                        menuItem.click();
                    }
                }, 300);
            }
            
            document.getElementById('customSearchResults').style.display = 'none';
            document.getElementById('customMenuSearch').value = '';
        }

        // ============================================================
        // 7. FAVORITOS
        // ============================================================
        const favoriteManager = {
            favorites: [],
            editMode: false,

            init() {
                this.loadFavorites();
                this.renderFavorites();
            },

            loadFavorites() {
                try {
                    const stored = localStorage.getItem('customMenuFavorites');
                    this.favorites = stored ? JSON.parse(stored) : [];
                } catch (e) {
                    this.favorites = [];
                }
            },

            saveFavorites() {
                try {
                    localStorage.setItem('customMenuFavorites', JSON.stringify(this.favorites));
                } catch (e) {
                    console.error('Error guardando favoritos:', e);
                }
                this.renderFavorites();
            },

            addFavorite(name, icon, url) {
                if (this.favorites.some(f => f.url === url)) {
                    showToast('Ya está en favoritos', 'info');
                    return false;
                }
                
                this.favorites.push({ name, icon, url });
                this.saveFavorites();
                showToast(`⭐ "${name}" agregado a favoritos`, 'success');
                return true;
            },

            removeFavorite(url) {
                this.favorites = this.favorites.filter(f => f.url !== url);
                this.saveFavorites();
                showToast('Favorito eliminado', 'info');
            },

            toggleEdit() {
                this.editMode = !this.editMode;
                const icon = document.getElementById('customFavoritesEditIcon');
                if (icon) {
                    icon.style.color = this.editMode ? '#2563eb' : '#6b7280';
                }
                this.renderFavorites();
            },

            renderFavorites() {
                const container = document.getElementById('customFavoritesContainer');
                if (!container) return;
                
                if (this.favorites.length === 0) {
                    container.innerHTML = `
                        <small style="color: #9ca3af; font-size: 12px;">No hay favoritos aún</small>
                        <span class="badge bg-light text-muted" style="font-size: 10px; font-weight: 400;">⭐ Haz clic en la estrella</span>
                    `;
                    return;
                }
                
                let html = '';
                this.favorites.forEach(fav => {
                    html += `
                        <span class="custom-favorite-item">
                            <a href="${fav.url}">
                                <i class="${fav.icon}"></i>
                                <span>${fav.name}</span>
                            </a>
                            ${this.editMode ? `
                                <button class="remove-fav" onclick="favoriteManager.removeFavorite('${fav.url}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            ` : ''}
                        </span>
                    `;
                });
                
                container.innerHTML = html;
            }
        };

        function addToFavorites(starElement, name, icon, url) {
            if (!starElement) return;
            
            const isFavorite = favoriteManager.favorites.some(f => f.url === url);
            
            if (isFavorite) {
                favoriteManager.removeFavorite(url);
                starElement.classList.remove('active');
                starElement.style.color = '#9ca3af';
            } else {
                const success = favoriteManager.addFavorite(name, icon, url);
                if (success) {
                    starElement.classList.add('active');
                    starElement.style.color = '#fbbf24';
                }
            }
        }

        // ============================================================
        // 8. TOAST MEJORADO
        // ============================================================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('customToast');
            const toastMessage = document.getElementById('customToastMessage');
            const toastIcon = document.getElementById('customToastIcon');
            
            if (!toast || !toastMessage || !toastIcon) return;
            
            const configs = {
                success: { icon: 'fa-check-circle', color: 'text-success' },
                error: { icon: 'fa-exclamation-circle', color: 'text-danger' },
                info: { icon: 'fa-info-circle', color: 'text-info' },
                warning: { icon: 'fa-exclamation-triangle', color: 'text-warning' }
            };
            
            const config = configs[type] || configs.success;
            
            toast.className = `custom-toast ${type}`;
            toastIcon.className = `fas ${config.icon} ${config.color} fs-5`;
            toastMessage.textContent = message;
            
            toast.classList.add('show');
            
            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }

        // ============================================================
        // 9. MODO OSCURO
        // ============================================================
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.getElementById('customDarkModeIcon');
            if (icon) {
                icon.classList.toggle('fa-moon');
                icon.classList.toggle('fa-sun');
            }
            localStorage.setItem('customDarkMode', document.body.classList.contains('dark-mode'));
            showToast(document.body.classList.contains('dark-mode') ? '🌙 Modo oscuro activado' : '☀️ Modo claro activado', 'info');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('customDarkMode') === 'true') {
                document.body.classList.add('dark-mode');
                const icon = document.getElementById('customDarkModeIcon');
                if (icon) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            }
        });

        // ============================================================
        // 10. FUNCIONES DE UTILIDAD
        // ============================================================
        function toggleNotifications() {
            showToast('🔔 No hay notificaciones nuevas', 'info');
        }

        function openModal(modalId) {
            showToast('📋 Abriendo: ' + modalId, 'info');
        }

        function cargarFacturas() {
            showToast('📋 Cargando facturas...', 'info');
        }

        function cargarTodosLosDatos() {
            showToast('🔄 Actualizando datos...', 'info');
        }

        function showMenuShortcutsHelp() {
            showToast('⌨️ Ctrl+Shift+M: Abrir/Cerrar | Ctrl+/: Buscar | ESC: Cerrar', 'info');
        }

        // ============================================================
        // 11. ATAJOS DE TECLADO
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'M') {
                e.preventDefault();
                toggleSidebar();
            }
            
            if (e.key === 'Escape' && window.innerWidth < 992) {
                const sidebar = document.getElementById('customSidebar');
                if (sidebar && sidebar.classList.contains('open')) {
                    toggleSidebar();
                }
            }
            
            if (e.ctrlKey && e.key === '/') {
                e.preventDefault();
                const searchInput = document.getElementById('customMenuSearch');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
        });

        console.log('✅ Menú mejorado cargado correctamente');
        console.log('💡 Atajos: Ctrl+Shift+M (menú) | Ctrl+/ (búsqueda)');
    </script>

    @stack('scripts')
</body>
</html>