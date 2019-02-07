export class Api {
    constructor() {

    }

    /**
     * Save item to persistent DB
     *
     * @param items
     * @returns {Promise<any>}
     */
    saveItemsToServer(items) {
        return new Promise((resolve, reject) => {
            // @todo: Rework this so that additional call is not needed
            this.getCsrfToken().then(response => {

                const url = '/item/update';

                fetch(url, {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': response
                    },
                    body: JSON.stringify(items)
                }).then(response => {
                    return response.json();
                }).then(data => {
                    resolve(data);
                });
            });
        });
    }

    /**
     * Get items from persistent DB
     *
     * @returns {Promise<any>}
     */
    getItemsFromServer() {
        return new Promise((resolve, reject) => {
            const url = '/item/get';

            fetch(url).then(response => {
                return response.json();
            }).then(data => {
                resolve(data);
            });
        });
    }

    /**
     * Get CSRF token
     *
     * @returns {Promise<any>}
     */
    getCsrfToken() {
        return new Promise((resolve, reject) => {
            const url = '/utils/csrf';

            fetch(url).then(response => {
                return response.json();
            }).then(data => {
                resolve(data);
            });
        });
    }
}
