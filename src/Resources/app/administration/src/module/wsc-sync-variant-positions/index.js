import './page/wsc-sync-variant-positions-index';

Shopware.Module.register('wsc-sync-variant-positions', {
    type: 'plugin',
    name: 'WSCSyncVariantPositions',
    title: 'wsc-sync-variant-positions.general.mainMenuItemGeneral',
    description: 'wsc-sync-variant-positions.general.descriptionTextModule',
    color: '#ff3d58',
    icon: 'regular-cog',

    routes: {
        index: {
            component: 'wsc-sync-variant-positions-index',
            path: 'index'
        }
    },

    navigation: [{
        id: 'wsc-sync-variant-positions',
        label: 'wsc-sync-variant-positions.general.mainMenuItemGeneral',
        color: '#ff3d58',
        path: 'wsc.sync.variant.positions.index',
        icon: 'regular-cog',
        parent: 'sw-settings',
        position: 100
    }]
});
