import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';

window.Alpine = Alpine;

Alpine.store('ui', {
    dark: false,
    init() {
        const saved = localStorage.getItem('cs_dark');
        this.dark = saved === '1';
        document.documentElement.classList.toggle('dark', this.dark);
    },
    toggleDark() {
        this.dark = !this.dark;
        localStorage.setItem('cs_dark', this.dark ? '1' : '0');
        document.documentElement.classList.toggle('dark', this.dark);
    },
});

Alpine.store('toast', {
    items: [],
    push(message, type = 'info') {
        const id = `${Date.now()}_${Math.random().toString(16).slice(2)}`;
        this.items = [...this.items, { id, message, type }];
        setTimeout(() => this.remove(id), 3500);
    },
    remove(id) {
        this.items = this.items.filter((t) => t.id !== id);
    },
});

document.addEventListener('alpine:init', () => {
    Alpine.store('ui').init();
});

Alpine.start();
