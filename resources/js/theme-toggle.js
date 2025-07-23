/**
 * Theme Toggle Functionality for Tabler.io Dark Theme
 */

class ThemeToggle {
    constructor() {
        this.init();
    }

    init() {
        // Always default to dark theme as primary theme
        this.currentTheme = localStorage.getItem('theme') || 'dark';
        
        // Force dark theme if no preference is stored
        if (!localStorage.getItem('theme')) {
            localStorage.setItem('theme', 'dark');
            this.currentTheme = 'dark';
        }
        
        // Set initial theme
        this.setTheme(this.currentTheme);
        
        // Bind event listener when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.bindEvents());
        } else {
            this.bindEvents();
        }
    }

    bindEvents() {
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }

    setTheme(theme) {
        const html = document.documentElement;
        const body = document.body;
        
        if (theme === 'dark') {
            html.setAttribute('data-bs-theme', 'dark');
            body.classList.add('theme-dark');
            body.classList.remove('theme-light');
            this.updateToggleIcon('dark');
        } else {
            html.setAttribute('data-bs-theme', 'light');
            body.classList.add('theme-light');
            body.classList.remove('theme-dark');
            this.updateToggleIcon('light');
        }
        
        // Store theme preference
        localStorage.setItem('theme', theme);
        this.currentTheme = theme;
    }

    updateToggleIcon(theme) {
        const darkIcon = document.getElementById('theme-icon-dark');
        const lightIcon = document.getElementById('theme-icon-light');
        
        if (darkIcon && lightIcon) {
            if (theme === 'dark') {
                // Show moon icon (dark mode is active)
                darkIcon.classList.remove('d-none');
                lightIcon.classList.add('d-none');
            } else {
                // Show sun icon (light mode is active)
                darkIcon.classList.add('d-none');
                lightIcon.classList.remove('d-none');
            }
        }
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        
        // Add switching class for animation
        document.body.classList.add('theme-switching');
        const toggleButton = document.getElementById('theme-toggle');
        if (toggleButton) {
            toggleButton.classList.add('switching');
        }
        
        // Set new theme after brief delay for animation
        setTimeout(() => {
            this.setTheme(newTheme);
            
            // Remove switching classes
            document.body.classList.remove('theme-switching');
            if (toggleButton) {
                toggleButton.classList.remove('switching');
            }
        }, 150);
    }

    getCurrentTheme() {
        return this.currentTheme;
    }
}

// Initialize theme toggle
const themeToggle = new ThemeToggle();

// Export for use in other scripts
window.ThemeToggle = themeToggle;
