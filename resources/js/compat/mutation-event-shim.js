// Lightweight shim: translate legacy MutationEvent listeners (e.g. 'DOMNodeInserted', 'DOMSubtreeModified')
// into a MutationObserver to avoid browser deprecation warnings while preserving behavior.
// This shim installs itself early and intercepts addEventListener calls for those event types.
(function () {
    if (typeof window === "undefined" || !window.Node) return;

    const LEGACY = ["DOMNodeInserted", "DOMNodeRemoved", "DOMSubtreeModified"];

    // store the original addEventListener so we can fall back
    const _addEventListener = EventTarget.prototype.addEventListener;

    // map element -> observer & callbacks (to support removeEventListener if needed)
    const observers = new WeakMap();

    function ensureObserver(target) {
        let entry = observers.get(target);
        if (entry) return entry;
        const callbacks = new Map();
        const observer = new MutationObserver(function (mutations) {
            // call each registered callback with a lightweight synthetic event
            mutations.forEach(function (m) {
                const synthetic = {
                    type: "DOMSubtreeModified",
                    target: m.target,
                    addedNodes: m.addedNodes,
                    removedNodes: m.removedNodes,
                };
                callbacks.forEach(function (cb) {
                    try {
                        cb.call(target, synthetic);
                    } catch (e) {
                        setTimeout(() => {
                            throw e;
                        });
                    }
                });
            });
        });
        entry = { observer: observer, callbacks: callbacks };
        observers.set(target, entry);
        return entry;
    }

    EventTarget.prototype.addEventListener = function (
        type,
        listener,
        options
    ) {
        if (
            typeof type === "string" &&
            LEGACY.indexOf(type) !== -1 &&
            typeof listener === "function"
        ) {
            // install a MutationObserver on this target if not present
            const entry = ensureObserver(this);
            // register the callback so we can call it when mutations occur
            entry.callbacks.set(listener, listener);
            // start observing when first callback is added
            if (entry.callbacks.size === 1) {
                try {
                    entry.observer.observe(this, {
                        childList: true,
                        subtree: true,
                    });
                } catch (e) {
                    /* some nodes (like Window) may not be observable; fall back to original */
                }
            }
            return; // do not call the original addEventListener for legacy types
        }
        return _addEventListener.call(this, type, listener, options);
    };

    // patch removeEventListener to detach callbacks from our map
    const _removeEventListener = EventTarget.prototype.removeEventListener;
    EventTarget.prototype.removeEventListener = function (
        type,
        listener,
        options
    ) {
        if (
            typeof type === "string" &&
            LEGACY.indexOf(type) !== -1 &&
            typeof listener === "function"
        ) {
            const entry = observers.get(this);
            if (entry && entry.callbacks.has(listener)) {
                entry.callbacks.delete(listener);
                if (entry.callbacks.size === 0) {
                    try {
                        entry.observer.disconnect();
                    } catch (e) {}
                    observers.delete(this);
                }
            }
            return;
        }
        return _removeEventListener.call(this, type, listener, options);
    };
})();
