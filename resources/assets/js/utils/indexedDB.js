import { Uuid } from "./uuid";

export class IndexedDB {
    constructor(dbname, dbversion) {
        this.dbname = dbname;
        this.dbversion = dbversion;
        this.uuid = new Uuid();

        this.createDB();
    }

    /**
     * Create DB structure
     *
     */
    createDB() {
        const request = self.indexedDB.open(this.dbname, this.dbversion);
        this.request = request;

        this.request.onupgradeneeded = (event) => {
            const db = event.target.result;

            if (!db.objectStoreNames.contains('items')) {
                const store = db.createObjectStore('items', {
                    keyPath: 'uuid'
                });
            }
        };
    }

    /**
     * Insert data into store
     *
     * @param store
     * @param data
     * @param updateProperty
     * @param uuid
     * @param update
     * @returns {Promise<any>}
     */
    insertData(store, data, updateProperty = false, uuid = false, update = false) {
        return new Promise((resolve, reject) => {
            const request = self.indexedDB.open(this.dbname, this.dbversion);

            request.onsuccess = (requestEvent) => {
                const db = requestEvent.target.result;
                const transaction = db.transaction([store], 'readwrite');

                transaction.oncomplete = (transactionEvent) => {};

                const objectStore = transaction.objectStore(store);
                const cursorRequest = objectStore.openCursor();
                let dataExists = false;

                cursorRequest.onsuccess = (cursorEvent) => {
                    const cursor = cursorEvent.target.result;

                    if (cursor && !update) {
                        if (false !== updateProperty && cursor.value[updateProperty] === data[updateProperty]) {
                            dataExists = true;
                        }

                        cursor.continue();
                    } else if (!dataExists || update) {

                        if (false !== uuid) {
                            data.uuid = this.uuid.generateV4();
                        }

                        const result = objectStore.put(data);

                        result.onsuccess = (writeEvent) => {
                            resolve('Data successfully added');
                        };

                        result.onerror = (error) => {
                            reject(error);
                        }
                    }
                };

                cursorRequest.onerror = (error) => {
                    reject(error);
                };
            };
        });
    }

    /**
     * Get all store data
     *
     * @param store
     * @param key
     * @returns {Promise<any>}
     */
    getData(store, key = false) {
        return new Promise((resolve, reject) => {
            this.request.onsuccess = (requestEvent) => {
                const db = requestEvent.target.result;
                const transaction = db.transaction([store], 'readwrite');

                transaction.oncomplete = (transactionEvent) => {};

                const objectStore = transaction.objectStore(store);

                let result = false;

                if (key) {
                    result = objectStore.get(key);
                } else {
                    result = objectStore.getAll();
                }

                result.onsuccess = (writeEvent) => {
                    resolve(result);
                };

                result.onerror = (error) => {
                    reject(error);
                }
            }
        });
    }
}
