/**
 * Sidebar Manager
 * Handles sidebar toggle, mobile menu, and dropdown functionality
 */

class SidebarManager {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.sidebarToggle = document.getElementById('sidebarToggle');
        this.toggleIcon = document.getElementById('toggleIcon');
        this.mobileMenuBtn = document.getElementById('mobileMenuBtn');
        this.mobileOverlay = document.getElementById('mobileOverlay');
        this.dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');
        
        this.storageKey = 'sidebarExpanded';
        this.isMobile = window.innerWidth <= 768;
        
        this.init();
    }

    /**
     * Initialize sidebar functionality
     */
    init() {
        this.restoreSidebarState();
        this.attachEventListeners();
        this.handleResize();
    }

    /**
     * Restore sidebar state from localStorage
     */
    restoreSidebarState() {
        if (!this.isMobile) {
            const savedState = localStorage.getItem(this.storageKey);
            if (savedState === 'true') {
                this.expandSidebar();
            }
        }
    }

    /**
     * Attach all event listeners
     */
    attachEventListeners() {
        // Sidebar toggle
        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        }

        // Mobile menu button
        if (this.mobileMenuBtn) {
            this.mobileMenuBtn.addEventListener('click', () => this.openMobileSidebar());
        }

        // Mobile overlay
        if (this.mobileOverlay) {
            this.mobileOverlay.addEventListener('click', () => this.closeMobileSidebar());
        }

        // Dropdown toggles
        this.dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => this.toggleDropdown(e));
        });

        // Window resize
        window.addEventListener('resize', () => this.handleResize());

        // Close sidebar on ESC key (mobile)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobile && this.sidebar.classList.contains('expanded')) {
                this.closeMobileSidebar();
            }
        });
    }

    /**
     * Toggle sidebar expanded state
     */
    toggleSidebar() {
        if (this.sidebar.classList.contains('expanded')) {
            this.collapseSidebar();
        } else {
            this.expandSidebar();
        }
    }

    /**
     * Expand sidebar
     */
    expandSidebar() {
        this.sidebar.classList.add('expanded');
        this.updateToggleIcon('left');
        
        if (!this.isMobile) {
            localStorage.setItem(this.storageKey, 'true');
        }
    }

    /**
     * Collapse sidebar
     */
    collapseSidebar() {
        this.sidebar.classList.remove('expanded');
        this.updateToggleIcon('right');
        
        if (!this.isMobile) {
            localStorage.setItem(this.storageKey, 'false');
        }
        
        // Close all dropdowns when collapsing
        this.closeAllDropdowns();
    }

    /**
     * Update toggle icon direction
     */
    updateToggleIcon(direction) {
        if (!this.toggleIcon) return;
        
        this.toggleIcon.classList.remove('bi-chevron-left', 'bi-chevron-right');
        this.toggleIcon.classList.add(`bi-chevron-${direction}`);
    }

    /**
     * Open mobile sidebar
     */
    openMobileSidebar() {
        this.sidebar.classList.add('expanded');
        this.mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    /**
     * Close mobile sidebar
     */
    closeMobileSidebar() {
        this.sidebar.classList.remove('expanded');
        this.mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
        this.closeAllDropdowns();
    }

    /**
     * Toggle dropdown menu
     */
    toggleDropdown(event) {
        const toggle = event.currentTarget;
        const dropdown = toggle.closest('.sidebar-dropdown');
        const isOpen = dropdown.classList.contains('open');

        // Auto-expand sidebar on dropdown click if collapsed (desktop only)
        if (!this.sidebar.classList.contains('expanded') && !this.isMobile) {
            this.expandSidebar();
        }

        // Close other dropdowns
        this.closeAllDropdowns(dropdown);

        // Toggle current dropdown
        if (isOpen) {
            this.closeDropdown(dropdown);
        } else {
            this.openDropdown(dropdown);
        }
    }

    /**
     * Open dropdown
     */
    openDropdown(dropdown) {
        dropdown.classList.add('open');
        const toggle = dropdown.querySelector('.sidebar-dropdown-toggle');
        if (toggle) {
            toggle.setAttribute('aria-expanded', 'true');
        }
    }

    /**
     * Close dropdown
     */
    closeDropdown(dropdown) {
        dropdown.classList.remove('open');
        const toggle = dropdown.querySelector('.sidebar-dropdown-toggle');
        if (toggle) {
            toggle.setAttribute('aria-expanded', 'false');
        }
    }

    /**
     * Close all dropdowns except the specified one
     */
    closeAllDropdowns(except = null) {
        document.querySelectorAll('.sidebar-dropdown').forEach(dropdown => {
            if (dropdown !== except) {
                this.closeDropdown(dropdown);
            }
        });
    }

    /**
     * Handle window resize
     */
    handleResize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth <= 768;

        // If switching from mobile to desktop
        if (wasMobile && !this.isMobile) {
            this.mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
            this.restoreSidebarState();
        }

        // If switching from desktop to mobile
        if (!wasMobile && this.isMobile) {
            this.sidebar.classList.remove('expanded');
            this.closeAllDropdowns();
        }
    }
}

// Initialize sidebar when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new SidebarManager();
});

// Export for potential use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SidebarManager;
}