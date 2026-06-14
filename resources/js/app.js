import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// register components FIRST
window.getInitials = (first, last) => {
    return ((first?.charAt(0) || '') + (last?.charAt(0) || '')).toUpperCase();
};

import Swal from 'sweetalert2';

window.Swal = Swal;

// start Alpine LAST
Alpine.start();