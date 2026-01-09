// store.js - A mini state manager for Vanilla JS
const CartStore = {
    // 1. STATE: The data we want to track
    state: {
        count: 0
    },

    // 2. LISTENERS: Functions waiting for updates (Subscribers)
    listeners: [],

    // 3. INITIALIZE: Set initial state from HTML (server-side rendering)
    init(initialCount) {
        this.state.count = parseInt(initialCount) || 0;
    },

    // 4. SUBSCRIBE: Let a part of the UI listen for changes
    subscribe(callback) {
        this.listeners.push(callback);
    },

    // 5. NOTIFY: Tell everyone the state changed
    notify() {
        this.listeners.forEach(callback => callback(this.state));
    },

    // 6. ACTIONS: Methods to modify state
    setCount(newCount) {
        this.state.count = newCount;
        this.notify(); // Tell the UI to update!
    },

    // The actual API call logic is moved here (Centralized)
    async updateCart(action, id, name = null, price = null) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('id', id);
        if(name) formData.append('name', name);
        if(price) formData.append('price', price);

        try {
            const response = await fetch('ajax_handler.php', { method: 'POST', body: formData });
            const data = await response.json();

            if (data.status === 'success') {
                // UPDATE STATE
                if (data.cart_count !== undefined) {
                    this.setCount(data.cart_count);
                }
                
                // RETURN DATA (so the UI can show alerts)
                return { success: true, message: data.message };
            }
        } catch (error) {
            console.error('Store Error:', error);
            return { success: false, message: 'Network error' };
        }
    }
};