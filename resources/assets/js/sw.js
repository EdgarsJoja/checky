import { IndexedDB } from "./utils/indexedDB";
import { Api } from "./utils/api";

// Cache
const CACHE_PREFIX = 'checky';
const CACHE_VERSION = 'v3';
const CACHE = `${CACHE_PREFIX}-${CACHE_VERSION}`;

// Indexed DB
const DB_NAME = 'checky';
const DB_VERSION = 3;

const STATIC_FILES = [
    '/css/app.css',
    '/js/app.js'
];

const CACHE_FIRST_STRATEGY = [
    'script',
    'style',
    'font',
    'image',
    // 'document'
];

const NETWORK_THEN_CACHE_STARTEGY = [
    'document'
];

const IGNORE_REQUESTS = [
    'chrome-extension'
];

// INSTALL
self.addEventListener('install', e => {
    console.log('--- Install ---');

    self.skipWaiting();

    e.waitUntil(
        caches.open(CACHE).then(cache => {
            return cache.addAll(STATIC_FILES);
        })
    );
});

// ACTIVATE
self.addEventListener('activate', e => {
    console.log('--- Activate ---');

    e.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(keys.map(key => {
                if (key.startsWith(CACHE_PREFIX) && key !== CACHE) {
                    return caches.delete(key);
                }
            }))
        })
    );
});

// FETCH
self.addEventListener('fetch', e => {
    console.log('--- Fetch ---');

    const destination  = e.request.destination;

    if (CACHE_FIRST_STRATEGY.indexOf(destination) > -1
        && IGNORE_REQUESTS.indexOf(e.request.protocol) > -1
    ) {
        e.respondWith(
            caches.match(e.request).then(resp => {
                return resp || fetch(e.request).then(response => {
                    return caches.open(CACHE).then(cache => {
                        cache.put(e.request, response.clone());
                        return response;
                    });
                });
            })
        );
    }
});

// MESSAGE
self.addEventListener('message', e => {
    console.log('--- Message ---');
    const db = new IndexedDB(DB_NAME, DB_VERSION);

    if (e.data.type === 'item-save') {
        e.data.item.status = 'pending';

        db.insertData('items', e.data.item, false, true).then(response => {
            e.ports[0].postMessage({
                status: 'success',
                message: 'Item saved'
            });
        });
    }

    if (e.data.type === 'item-update') {
        e.data.item.status = 'pending';

        db.insertData('items', e.data.item, 'uuid', false, true).then(response => {
            e.ports[0].postMessage({
                status: 'success',
                message: 'Item updated'
            });
        });
    }

    if (e.data.type === 'item-delete') {
        e.data.item.status = 'deleted';

        db.insertData('items', e.data.item, 'uuid', false, true).then(response => {
            e.ports[0].postMessage({
                status: 'success',
                message: 'Item deleted'
            });
        });
    }

    if (e.data.type === 'get-items') {
        db.getData('items').then(response => {
            const items = response.result.filter(item => item.status !== 'deleted');

            e.ports[0].postMessage({
                items: items
            });
        });
    }
});

// SYNC
self.addEventListener('sync', e => {
    console.log('----- Sync -----');

    const db = new IndexedDB(DB_NAME, DB_VERSION);
    const api = new Api();

    if (e.tag === 'sync-pending-items') {
        db.getData('items').then(response => {
            const pendingItems = response.result.filter(item => item.status === 'pending');

            api.saveItemsToServer(pendingItems).then(response => {
                if (!response.success) {
                    throw error;
                }

                pendingItems.map(item => {
                    item.status = 'synced';

                    db.insertData('items', item, 'uuid', false, true);
                });
            });
        });
    } else if (e.tag === 'sync-db-items') {
        api.getItemsFromServer().then(data => {
            data.map(item => {
                db.insertData('items', item, 'uuid');
            });
        });
    } else if (e.tag === 'sync-deleted-items') {
        db.getData('items').then(response => {
            const deletedItems = response.result.filter(item => item.status === 'deleted');

            api.deleteItemsFromServer(deletedItems).then(response => {
                if (!response.success) {
                    throw error;
                }

                deletedItems.map(item => {
                    db.removeData('items', item.uuid);
                });
            });
        });
    }
});
