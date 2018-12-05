
window._ = require('lodash');


try {
    // window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    // require('bootstrap');
    require('bootstrap/js/dist/util');
    require('bootstrap/js/dist/collapse');
} catch (e) {}


// window.axios = require('axios');
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// let token = document.head.querySelector('meta[name="csrf-token"]');

// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }

