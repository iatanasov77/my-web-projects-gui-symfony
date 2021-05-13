/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// jQuery
const $ = require('jquery');
window.$ = window.jQuery = $;

// Vendor scripts
require( '../vendor/bootstrap/js/bootstrap.bundle.js' );
require( '../vendor/jquery-easing/jquery.easing.js' );

// Main script
require( './sb-admin-2.js' );

// bootstrap should be before jquery-ui
require( 'jquery-ui-dist/jquery-ui.js' );
require( 'jquery-ui-dist/jquery-ui.css' );
require( 'jquery-ui-dist/jquery-ui.theme.css' );
