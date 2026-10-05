<?php
/**
 * Plugin Name: Slider WP
 * Description: Dynamic slider manager from the WordPress admin.
 * Version: 1.0.0
 * Author: Akshit
 * Text Domain: custom-project-slider
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CPS_PLUGIN_FILE', __FILE__ );
define( 'CPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CPS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CPS_VERSION', '1.0.0' );

require_once CPS_PLUGIN_DIR . 'includes/class-cps-post-type.php';
require_once CPS_PLUGIN_DIR . 'includes/class-cps-meta-boxes.php';
require_once CPS_PLUGIN_DIR . 'includes/class-cps-admin.php';
require_once CPS_PLUGIN_DIR . 'includes/class-cps-shortcode.php';

// To init all the essential thinks 
function cps_load_plugin() {
    new CPS_Post_Type();
    new CPS_Meta_Boxes();
    new CPS_Admin();
    new CPS_Shortcode();
}
add_action( 'plugins_loaded', 'cps_load_plugin' );


// function custom_enable_svg_upload( $mimes ) {
//     if ( current_user_can( 'administrator' ) ) {
//         $mimes['svg']  = 'image/svg+xml';
//         $mimes['svgz'] = 'image/svg+xml';
//     }
//     return $mimes;
// }
// add_filter( 'upload_mimes', 'custom_enable_svg_upload' );


// function cps_activate_plugin() {
//     if ( function_exists( 'flush_rewrite_rules' ) ) {
//         flush_rewrite_rules();
//     }
// }
// register_activation_hook( __FILE__, 'cps_activate_plugin' );

// function cps_deactivate_plugin() {
//     if ( function_exists( 'flush_rewrite_rules' ) ) {
//         flush_rewrite_rules();
//     }
// }
// register_deactivation_hook( __FILE__, 'cps_deactivate_plugin' );
