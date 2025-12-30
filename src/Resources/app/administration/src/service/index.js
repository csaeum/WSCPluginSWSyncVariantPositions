import SyncService from './sync.service';

const { Application } = Shopware;

Application.addServiceProvider('syncService', (container) => {
    const initContainer = Application.getContainer('init');
    return new SyncService(initContainer.httpClient, container.loginService);
});
