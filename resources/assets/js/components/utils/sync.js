export class Sync {
    constructor() {
        if ('serviceWorker' in navigator && 'sync' in navigator) {
            console.log('Sync API supported');
        } else {
            console.warn('Sync API not supported');
        }
    }

    /**
     * Sync event trigger
     *
     * @param tag
     */
    static sync(tag) {
        navigator.serviceWorker.ready.then(registration => {
            registration.sync.register(tag);
        });
    }
}
