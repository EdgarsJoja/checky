export class Message {
    constructor() {
        if ('serviceWorker' in navigator) {
            console.log('Message API supported');
        } else {
            console.warn('Message API not supported');
        }
    }

    /**
     * Post message to service worker
     *
     * @param data
     */
    static postMessage(data) {
        navigator.serviceWorker.controller.postMessage(data);
    }

    /**
     * Open messaging channel between window and service worker
     *
     * @param data
     * @param callback
     */
    static openChannel(data, callback) {
        const channel = new MessageChannel();
        channel.port1.onmessage = callback;

        navigator.serviceWorker.controller.postMessage(data, [channel.port2]);
    }
}
