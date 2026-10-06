<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPS_Post_Type {
    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    public function register_post_type() {
        $labels = array(
            'name' => _x('Slider WP', 'post type general name', 'custom-project-slider'),
            'singular_name' => _x('Project', 'post type singular name', 'custom-project-slider'),
            'menu_name' => __('Slider WP', 'custom-project-slider'),
            'name_admin_bar' => __('Project', 'custom-project-slider'),
            'add_new' => __('Add New', 'custom-project-slider'),
            'add_new_item' => __('Add New Product', 'custom-project-slider'),
            'new_item' => __('New Project', 'custom-project-slider'),
            'edit_item' => __('Edit Project', 'custom-project-slider'),
            'view_item' => __('View Project', 'custom-project-slider'),
            'all_items' => __('All Product', 'custom-project-slider'),
            'search_items' => __('Search Projects', 'custom-project-slider'),
            'not_found' => __('No projects found.', 'custom-project-slider'),
            'not_found_in_trash'=> __('No projects found in Trash.', 'custom-project-slider'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-portfolio',
            'menu_position' => 20,
            'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
            'has_archive' => false,
            'rewrite' => array( 'slug' => 'slider_wp' ),
            'taxonomies' => array(),
        );

        register_post_type( 'cps_project', $args );
    }
}