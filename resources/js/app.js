

import Alpine from 'alpinejs';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';

window.Alpine = Alpine;
window.Toastify = Toastify;

window.showSuccessToast = (message) => {
    Toastify({
        text: message,
        duration: 3500,
        gravity: 'top',
        position: 'right',
        close: true,
        stopOnFocus: true,
        style: {
            background: 'linear-gradient(135deg, #109447, #16a34a)',
            borderRadius: '8px',
            boxShadow: '0 18px 36px rgba(16, 148, 71, .22)',
            color: '#ffffff',
            fontWeight: '800',
        },
    }).showToast();
};

Alpine.start();
