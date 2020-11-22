window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
try {
    window.Popper = require('@popperjs/core').default;
    window.$ = window.jQuery = require('jquery');
    window.Noty = require('noty');

    require('bootstrap');
    require('bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker');

    $.DataTable = require( 'datatables.net' )( window, $ );
    require( 'datatables.net-bs4' )( window, $ );
    require( 'datatables.net-responsive' )( window, $ );
    require( 'datatables.net-responsive-bs4' )( window, $ );
    require( 'datatables.net-fixedheader' )( window,$ );
    require( 'datatables.net-fixedheader-bs4' )( window, $ );
} catch (e) {}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
window.select2 = require ('select2');
window.noty = require('noty');
window.Vapor = require('laravel-vapor');
// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

