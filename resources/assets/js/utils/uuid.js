export class Uuid {
    constructor() {
        this.uuidv4 = require('uuid/v4');
    }

    /**
     * Generate v4 uuid
     *
     * @returns {*}
     */
    generateV4() {
        return this.uuidv4();
    }
}
