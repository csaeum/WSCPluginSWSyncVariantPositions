import template from './wsc-sync-variant-positions-index.html.twig';
import './wsc-sync-variant-positions-index.scss';

const { Component, Mixin } = Shopware;

Component.register('wsc-sync-variant-positions-index', {
    template,

    inject: ['syncService'],

    mixins: [
        Mixin.getByName('notification')
    ],

    data() {
        return {
            isLoading: false,
            isDryRun: true,
            productId: null,
            syncResult: null,
            showResults: false
        };
    },

    computed: {
        canSync() {
            return !this.isLoading;
        }
    },

    methods: {
        async onSync() {
            this.isLoading = true;
            this.showResults = false;

            try {
                const result = await this.syncService.sync({
                    dryRun: this.isDryRun,
                    productId: this.productId || null
                });

                this.syncResult = result.data;
                this.showResults = true;

                if (result.success) {
                    const message = this.isDryRun
                        ? this.$tc('wsc-sync-variant-positions.notifications.previewSuccess', this.syncResult.updatedCount)
                        : this.$tc('wsc-sync-variant-positions.notifications.syncSuccess', this.syncResult.updatedCount);

                    this.createNotificationSuccess({
                        message: message
                    });
                }
            } catch (error) {
                this.createNotificationError({
                    message: this.$tc('wsc-sync-variant-positions.notifications.syncError')
                });
                console.error('Sync error:', error);
            } finally {
                this.isLoading = false;
            }
        },

        onReset() {
            this.syncResult = null;
            this.showResults = false;
            this.isDryRun = true;
            this.productId = null;
        }
    }
});
