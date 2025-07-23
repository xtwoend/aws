import './bootstrap';

// Import jQuery and Popper.js
import jQuery from 'jquery';
import { createPopper } from '@popperjs/core';

// Import Tabler Core JavaScript (includes Bootstrap)
import '@tabler/core/dist/js/tabler.min.js';

// Import DataTables and extensions
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-buttons-bs5';

// Import Alpine.js for any interactive components
import Alpine from 'alpinejs';

// Make libraries globally available
window.$ = window.jQuery = jQuery;
window.Popper = createPopper;
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// Initialize custom features on DOM load
document.addEventListener('DOMContentLoaded', function() {
    console.log("🚀 Initializing custom features...");
    initCustomFeatures();
    console.log("✅ Custom features initialized.");
});

/**
 * Custom dashboard features
 */
function initCustomFeatures() {
    // Add loading states to forms on submit
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
            }
        });
    });
    
    // Add hover effects to stat cards
    const statCards = document.querySelectorAll('.dashboard-stats-card');
    statCards.forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('mouseover', () => card.classList.add('shadow-lg'));
        card.addEventListener('mouseout', () => card.classList.remove('shadow-lg'));
    });

    // Initialize theme toggle functionality
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        const themeIconDark = document.getElementById('theme-icon-dark');
        const themeIconLight = document.getElementById('theme-icon-light');

        const applyTheme = (theme) => {
            document.documentElement.setAttribute('data-bs-theme', theme);
            if (themeIconDark && themeIconLight) {
                themeIconDark.classList.toggle('d-none', theme === 'light');
                themeIconLight.classList.toggle('d-none', theme === 'dark');
            }
        };

        // Set initial theme icon state
        applyTheme(localStorage.getItem('theme') || 'dark');

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            applyTheme(newTheme);
        });
    }
}
