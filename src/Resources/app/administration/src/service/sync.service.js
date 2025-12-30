const ApiService = Shopware.Classes.ApiService;

class SyncService extends ApiService {
    constructor(httpClient, loginService, apiEndpoint = 'wsc-sync-variant-positions') {
        super(httpClient, loginService, apiEndpoint);
    }

    sync(options) {
        const headers = this.getBasicHeaders();

        return this.httpClient
            .post(`_action/${this.getApiBasePath()}/sync`, options, { headers })
            .then((response) => {
                return ApiService.handleResponse(response);
            });
    }

    preview(options) {
        const headers = this.getBasicHeaders();

        return this.httpClient
            .post(`_action/${this.getApiBasePath()}/preview`, options, { headers })
            .then((response) => {
                return ApiService.handleResponse(response);
            });
    }
}

export default SyncService;
