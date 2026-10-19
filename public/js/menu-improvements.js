/**
 * ============================================
 * MENU IMPROVEMENTS - JAVASCRIPT COMPLETO
 * ============================================
 */

// ============================================
// 1. SISTEMA DE ACORDEÓN CON MEMORIA
// ============================================

function toggleAccordion(menuId) {
    const menu = document.getElementById(menuId);
    const icon = document.getElementById(menuId + 'Icon');
    
    if (!menu) return;
    
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
        if (icon) icon.className = 'fas fa-chevron-up transition-smooth';
        saveMenuState(menuId, true);
    } else {
        menu.style.display = 'none';
        if (icon) icon.className = 'fas fa-chevron-down transition-smooth';
        saveMenuState(menuId, false);
    }
}

function saveMenuState(menuId, isOpen) {
    try {
        let states = JSON.parse(localStorage.getItem('menuStates') || '{}');
        states[menuId] = isOpen;
        localStorage.setItem('menuStates', JSON.stringify(states));
    } catch (e) {
        console.warn('Error saving menu state:', e);
    }
}

function loadMenuStates() {
    try {
        const states = JSON.parse(localStorage.getItem('menuStates') || '{}');
        Object.keys(states).forEach(menuId => {
            if (states[menuId]) {
                const menu = document.getElementById(menuId);
                const icon = document.getElementById(menuId + 'Icon');
                if (menu) {
                    menu.style.display = 'block';
                    if (icon) icon.className = 'fas fa-chevron-up transition-smooth';
                }
            }
        });
    } catch (e) {
        console.warn('Error loading menu states:', e);
    }
}

// ============================================
// 2. SISTEMA DE FAVORITOS
// ============================================

class FavoriteManager {
    constructor() {
        this.favorites = [];
        this.editing = false;
        this.load();
        this.render();
    }
    
    load() {
        try {
            this.favorites = JSON.parse(localStorage.getItem('menuFavorites') || '[]');
        } catch (e) {
            this.favorites = [];
        }
    }
    
    save() {
        try {
            localStorage.setItem('menuFavorites', JSON.stringify(this.favorites));
        } catch (e) {
            console.warn('Error saving favorites:', e);
        }
    }
    
    add(item) {
        if (!this.favorites.find(f => f.id === item.id)) {
            this.favorites.push({
                id: item.id || Date.now().toString(),
                label: item.label || 'Item',
                icon: item.icon || 'fas fa-link',
                url: item.url || '#'
            });
            this.save();
            this.render();
            showToast('Añadido a favoritos ✅', 'success');
            return true;
        }
        showToast('Ya está en favoritos', 'info');
        return false;
    }
    
    remove(itemId) {
        this.favorites = this.favorites.filter(f => f.id !== itemId);
        this.save();
        this.render();
        showToast('Eliminado de favoritos', 'info');
    }
    
    toggleEdit() {
        this.editing = !this.editing;
        const icon = document.getElementById('favoritesEditIcon');
        if (icon) {
            icon.className = this.editing ? 'fas fa-check text-success' : 'fas fa-edit';
        }
        this.render();
    }
    
    render() {
        const container = document.getElementById('favoritesContainer');
        if (!container) return;
        
        if (this.favorites.length === 0) {
            container.innerHTML = '<small class="text-muted">Sin favoritos. ★ Agrega desde el menú</small>';
            return;
        }
        
        container.innerHTML = this.favorites.map(f => `
            <span class="badge bg-primary d-flex align-items-center gap-1 p-2" 
                  style="cursor: pointer;" 
                  onclick="window.location.href='${f.url}'">
                <i class="${f.icon}"></i>
                ${f.label}
                ${this.editing ? `<i class="fas fa-times ms-1 text-danger" style="font-size: 10px;" onclick="event.stopPropagation(); favoriteManager.remove('${f.id}')"></i>` : ''}
            </span>
        `).join('');
    }
}

// Instancia global de favoritos
const favoriteManager = new FavoriteManager();

// Función para agregar desde el menú
function addToFavorites(element, label, icon, url) {
    const id = url + '_' + Date.now();
    const item = {
        id: id,
        label: label,
        icon: icon,
        url: url
    };
    favoriteManager.add(item);
    
    // Feedback visual
    const star = element;
    star.className = 'fas fa-star text-warning';
    setTimeout(() => {
        star.className = 'fas fa-star text-muted';
    }, 1000);
}

// ============================================
// 3. BÚSQUEDA EN EL MENÚ
// ============================================

let searchTimeout = null;

function searchMenu(query) {
    const resultsContainer = document.getElementById('menuSearchResults');
    
    if (!resultsContainer) return;
    
    if (query.length < 2) {
        resultsContainer.classList.add('d-none');
        return;
    }
    
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        performSearch(query);
    }, 200);
}

function performSearch(query) {
    const resultsContainer = document.getElementById('menuSearchResults');
    if (!resultsContainer) return;
    
    const items = document.querySelectorAll('.sidebar .menu-item:not(.submenu-item)');
    const results = [];
    const searchTerm = query.toLowerCase().trim();
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        const icon = item.querySelector('i')?.className || 'fas fa-link';
        const onclick = item.getAttribute('onclick') || '';
        const href = item.getAttribute('href') || '';
        
        if (text.includes(searchTerm)) {
            const url = onclick.match(/['"](.*?)['"]/)?.[1] || href || '#';
            results.push({
                element: item,
                text: text.trim().replace(/\s+/g, ' '),
                icon: icon,
                url: url
            });
        }
    });
    
    if (results.length === 0) {
        resultsContainer.innerHTML = `
            <div class="p-3 text-center text-muted">
                <i class="fas fa-search mb-2 d-block"></i>
                No hay resultados para "<strong>${query}</strong>"
            </div>
        `;
    } else {
        resultsContainer.innerHTML = results.slice(0, 10).map(r => `
            <div class="p-2" onclick="navigateTo('${r.url}')">
                <i class="${r.icon} me-2" style="width: 20px;"></i>
                ${highlightMatch(r.text, query)}
            </div>
        `).join('');
    }
    
    resultsContainer.classList.remove('d-none');
}

function highlightMatch(text, query) {
    const index = text.toLowerCase().indexOf(query.toLowerCase());
    if (index === -1) return text;
    return text.substring(0, index) + 
           '<strong class="text-primary">' + 
           text.substring(index, index + query.length) + 
           '</strong>' + 
           text.substring(index + query.length);
}

function navigateTo(url) {
    if (url && url !== '#') {
        window.location.href = url;
    }
    document.getElementById('menuSearch').value = '';
    document.getElementById('menuSearchResults').classList.add('d-none');
}

// Cerrar resultados al hacer clic fuera
document.addEventListener('click', function(e) {
    const search = document.getElementById('menuSearch');
    const results = document.getElementById('menuSearchResults');
    if (search && results) {
        if (!e.target.closest('#menuSearch') && !e.target.closest('#menuSearchResults')) {
            results.classList.add('d-none');
        }
    }
});

// ============================================
// 4. TOGGLE SIDEBAR
// ============================================

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const mainWrapper = document.getElementById('mainWrapper');
    
    if (!sidebar) return;
    
    if (window.innerWidth < 768) {
        const isOpen = sidebar.classList.contains('sidebar-open');
        if (isOpen) {
            sidebar.classList.remove('sidebar-open');
            sidebar.style.transform = 'translateX(-100%)';
            if (overlay) overlay.classList.remove('active');
        } else {
            sidebar.classList.add('sidebar-open');
            sidebar.style.transform = 'translateX(0)';
            if (overlay) overlay.classList.add('active');
        }
    } else {
        sidebar.classList.toggle('collapsed');
        if (mainWrapper) {
            mainWrapper.classList.toggle('expanded');
        }
    }
}

// ============================================
// 5. NOTIFICACIONES
// ============================================

let notificationTimeout = null;

function toggleNotifications() {
    const existing = document.querySelector('.notification-popup');
    if (existing) {
        existing.remove();
        return;
    }
    
    notificationSystem.showPopup();
}

class NotificationSystem {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.pollingInterval = null;
        this.load();
        this.startPolling();
    }
    
    async load() {
        try {
            // Simular carga de notificaciones - Reemplazar con API real
            const response = await fetch('/api/notifications').catch(() => null);
            if (response && response.ok) {
                const data = await response.json();
                this.notifications = data;
            } else {
                // Datos de ejemplo
                this.notifications = [
                    { id: 1, title: 'Nueva factura', message: 'Factura #001 creada', icon: 'fas fa-file-invoice', color: 'primary', read: false, created_at: new Date().toISOString() },
                    { id: 2, title: 'Stock bajo', message: 'Producto "Laptop" tiene menos de 5 unidades', icon: 'fas fa-exclamation-triangle', color: 'warning', read: false, created_at: new Date(Date.now() - 3600000).toISOString() }
                ];
            }
            this.updateBadge();
        } catch (error) {
            console.warn('Error loading notifications:', error);
        }
    }
    
    startPolling() {
        if (this.pollingInterval) clearInterval(this.pollingInterval);
        this.pollingInterval = setInterval(() => this.load(), 30000);
    }
    
    updateBadge() {
        const unread = this.notifications.filter(n => !n.read).length;
        const badge = document.getElementById('menuNotificationBadge');
        if (badge) {
            badge.textContent = unread;
            badge.style.display = unread > 0 ? 'inline-block' : 'none';
        }
    }
    
    showPopup() {
        const container = document.createElement('div');
        container.className = 'notification-popup position-absolute bg-white shadow-lg rounded-3 p-2';
        container.style.cssText = `
            right: 0;
            top: 100%;
            width: 320px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            margin-top: 8px;
        `;
        
        const header = document.createElement('div');
        header.className = 'd-flex justify-content-between align-items-center p-2 border-bottom';
        header.innerHTML = `
            <strong class="small">Notificaciones</strong>
            <button class="btn btn-sm btn-link text-decoration-none" onclick="this.closest('.notification-popup').remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(header);
        
        if (this.notifications.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'text-center text-muted p-3';
            empty.innerHTML = '<i class="fas fa-bell-slash mb-2 d-block"></i>No hay notificaciones';
            container.appendChild(empty);
        } else {
            this.notifications.slice(0, 10).forEach(n => {
                const item = document.createElement('div');
                item.className = `p-2 ${n.read ? 'opacity-75' : 'bg-light'}`;
                item.style.cursor = 'pointer';
                item.innerHTML = `
                    <div class="d-flex align-items-start gap-2">
                        <i class="${n.icon || 'fas fa-info-circle'} text-${n.color || 'primary'} mt-1"></i>
                        <div>
                            <div class="fw-bold small">${n.title}</div>
                            <div class="small">${n.message}</div>
                            <small class="text-muted">${timeAgo(n.created_at)}</small>
                        </div>
                    </div>
                `;
                item.onclick = () => markNotificationRead(n.id);
                container.appendChild(item);
            });
        }
        
        // Agregar al DOM
        const parent = document.querySelector('.position-relative');
        if (parent) {
            const existing = parent.querySelector('.notification-popup');
            if (existing) existing.remove();
            parent.appendChild(container);
        }
    }
}

function timeAgo(date) {
    const diff = Date.now() - new Date(date).getTime();
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    
    if (days > 0) return `hace ${days} día${days > 1 ? 's' : ''}`;
    if (hours > 0) return `hace ${hours} hora${hours > 1 ? 's' : ''}`;
    if (minutes > 0) return `hace ${minutes} minuto${minutes > 1 ? 's' : ''}`;
    return 'hace un momento';
}

async function markNotificationRead(id) {
    try {
        await fetch(`/api/notifications/${id}/read`, { method: 'POST' });
        notificationSystem.notifications = notificationSystem.notifications.map(n => 
            n.id === id ? { ...n, read: true } : n
        );
        notificationSystem.updateBadge();
        // Cerrar popup
        document.querySelector('.notification-popup')?.remove();
    } catch (error) {
        console.warn('Error marking notification as read:', error);
    }
}

const notificationSystem = new NotificationSystem();

// ============================================
// 6. MENÚ CONTEXTUAL (CLICK DERECHO)
// ============================================

document.addEventListener('contextmenu', function(e) {
    const target = e.target.closest('.menu-item');
    if (target) {
        e.preventDefault();
        showContextMenu(e.clientX, e.clientY, target);
    }
});

function showContextMenu(x, y, target) {
    const existing = document.querySelector('.custom-context-menu');
    if (existing) existing.remove();
    
    const menu = document.createElement('div');
    menu.className = 'custom-context-menu position-fixed bg-white shadow-lg rounded-3 p-2';
    menu.style.cssText = `
        left: ${Math.min(x, window.innerWidth - 220)}px;
        top: ${Math.min(y, window.innerHeight - 200)}px;
        min-width: 200px;
        z-index: 9999;
        border: 1px solid #e2e8f0;
    `;
    
    const text = target.textContent.trim();
    const onclick = target.getAttribute('onclick') || '';
    const href = target.getAttribute('href') || '';
    const url = onclick.match(/['"](.*?)['"]/)?.[1] || href || '#';
    const icon = target.querySelector('i')?.className || 'fas fa-link';
    
    menu.innerHTML = `
        <div onclick="navigateTo('${url}')">
            <i class="fas fa-external-link-alt me-2"></i> Abrir
        </div>
        <div onclick="window.open('${url}', '_blank')">
            <i class="fas fa-window-restore me-2"></i> Abrir en nueva pestaña
        </div>
        <div onclick="copyToClipboard('${window.location.origin}${url}')">
            <i class="fas fa-copy me-2"></i> Copiar enlace
        </div>
        <hr>
        <div onclick="favoriteManager.add({id: '${Date.now()}', label: '${text.substring(0, 30)}', icon: '${icon}', url: '${url}'})">
            <i class="fas fa-star me-2"></i> Agregar a favoritos
        </div>
        <div onclick="console.log('Analytics: ', '${text}')">
            <i class="fas fa-chart-bar me-2"></i> Ver estadísticas
        </div>
    `;
    
    document.body.appendChild(menu);
    
    // Cerrar al hacer clic fuera
    setTimeout(() => {
        document.addEventListener('click', function closeMenu(e) {
            if (!e.target.closest('.custom-context-menu')) {
                menu.remove();
                document.removeEventListener('click', closeMenu);
            }
        });
    }, 10);
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text)
            .then(() => showToast('Enlace copiado 📋', 'success'))
            .catch(() => {});
    } else {
        // Fallback
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        textarea.remove();
        showToast('Enlace copiado 📋', 'success');
    }
}

// ============================================
// 7. RESALTADO DE PÁGINA ACTUAL
// ============================================

function highlightCurrentPage() {
    const currentPath = window.location.pathname;
    
    document.querySelectorAll('.sidebar .menu-item').forEach(item => {
        const onclickAttr = item.getAttribute('onclick');
        const hrefAttr = item.getAttribute('href');
        
        let itemUrl = '';
        if (onclickAttr) {
            const match = onclickAttr.match(/['"](.*?)['"]/);
            if (match) itemUrl = match[1];
        } else if (hrefAttr) {
            itemUrl = hrefAttr;
        }
        
        if (itemUrl && currentPath.includes(itemUrl) && itemUrl !== '#') {
            item.classList.add('active');
            
            // Expandir el acordeón padre
            const parentAccordion = item.closest('div[id$="Menu"]');
            if (parentAccordion && parentAccordion.style.display === 'none') {
                const parentId = parentAccordion.id;
                const parentButton = document.querySelector(`[onclick*="${parentId}"]`);
                if (parentButton) {
                    parentButton.click();
                }
            }
        }
    });
}

// ============================================
// 8. BADGES DINÁMICOS
// ============================================

function updateMenuBadges() {
    // Facturas pendientes
    fetch('/api/invoices/pending-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('pendingInvoicesBadge');
            if (badge) {
                badge.textContent = data.count || 0;
                badge.style.display = (data.count || 0) > 0 ? 'inline-block' : 'none';
            }
        })
        .catch(() => {
            // Fallback - datos de ejemplo
            const badge = document.getElementById('pendingInvoicesBadge');
            if (badge) {
                badge.textContent = '3';
                badge.style.display = 'inline-block';
            }
        });
    
    // Stock bajo
    fetch('/api/inventory/low-stock-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('lowStockBadge');
            if (badge) {
                badge.textContent = data.count || 0;
                badge.style.display = (data.count || 0) > 0 ? 'inline-block' : 'none';
            }
        })
        .catch(() => {
            const badge = document.getElementById('lowStockBadge');
            if (badge) {
                badge.textContent = '5';
                badge.style.display = 'inline-block';
            }
        });
}

// ============================================
// 9. SISTEMA DE TOAST
// ============================================

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    if (!toast || !toastMessage) return;
    
    const icons = {
        success: 'fas fa-check-circle text-success',
        error: 'fas fa-times-circle text-danger',
        warning: 'fas fa-exclamation-circle text-warning',
        info: 'fas fa-info-circle text-info'
    };
    
    toastIcon.className = icons[type] || icons.success;
    toastMessage.textContent = message;
    toast.className = `toast-custom bg-white border-0 shadow-lg ${type}`;
    
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 3000
    });
    bsToast.show();
}

// ============================================
// 10. MODO OSCURO
// ============================================

function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const icon = document.getElementById('darkModeIcon');
    const isDark = document.body.classList.contains('dark-mode');
    if (icon) {
        icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
    }
    try {
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    } catch (e) {}
}

function loadTheme() {
    try {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            const icon = document.getElementById('darkModeIcon');
            if (icon) icon.className = 'fas fa-sun';
        }
    } catch (e) {}
}

// ============================================
// 11. ESTADO DE CONEXIÓN
// ============================================

function updateConnectionStatus() {
    const status = document.getElementById('connectionStatus');
    if (!status) return;
    
    if (navigator.onLine) {
        status.innerHTML = '<i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> Conectado';
        status.className = 'badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill';
        status.style.display = 'inline-block';
    } else {
        status.innerHTML = '<i class="fas fa-circle text-danger me-1" style="font-size: 8px;"></i> Sin conexión';
        status.className = 'badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill';
        status.style.display = 'inline-block';
    }
}

// ============================================
// 12. ATEJOS DE TECLADO
// ============================================

document.addEventListener('keydown', function(e) {
    // Ctrl+Shift+M - Enfocar búsqueda
    if (e.ctrlKey && e.shiftKey && e.key === 'M') {
        e.preventDefault();
        document.getElementById('menuSearch')?.focus();
    }
    
    // Ctrl+Shift+D - Dashboard
    if (e.ctrlKey && e.shiftKey && e.key === 'D') {
        e.preventDefault();
        window.location.href = '/dashboard';
    }
    
    // Ctrl+Shift+I - Inventario
    if (e.ctrlKey && e.shiftKey && e.key === 'I') {
        e.preventDefault();
        document.querySelector('[onclick*="inventarioMenu"]')?.click();
    }
    
    // Ctrl+Shift+F - Facturas
    if (e.ctrlKey && e.shiftKey && e.key === 'F') {
        e.preventDefault();
        document.querySelector('[onclick*="facturasMenu"]')?.click();
    }
    
    // Ctrl+Shift+P - Proveedores
    if (e.ctrlKey && e.shiftKey && e.key === 'P') {
        e.preventDefault();
        document.querySelector('[onclick*="proveedoresMenu"]')?.click();
    }
    
    // Ctrl+Shift+C - Configuración
    if (e.ctrlKey && e.shiftKey && e.key === 'C') {
        e.preventDefault();
        document.querySelector('[onclick*="configuracionMenu"]')?.click();
    }
    
    // Ctrl+Shift+O - Modo oscuro
    if (e.ctrlKey && e.shiftKey && e.key === 'O') {
        e.preventDefault();
        toggleDarkMode();
    }
    
    // Escape - Cerrar modales y popups
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.show').forEach(modal => {
            const instance = bootstrap.Modal.getInstance(modal);
            if (instance) instance.hide();
        });
        document.querySelectorAll('.notification-popup, .custom-context-menu').forEach(el => el.remove());
        document.getElementById('menuSearchResults')?.classList.add('d-none');
    }
    
    // Navegación con teclas de dirección en el menú
    if (document.activeElement?.closest('.sidebar')) {
        const items = document.querySelectorAll('.sidebar .menu-item:not([style*="display: none"])');
        let currentIndex = Array.from(items).indexOf(document.activeElement);
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextIndex = Math.min(currentIndex + 1, items.length - 1);
            items[nextIndex]?.focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prevIndex = Math.max(currentIndex - 1, 0);
            items[prevIndex]?.focus();
        } else if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            document.activeElement?.click();
        }
    }
});

// Hacer los items del menú focusables
document.querySelectorAll('.sidebar .menu-item').forEach(item => {
    if (!item.getAttribute('tabindex')) {
        item.setAttribute('tabindex', '0');
    }
});

// ============================================
// 13. AYUDA DE ATEJOS
// ============================================

function showMenuShortcutsHelp() {
    const shortcuts = [
        { key: 'Ctrl+Shift+D', action: 'Ir al Dashboard' },
        { key: 'Ctrl+Shift+I', action: 'Abrir Inventario' },
        { key: 'Ctrl+Shift+F', action: 'Abrir Facturas' },
        { key: 'Ctrl+Shift+P', action: 'Abrir Proveedores' },
        { key: 'Ctrl+Shift+C', action: 'Abrir Configuración' },
        { key: 'Ctrl+Shift+M', action: 'Buscar en el menú' },
        { key: 'Ctrl+Shift+O', action: 'Alternar Modo Oscuro' },
        { key: 'Esc', action: 'Cerrar modales y popups' },
        { key: '↑/↓', action: 'Navegar en el menú' },
        { key: 'Enter', action: 'Activar elemento seleccionado' }
    ];
    
    const modalHtml = `
        <div class="modal fade" id="shortcutsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-keyboard me-2"></i> Atajos de Teclado
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
                            ${shortcuts.map(s => `
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <kbd class="bg-light p-2 rounded">${s.key}</kbd>
                                    <span>${s.action}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal existente
    const existing = document.getElementById('shortcutsModal');
    if (existing) existing.remove();
    
    // Crear y mostrar modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('shortcutsModal'));
    modal.show();
    
    // Limpiar al cerrar
    document.getElementById('shortcutsModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// ============================================
// 14. RESPONSIVE
// ============================================

function handleMenuResponsive() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const mainWrapper = document.getElementById('mainWrapper');
    
    if (!sidebar) return;
    
    if (window.innerWidth < 768) {
        sidebar.classList.add('sidebar-mobile');
        if (!sidebar.classList.contains('sidebar-open')) {
            sidebar.style.transform = 'translateX(-100%)';
        }
        if (overlay) overlay.style.display = 'block';
        if (mainWrapper) mainWrapper.classList.add('expanded');
    } else {
        sidebar.classList.remove('sidebar-mobile');
        sidebar.style.transform = 'translateX(0)';
        if (overlay) overlay.style.display = 'none';
        if (mainWrapper) mainWrapper.classList.remove('expanded');
        
        // Si no está colapsado, asegurar que se vea
        if (!sidebar.classList.contains('collapsed')) {
            sidebar.style.transform = 'translateX(0)';
        }
    }
}

// ============================================
// 15. ANALYTICS DE MENÚ
// ============================================

class MenuAnalytics {
    constructor() {
        this.trackClicks();
    }
    
    trackClicks() {
        document.querySelectorAll('.sidebar .menu-item').forEach(item => {
            item.addEventListener('click', function() {
                const menuPath = this.closest('div[id$="Menu"]')?.id || 'dashboard';
                const itemText = this.textContent.trim();
                const url = window.location.href;
                
                // Guardar en localStorage
                try {
                    let history = JSON.parse(localStorage.getItem('menuClickHistory') || '[]');
                    history.push({
                        path: menuPath,
                        item: itemText.substring(0, 50),
                        timestamp: new Date().toISOString(),
                        url: url
                    });
                    if (history.length > 100) history.shift();
                    localStorage.setItem('menuClickHistory', JSON.stringify(history));
                } catch (e) {}
            });
        });
    }
    
    getStatistics() {
        try {
            const history = JSON.parse(localStorage.getItem('menuClickHistory') || '[]');
            const stats = {};
            history.forEach(h => {
                stats[h.path] = (stats[h.path] || 0) + 1;
            });
            return stats;
        } catch (e) {
            return {};
        }
    }
}

const menuAnalytics = new MenuAnalytics();

// ============================================
// 16. INDICADOR DE PÁGINA ACTUAL
// ============================================

function updatePageIndicator() {
    const indicator = document.getElementById('currentPageName');
    if (!indicator) return;
    
    const active = document.querySelector('.sidebar .menu-item.active');
    if (active) {
        const text = active.textContent.trim();
        indicator.textContent = text.substring(0, 30);
    } else {
        indicator.textContent = 'Dashboard';
    }
}

// ============================================
// 17. INICIALIZACIÓN
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Cargar estado del menú
    loadMenuStates();
    
    // Resaltar página actual
    highlightCurrentPage();
    
    // Actualizar badges
    updateMenuBadges();
    setInterval(updateMenuBadges, 30000);
    
    // Cargar tema
    loadTheme();
    
    // Estado de conexión
    updateConnectionStatus();
    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);
    
    // Responsive
    handleMenuResponsive();
    window.addEventListener('resize', handleMenuResponsive);
    
    // Indicador de página
    updatePageIndicator();
    
    // Cerrar sidebar al hacer clic en overlay
    document.getElementById('sidebarOverlay')?.addEventListener('click', toggleSidebar);
    
    console.log('✅ Menú mejorado cargado correctamente');
    console.log('📊 Estadísticas de uso:', menuAnalytics.getStatistics());
});

// ============================================
// EXPORTAR FUNCIONES GLOBALES
// ============================================

window.toggleAccordion = toggleAccordion;
window.toggleSidebar = toggleSidebar;
window.searchMenu = searchMenu;
window.addToFavorites = addToFavorites;
window.favoriteManager = favoriteManager;
window.toggleDarkMode = toggleDarkMode;
window.showToast = showToast;
window.showMenuShortcutsHelp = showMenuShortcutsHelp;
window.toggleNotifications = toggleNotifications;
window.navigateTo = navigateTo;
window.copyToClipboard = copyToClipboard;