<style>
    /* Apply zoom to entire Filament admin interface */
    /* .fi-sidebar, */
    .fi-topbar,
    .fi-header,
    /* .fi-page, */
    .fi-main,
    .fi-resource-page {
        zoom: 80%;
    }
    .fi-page {
        /* border: 1px solid #ccc; */
        zoom: 80%;
        width: 100%;
        /* margin-left: -37px; */
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

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .fi-main,
        .fi-topbar,
        .fi-page,
        .fi-header {
            zoom: 90%;
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
    }
</style>
