// Simple event bus for pub/sub communication
const events = {};

export default {
    // Register an event listener
    on(event, callback) {
        events[event] = events[event] || [];
        events[event].push(callback);
    },

    // Trigger an event with data
    emit(event, data) {
        (events[event] || []).forEach(cb => cb(data));
    },

    // Remove event listener(s)
    // - Pass callback to remove specific listener
    // - Omit callback to remove all listeners for event
    off(event, callback) {
        if (!events[event]) return;
        if (callback) {
            events[event] = events[event].filter(cb => cb !== callback);
        } else {
            delete events[event];
        }
    },

    // Register a one-time listener (auto-removes after first emit)
    once(event, callback) {
        const wrapper = (data) => {
            callback(data);
            this.off(event, wrapper);
        };
        this.on(event, wrapper);
    },
};