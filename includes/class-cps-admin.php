<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPS_Admin {
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    public function enqueue_admin_assets( $hook ) {
        $screen = get_current_screen();
        if ( ! $screen || 'cps_project' !== $screen->post_type ) {
            return;
        }

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_media();

        wp_enqueue_style( 'cps-admin', CPS_PLUGIN_URL . 'admin/css/admin.css', array(), CPS_VERSION );
        wp_enqueue_script( 'cps-admin', CPS_PLUGIN_URL . 'admin/js/admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ), CPS_VERSION, true );
        wp_localize_script(
            'cps-admin',
            'cpsAdmin',
            array(
                'mediaTitle' => __( 'Select image', 'custom-project-slider' ),
                'mediaButtonText' => __( 'Use this image', 'custom-project-slider' ),
            )
        );
    }
}
