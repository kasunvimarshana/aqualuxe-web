export class LazyLoading {
    constructor(config = {}) {
        this.config = { debug: false, ...config };
    }
}