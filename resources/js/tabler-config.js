// Custom Tabler configuration and utilities
// resources/js/tabler-config.js

export const TablerConfig = {
    // Initialize all Tabler components
    init() {
        this.initTooltips();
        this.initModals();
        this.initDropdowns();
        this.initAlerts();
    },

    // Initialize Bootstrap tooltips
    initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },

    // Initialize modals
    initModals() {
        // Auto-show modals with errors
        const errorModals = document.querySelectorAll('[data-auto-show="true"]');
        errorModals.forEach(modal => {
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        });
    },

    // Initialize dropdowns
    initDropdowns() {
        // Add any custom dropdown logic here
        const dropdowns = document.querySelectorAll('[data-bs-toggle="dropdown"]');
        dropdowns.forEach(dropdown => {
            // Custom dropdown initialization if needed
        });
    },

    // Initialize alerts
    initAlerts() {
        // Auto-hide success alerts after 5 seconds
        const successAlerts = document.querySelectorAll('.alert-success[data-auto-dismiss="true"]');
        successAlerts.forEach(alert => {
            setTimeout(() => {
                const alertInstance = new bootstrap.Alert(alert);
                alertInstance.close();
            }, 5000);
        });
    },

    // Utility functions
    showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        // Add to toast container or create one
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(container);
        }
        
        container.appendChild(toast);
        const toastInstance = new bootstrap.Toast(toast);
        toastInstance.show();
    }
};

export default TablerConfig;
