// Tabler Icons utilities
// resources/js/tabler-icons.js

/**
 * Helper function to create Tabler icons
 * Since Tabler icons are SVG-based, we need to handle them programmatically
 */
export class TablerIcons {
    static baseUrl = 'https://cdn.jsdelivr.net/npm/@tabler/icons@2.47.0/icons/';
    
    /**
     * Create an icon element
     * @param {string} name - Icon name (without .svg extension)
     * @param {object} options - Icon options
     */
    static create(name, options = {}) {
        const {
            size = 24,
            className = 'icon',
            stroke = 2,
            color = 'currentColor'
        } = options;
        
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
        svg.setAttribute('width', size);
        svg.setAttribute('height', size);
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('stroke-width', stroke);
        svg.setAttribute('stroke', color);
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke-linecap', 'round');
        svg.setAttribute('stroke-linejoin', 'round');
        svg.className = className;
        
        // For common icons, we can include the paths directly
        const iconPaths = {
            'home': 'M5 12l-2 0l9 -9l9 9l-2 0M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6',
            'user': 'M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2',
            'settings': 'M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065zM9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0',
            'logout': 'M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2M9 12h12l-3 -3M18 15l3 -3',
            'check': 'M5 12l5 5l10 -10',
            'x': 'M18 6l-12 12M6 6l12 12',
            'alert-triangle': 'M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0zM12 9v4M12 17h.01'
        };
        
        if (iconPaths[name]) {
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('stroke', 'none');
            path.setAttribute('d', 'M0 0h24v24H0z');
            path.setAttribute('fill', 'none');
            svg.appendChild(path);
            
            const iconPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            iconPath.setAttribute('d', iconPaths[name]);
            svg.appendChild(iconPath);
        }
        
        return svg;
    }
    
    /**
     * Replace all icon placeholders in the document
     */
    static init() {
        const iconElements = document.querySelectorAll('[data-icon]');
        iconElements.forEach(element => {
            const iconName = element.getAttribute('data-icon');
            const icon = this.create(iconName, {
                size: element.getAttribute('data-size') || 24,
                className: element.className + ' icon',
                stroke: element.getAttribute('data-stroke') || 2
            });
            element.parentNode.replaceChild(icon, element);
        });
    }
}

export default TablerIcons;
