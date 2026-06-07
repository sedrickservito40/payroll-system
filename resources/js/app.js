import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// register components FIRST
window.getInitials = (first, last) => {
    return ((first?.charAt(0) || '') + (last?.charAt(0) || '')).toUpperCase();
};

// start Alpine LAST
Alpine.start();