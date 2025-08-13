// Sistema de notificaciones con Toastify JS
class ToastNotification {
    constructor() {
        // Configuración base para Toastify
        this.defaultConfig = {
            duration: 4000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: {
                borderRadius: "12px",
                fontSize: "14px",
                fontWeight: "600",
                fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
                boxShadow: "0 8px 32px rgba(0, 0, 0, 0.15)",
                minWidth: "320px",
                maxWidth: "400px",
                padding: "16px 20px",
            }
        };
    }

    show(message, type = 'success', duration = 4000) {
        const configs = {
            success: {
                text: `✅ ${message}`,
                style: {
                    background: "linear-gradient(135deg, #28a745 0%, #20c997 100%)",
                    color: "#ffffff",
                    ...this.defaultConfig.style
                }
            },
            error: {
                text: `❌ ${message}`,
                style: {
                    background: "linear-gradient(135deg, #dc3545 0%, #c82333 100%)",
                    color: "#ffffff",
                    ...this.defaultConfig.style
                }
            },
            warning: {
                text: `⚠️ ${message}`,
                style: {
                    background: "linear-gradient(135deg, #ffc107 0%, #e0a800 100%)",
                    color: "#212529",
                    ...this.defaultConfig.style
                }
            },
            info: {
                text: `ℹ️ ${message}`,
                style: {
                    background: "linear-gradient(135deg, #17a2b8 0%, #138496 100%)",
                    color: "#ffffff",
                    ...this.defaultConfig.style
                }
            }
        };

        const config = {
            ...this.defaultConfig,
            ...configs[type],
            duration: duration,
            onClick: function() {
                this.hideToast();
            }
        };

        return Toastify(config).showToast();
    }

    success(message, duration = 4000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 4500) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 4000) {
        return this.show(message, 'info', duration);
    }

    // Método para notificaciones personalizadas
    custom(config) {
        const customConfig = {
            ...this.defaultConfig,
            ...config,
            style: {
                ...this.defaultConfig.style,
                ...config.style
            }
        };
        return Toastify(customConfig).showToast();
    }
}

// Inicializar el sistema de notificaciones
const toastNotification = new ToastNotification();

// Alias para compatibilidad
window.showToast = toastNotification;
window.toast = toastNotification;
