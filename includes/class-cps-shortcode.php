<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPS_Shortcode {
    public function __construct() {
        add_shortcode( 'cps_project_slider', array( $this, 'render_shortcode' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
    }

    // public function enqueue_frontend_assets() {
    //     wp_enqueue_style( 'cps-frontend', CPS_PLUGIN_URL . 'assets/css/frontend.css', array(), CPS_VERSION );
    //     wp_enqueue_script( 'cps-frontend', CPS_PLUGIN_URL . 'assets/js/frontend.js', array(), CPS_VERSION, true );
    // }

    public function enqueue_frontend_assets() {
        wp_register_style( 
            'font-awesome-6', 
            'https://cloudflare.com', 
            array(), 
            '6.4.2' 
        );
        wp_enqueue_style( 'cps-frontend', CPS_PLUGIN_URL . 'assets/css/frontend.css', array(), CPS_VERSION );
        wp_enqueue_script( 'cps-frontend', CPS_PLUGIN_URL . 'assets/js/frontend.js', array(), CPS_VERSION, true );
    }

    public function render_shortcode( $atts = array() ) {
        $atts = shortcode_atts(
            array(
                'count' => -1,
            ),
            $atts,
            'cps_project_slider'
        );

        $args = array(
            'post_type'      => 'cps_project',
            'post_status'    => 'publish',
            'posts_per_page' => (int) $atts['count'],
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if ( (int) $atts['count'] === -1 ) {
            $args['posts_per_page'] = -1;
        }

        $projects = get_posts( $args );
        if ( empty( $projects ) ) {
            return '<p>' . esc_html__( 'No projects available yet.', 'custom-project-slider' ) . '</p>';
        }

        $prepared = array();
        foreach ( $projects as $project ) {
            $prepared[] = $this->prepare_project_data( $project );
        }

        ob_start();
        $projects = $prepared;
        include CPS_PLUGIN_DIR . 'templates/project-slider.php';

        return ob_get_clean();
    }

    private function prepare_project_data( $project ) {
        $main_image_id = (int) get_post_meta( $project->ID, '_cps_main_image', true );
        $gallery_ids = get_post_meta( $project->ID, '_cps_gallery_images', true );
        $gallery_ids = is_array( $gallery_ids ) ? array_filter( array_map( 'absint', $gallery_ids ) ) : array();
        $icon_ids = get_post_meta( $project->ID, '_cps_icon_images', true );
        $icon_ids = is_array( $icon_ids ) ? array_filter( array_map( 'absint', $icon_ids ) ) : array();
        $features = get_post_meta( $project->ID, '_cps_features', true );
        $source_urls = get_post_meta( $project->ID, '_cps_source_urls', true );
        if ( ! metadata_exists( 'post', $project->ID, '_cps_source_urls' ) ) {
            $legacy_platforms = get_post_meta( $project->ID, '_cps_platforms', true );
            if ( is_array( $legacy_platforms ) ) {
                $source_urls = array_map(
                    static function ( $platform ) {
                        return array(
                            'title' => $platform['label'] ?? '',
                            'icon'  => '',
                            'url'   => $platform['url'] ?? '',
                        );
                    },
                    $legacy_platforms
                );
            }
        }

        $main_image = $main_image_id ? wp_get_attachment_image_url( $main_image_id, 'large' ) : '';

        $gallery = array();
        foreach ( $gallery_ids as $gallery_id ) {
            $image_url = wp_get_attachment_image_url( $gallery_id, 'large' );
            if ( $image_url ) {
                $gallery[] = $image_url;
            }
        }

        if ( empty( $gallery ) && $main_image ) {
            $gallery[] = $main_image;
        }

        $icon_images = array();
        foreach ( $icon_ids as $icon_id ) {
            $image_url = wp_get_attachment_image_url( $icon_id, 'thumbnail' );
            if ( $image_url ) {
                $icon_images[] = $image_url;
            }
        }

        // $project_data = array(
        //     'id'             => $project->ID,
        //     'title'          => get_the_title( $project ),
        //     'description'    => get_post_meta( $project->ID, '_cps_short_description', true ),
        //     'store_url'      => get_post_meta( $project->ID, '_cps_store_url', true ),
        //     'main_image'     => $main_image,
        //     'gallery'        => $gallery,
        //     'icon_images'    => $icon_images,
        //     'features'       => is_array( $features ) ? $features : array(),
        // );

        $project_data = array(
            'id'             => $project->ID,
            'title'          => get_the_title( $project ),
            'description'    => get_post_meta( $project->ID, '_cps_short_description', true ),
            'store_available' => get_post_meta( $project->ID, '_cps_store_available', true ),
            'store_button_text' => get_post_meta( $project->ID, '_cps_store_button_text', true ),
            'store_url'      => get_post_meta( $project->ID, '_cps_store_url', true ),
            'main_image'     => $main_image,
            'gallery'        => $gallery,
            'icon_images'    => $icon_images,
            'features'       => is_array( $features ) ? $features : array(),
            'source_urls'    => is_array( $source_urls ) ? $source_urls : array(),
            'cta_label'      => get_post_meta( $project->ID, '_cps_cta_label', true ) ?: __( 'View Project', 'custom-project-slider' ),
            'cta_url'        => get_post_meta( $project->ID, '_cps_cta_url', true ) ?: '#',
            'background_color' => get_post_meta( $project->ID, '_cps_background_color', true ) ?: '#f15042',
        );

        return $project_data;
    }
}
