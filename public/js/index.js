 const toastEl = document.getElementById('toast');
        const toast = new bootstrap.Toast(toastEl, { 
            delay: 3000,
            autohide: true
        });

        // Función para mostrar toast mejorada
        function mostrarToast(message, type = 'success') {
            const toastIcon = document.getElementById('toastIcon');
            const toastMessage = document.getElementById('toastMessage');
            
            // Configurar según tipo
            const configs = {
                success: {
                    icon: 'fa-check-circle',
                    color: 'text-success'
                },
                error: {
                    icon: 'fa-exclamation-circle',
                    color: 'text-danger'
                },
                warning: {
                    icon: 'fa-exclamation-triangle',
                    color: 'text-warning'
                },
                info: {
                    icon: 'fa-info-circle',
                    color: 'text-info'
                }
            };

            const config = configs[type] || configs.success;
            
            toastIcon.className = `fas ${config.icon} ${config.color} fs-5`;
            toastMessage.textContent = message;
            
            toast.show();
        }

        // Función toggleSidebar mejorada
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const overlay = document.getElementById('sidebarOverlay');

            if (window.innerWidth > 992) {
                // Desktop
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');
            } else {
                // Mobile
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('mobile-open') ? 'hidden' : '';
            }
        }

        // Función para toggle de acordeón
        // Función para toggle de acordeón - Versión mejorada
function toggleAccordion(menuId) {
    const menu = document.getElementById(menuId);
    const icon = document.getElementById(menuId + 'Icon');
    
    if (!menu) {
        console.error('Menú no encontrado:', menuId);
        return;
    }
    
    // Verificar si el menú está visible
    const isVisible = menu.style.display !== 'none';
    console.log('📌 Menú:', menuId, 'Visible:', isVisible);
    
    // Cerrar todos los acordeones abiertos
    document.querySelectorAll('[id$="Menu"]').forEach(el => {
        if (el.id !== menuId) {
            el.style.display = 'none';  // Ocultar
            const otherIcon = document.getElementById(el.id + 'Icon');
            if (otherIcon) {
                otherIcon.classList.remove('fa-chevron-up');
                otherIcon.classList.add('fa-chevron-down');
            }
        }
    });
    
   // Toggle del menú actual
    if (isVisible) {
        // Si está visible, lo ocultamos
        menu.style.display = 'none';
        if (icon) {
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    } else {
        // Si está oculto, lo mostramos
        menu.style.display = 'block';
        if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        }
    }
}

        // Función openModal mejorada (usa Bootstrap)
        function openModal(modalId) {
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: true
                });
                modal.show();
            }
        }

        function closeModal(modalId) {
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            }
        }

        // Función para resetear formulario de proveedor
        function resetProveedorForm() {
            const form = document.getElementById('proveedorForm');
            if (form) form.reset();
            const id = document.getElementById('proveedor_id');
            if (id) id.value = '';
            const title = document.getElementById('proveedorModalTitle');
            if (title) title.textContent = 'Agregar Proveedor';
        }

        // Función para cargar datos
        function cargarTodosLosDatos() {
            mostrarToast('Datos actualizados correctamente', 'success');
        }

        // Funciones placeholder para compatibilidad
        function cargarFacturasDelServidor() {
            mostrarToast('Cargando facturas...', 'info');
        }

        function cargarProveedores() {
            mostrarToast('Cargando proveedores...', 'info');
        }

        // Cerrar sidebar con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('mobile-open')) {
                    toggleSidebar();
                }
            }
        });

        // Autoajuste al cambiar tamaño de pantalla
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth > 992) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Inicializar tooltips de Bootstrap (opcional)
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });