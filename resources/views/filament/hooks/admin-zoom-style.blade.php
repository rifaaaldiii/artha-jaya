<style>
    /* Apply zoom to entire Filament admin interface */
    .fi-topbar,
    .fi-header,
    .fi-main,
    .fi-login,
    .fi-resource-page {
        zoom: 80%;
    }

    /* Ensure user menu/profile is positioned on the right side of topbar */
    .fi-topbar-end {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        margin-left: auto !important;
    }
    
    .fi-user-menu .show {
        margin-left: auto;
    }
    .fi-user-menu {
        margin-left: auto;
    }
    
    
    .fi-global-search-field {
        width: 350px;
        /* max-width: calc(100vw - 600px); */
    }

    .fi-page {
        zoom: 80%;
        width: 100%;
    }

    /* Sidebar navigation */
    .fi-sidebar {
        width: 250px;
    }

    .fi-sidebar-nav {
        height: 100vh;
        zoom: 65%;
    }

    /* Tables and content */
    .fi-table {
        zoom: 80%;
    }

    /* Ensure proper scaling for modals and dropdowns */
    .fi-modal,
    .fi-dropdown,
    .fi-popover {
        zoom: 100%;
    }

    /* Fix any overflow issues caused by zoom */
    .fi-main {
        overflow-x: auto;
    }

    .fi-dropdown-panel {
        left: auto !important;
        right: 20px !important;
        top: 53px !important;
        zoom: 100%;
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .fi-main,
        .fi-topbar,
        .fi-page,
        .fi-header {
            zoom: 90%;
        }

        .fi-global-search-field {
            width: 300px;
            /* max-width: calc(100vw - 400px); */
        }
        
        .fi-dropdown-panel {
            left: auto !important;
            right: 20px !important;
            top: 65px !important;
            zoom: 80%;
        }
    }

    @media (max-width: 768px) {
        .fi-main,
        .fi-topbar,
        .fi-page,
        .fi-sidebar-nav,
        .fi-header {
            zoom: 100%;
        }
        .fi-main {
            padding: 0 10px;
        }
        .fi-global-search-field {
            width: 270px;
            /* max-width: calc(100vw - 300px); */
        }
        
        .fi-dropdown-panel {
            left: auto !important;
            right: 20px !important;
            top: 65px !important;
        }
    }
</style>
