import { createApp } from 'vue';

const app = createApp({
    data() {
        return {
            message: 'Hello Vue!'
        }
    }
});

app.mount('#app');
