<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPS_Meta_Boxes {
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_project_meta' ) );
    }

    public function register_meta_box() {
        add_meta_box(
            'cps_project_details',
            __( 'Product Details', 'custom-project-slider' ),
            array( $this, 'render_meta_box' ),
            'cps_project',
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( 'cps_project_meta_nonce', 'cps_project_meta_nonce' );

        $short_description = get_post_meta( $post->ID, '_cps_short_description', true );
        $background_color  = get_post_meta( $post->ID, '_cps_background_color', true );

        $store_available   = get_post_meta( $post->ID, '_cps_store_available', true );
        $store_button_text = get_post_meta( $post->ID, '_cps_store_button_text', true );
        $store_url         = get_post_meta( $post->ID, '_cps_store_url', true );


        $display_order     = get_post_meta( $post->ID, '_cps_display_order', true );
        $main_image_id     = (int) get_post_meta( $post->ID, '_cps_main_image', true );
        $gallery_images    = get_post_meta( $post->ID, '_cps_gallery_images', true );
        $icon_images       = get_post_meta( $post->ID, '_cps_icon_images', true );
        $features          = get_post_meta( $post->ID, '_cps_features', true );
        $source_urls       = get_post_meta( $post->ID, '_cps_source_urls', true );


        // $cta_label         = get_post_meta( $post->ID, '_cps_cta_label', true );
        // $cta_url           = get_post_meta( $post->ID, '_cps_cta_url', true );

        if ( ! is_array( $gallery_images ) ) {
            $gallery_images = array();
        }

        if ( ! is_array( $icon_images ) ) {
            $icon_images = array();
        }

        if ( ! is_array( $features ) || empty( $features ) ) {
            $features = array(
                array(
                    'label' => '',
                    'value' => '',
                    'icon'  => '',
                ),
            );
        }

        if ( ! metadata_exists( 'post', $post->ID, '_cps_source_urls' ) ) {
            $legacy_platforms = get_post_meta( $post->ID, '_cps_platforms', true );
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

        if ( ! is_array( $source_urls ) || empty( $source_urls ) ) {
            $source_urls = array(
                array(
                    'title' => '',
                    'icon'  => '',
                    'url'   => '',
                ),
            );
        }

        $main_image_url = '';
        if ( $main_image_id ) {
            $main_image_url = wp_get_attachment_image_url( $main_image_id, 'medium' );
        }

        $gallery_images_csv = implode( ',', array_filter( array_map( 'absint', $gallery_images ) ) );
        $icon_images_csv    = implode( ',', array_filter( array_map( 'absint', $icon_images ) ) );
        ?>
<div class="cps-meta-box">
    <div class="cps-field-row">
        <label
            for="cps_short_description"><?php esc_html_e( 'Product Description', 'custom-project-slider' ); ?></label>
        <textarea id="cps_short_description" name="cps_short_description" rows="4" placeholder='Short description of product.'
            class="widefat"><?php echo esc_textarea( wp_unslash( $short_description ) ); ?></textarea>
    </div>

    <div class="cps-field-row cps-half-row">
        <div>
            <label for="cps_background_color"><?php esc_html_e( 'Accent Color', 'custom-project-slider' ); ?></label>
            <input type="text" id="cps_background_color" name="cps_background_color"
                value="<?php echo esc_attr( $background_color ); ?>" class="cps-color-picker" />
        </div>
        <!-- <div>
                    <label for="cps_display_order"><?php // esc_html_e( 'Display Order', 'custom-project-slider' ); ?></label>
                    <input type="number" id="cps_display_order" name="cps_display_order" value="<?php // echo esc_attr( $display_order ); ?>" min="0" />
                </div> -->
    </div>

    <!-- Is custom store avalable  -->

    <div class="cps-field-row">
        <p>
            <strong>
                <?php esc_html_e( 'Is Customer Store Available?', 'custom-project-slider' ); ?>
            </strong>
            <br>

            <label>
                <input type="radio" name="cps_store_available" value="yes" <?php checked( $store_available, 'yes' ); ?>>
                <?php esc_html_e( 'YES', 'custom-project-slider' ); ?>
            </label>

            &nbsp;&nbsp;

            <label>
                <input type="radio" name="cps_store_available" value="no"
                    <?php checked( $store_available ?: 'no', 'no' ); ?>>
                <?php esc_html_e( 'NO', 'custom-project-slider' ); ?>
            </label>
        </p>

        <div id="cps-store-conditional-fields"
            style="<?php echo ( 'yes' === $store_available ) ? '' : 'display: none;'; ?>">
            <p>
                <label for="cps_store_button_text">
                    <?php esc_html_e( 'Button Text', 'custom-project-slider' ); ?>
                </label>

                <input type="text" id="cps_store_button_text" name="cps_store_button_text"
                    value="<?php echo esc_attr( $store_button_text ); ?>" class="widefat"
                    <?php echo 'yes' === $store_available ? 'required' : ''; ?>>
            </p>

            <p>
                <label for="cps_store_url">
                    <?php esc_html_e( 'URL:', 'custom-project-slider' ); ?>
                    <span style="color: #6c757d; font-size: 0.85rem;">(Please provide valid, active URLs. Blank
                        entries and placeholder hashes (#) are not accepted.)</span>
                </label>

                <input type="url" id="cps_store_url" name="cps_store_url" placeholder="http://"
                    value="<?php echo esc_attr( $store_url ); ?>" class="widefat"
                    <?php echo 'yes' === $store_available ? 'required' : ''; ?>>
            </p>
        </div>
    </div>


    <!-- <div class="cps-field-row">
                <label><?php  // esc_html_e( 'Main Image', 'custom-project-slider' ); ?></label>
                <div class="cps-media-picker">
                    <input type="hidden" id="cps_main_image" name="cps_main_image" value="<?php // echo esc_attr( $main_image_id ); ?>" />
                    <div class="cps-image-preview-wrap">
                        <?php  // if ( $main_image_url ) : ?>
                            <img id="cps_main_image_preview" src="<?php  // echo esc_url( $main_image_url ); ?>" alt="" class="cps-image-preview" />
                        <?php  // else : ?>
                            <img id="cps_main_image_preview" src="" alt="" class="cps-image-preview cps-image-preview-empty" />
                        <?php  // endif; ?>
                    </div>
                    <button type="button" class="button cps-media-button" data-target="cps_main_image" data-preview="cps_main_image_preview"><?php esc_html_e( 'Select Image', 'custom-project-slider' ); ?></button>
                </div>
            </div> -->


    <div class="cps-field-row">
        <label><?php esc_html_e( 'Product Screenshots', 'custom-project-slider' ); ?></label>
        <div class="cps-media-picker multi">
            <input type="hidden" id="cps_gallery_images" name="cps_gallery_images"
                value="<?php echo esc_attr( $gallery_images_csv ); ?>" />

            <div class="cps-gallery-preview-wrap ui-sortable" id="cps_gallery_preview_wrap">
                <?php foreach ( $gallery_images as $gallery_image_id ) : 
                $gallery_image_url = wp_get_attachment_image_url( (int) $gallery_image_id, 'thumbnail' ); 
                if ( $gallery_image_url ) : ?>
                <div class="cps-gallery-item" data-id="<?php echo esc_attr( $gallery_image_id ); ?>">
                    <span class="cps-remove-image">&times;</span>
                    <img src="<?php echo esc_url( $gallery_image_url ); ?>" alt="" class="cps-gallery-thumb" />
                </div>
                <?php endif; 
            endforeach; ?>
            </div>
            <button type="button" class="button cps-media-button" data-target="cps_gallery_images"
                data-preview="cps_gallery_preview_wrap" data-multiple="true">
                <?php esc_html_e( 'Select Screenshots Images', 'custom-project-slider' ); ?>
            </button>
        </div>
    </div>


    <!-- old code  -->
    <!-- <div class="cps-field-row">
        <label><?php //esc_html_e( 'Product Screenshots', 'custom-project-slider' ); ?></label>
        <div class="cps-media-picker multi">
            <input type="hidden" id="cps_gallery_images" name="cps_gallery_images"
                value="<?php //echo esc_attr( $gallery_images_csv ); ?>" />
            <div class="cps-gallery-preview-wrap" id="cps_gallery_preview_wrap">
                <?php //foreach ( $gallery_images as $gallery_image_id ) : ?>
                <?php //$gallery_image_url = wp_get_attachment_image_url( (int) $gallery_image_id, 'thumbnail' ); ?>
                <?php //if ( $gallery_image_url ) : ?>
                <img src="<?php //echo esc_url( $gallery_image_url ); ?>" alt="" class="cps-gallery-thumb"
                    data-id="<?php //echo esc_attr( $gallery_image_id ); ?>" />
                <?php //endif; ?>
                <?php //endforeach; ?>
            </div>
            <button type="button" class="button cps-media-button" data-target="cps_gallery_images"
                data-preview="cps_gallery_preview_wrap"
                data-multiple="true"><?php //esc_html_e( 'Select Screenshots Images', 'custom-project-slider' ); ?></button>
        </div>
    </div> -->



    <div class="cps-field-row">
        <label><?php esc_html_e( 'Product Icons', 'custom-project-slider' ); ?></label>
        <div class="cps-media-picker multi">
            <input type="hidden" id="cps_icon_images" name="cps_icon_images"
                value="<?php echo esc_attr( $icon_images_csv ); ?>" />

            <div class="cps-gallery-preview-wrap sortable" id="cps_icon_preview_wrap">
                <?php foreach ( $icon_images as $icon_image_id ) : ?>
                <?php 
                if ( empty( $icon_image_id ) ) continue;
                $icon_image_url = wp_get_attachment_image_url( (int) $icon_image_id, 'thumbnail' ); 
                ?>
                <?php if ( $icon_image_url ) : ?>
                <div class="cps-gallery-thumb-item" data-id="<?php echo esc_attr( $icon_image_id ); ?>">
                    <img src="<?php echo esc_url( $icon_image_url ); ?>" alt="" class="cps-gallery-thumb" />
                    <span class="cps-remove-image">&times;</span>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button cps-media-button" data-target="cps_icon_images"
                data-preview="cps_icon_preview_wrap"
                data-multiple="true"><?php esc_html_e( 'Select Icon Images', 'custom-project-slider' ); ?></button>
        </div>
    </div>

    <!-- old code  -->

    <!-- <div class="cps-field-row">
        <label><?php //esc_html_e( 'Product Icons', 'custom-project-slider' ); ?></label>
        <div class="cps-media-picker multi">
            <input type="hidden" id="cps_icon_images" name="cps_icon_images"
                value="<?php// echo esc_attr( $icon_images_csv ); ?>" />
            <div class="cps-gallery-preview-wrap" id="cps_icon_preview_wrap">
                <?php// foreach ( $icon_images as $icon_image_id ) : ?>
                <?php //$icon_image_url = wp_get_attachment_image_url( (int) $icon_image_id, 'thumbnail' ); ?>
                <?php //if ( $icon_image_url ) : ?>
                <img src="<?php //echo esc_url( $icon_image_url ); ?>" alt="" class="cps-gallery-thumb"
                    data-id="<?php //echo esc_attr( $icon_image_id ); ?>" />
                <?php //endif; ?>
                <?php //endforeach; ?>
            </div>
            <button type="button" class="button cps-media-button" data-target="cps_icon_images"
                data-preview="cps_icon_preview_wrap"
                data-multiple="true"><?php// esc_html_e( 'Select Icon Images', 'custom-project-slider' ); ?></button>
        </div>
    </div> -->

    <!-- <div class="cps-field-row">
                <label><?php // esc_html_e( 'Highlights', 'custom-project-slider' ); ?></label>
                <div class="cps-repeatable-group" data-repeatable-group="features">
                    <?php //foreach ( $features as $index => $feature ) : ?>
                        <div class="cps-repeatable-item">
                            <div class="cps-repeatable-row">
                                <input type="text" name="cps_features[<?php // echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $feature['label'] ?? '' ); ?>" placeholder="Label" />
                                <input type="text" name="cps_features[<?php // echo esc_attr( $index ); ?>][value]" value="<?php echo esc_attr( $feature['value'] ?? '' ); ?>" placeholder="Value" />
                                <input type="text" name="cps_features[<?php // echo esc_attr( $index ); ?>][icon]" value="<?php echo esc_attr( $feature['icon'] ?? '' ); ?>" placeholder="Icon class" />
                                <button type="button" class="button cps-remove-item"><?php esc_html_e( 'Remove', 'custom-project-slider' ); ?></button>
                            </div>
                        </div>
                    <?php // endforeach; ?>
                </div>
                <button type="button" class="button cps-add-item" data-repeatable="features"><?php esc_html_e( 'Add Highlight', 'custom-project-slider' ); ?></button>
            </div> -->


    <div class="cps-field-row">
        <label><?php esc_html_e( 'Source URLs', 'custom-project-slider' ); ?>
            <span style="color: #6c757d; font-size: 0.85rem;">(Please provide valid, active URLs. Blank
                entries and placeholder hashes (#) are not accepted.)</span>
        </label>

        <div class="cps-repeatable-group" data-repeatable-group="source_urls">
            <?php foreach ( $source_urls as $index => $source ) : ?>
            <div class="cps-repeatable-item">
                <div class="cps-repeatable-row">
                    <?php $source_icon_id = absint( $source['icon'] ?? 0 ); ?>
                    <?php $source_icon_url = $source_icon_id ? wp_get_attachment_image_url( $source_icon_id, 'thumbnail' ) : ''; ?>

                    <input type="text" name="cps_source_urls[<?php echo esc_attr( $index ); ?>][title]"
                        value="<?php echo esc_attr( $source['title'] ?? '' ); ?>" placeholder="Title" />

                    <div class="cps-source-icon-picker">
                        <input type="hidden" id="cps_source_icon_<?php echo esc_attr( $index ); ?>"
                            name="cps_source_urls[<?php echo esc_attr( $index ); ?>][icon]"
                            value="<?php echo esc_attr( $source_icon_id ); ?>" />
                        <img id="cps_source_icon_preview_<?php echo esc_attr( $index ); ?>"
                            class="cps-source-icon-preview" src="<?php echo esc_url( $source_icon_url ?: '' ); ?>"
                            alt="" <?php echo $source_icon_url ? '' : 'hidden'; ?> />
                        <button type="button" class="button cps-source-icon-button"
                            data-target="cps_source_icon_<?php echo esc_attr( $index ); ?>"
                            data-preview="cps_source_icon_preview_<?php echo esc_attr( $index ); ?>">
                            <?php esc_html_e( 'Select Icon', 'custom-project-slider' ); ?>
                        </button>
                    </div>

                    <input type="url" name="cps_source_urls[<?php echo esc_attr( $index ); ?>][url]"
                        value="<?php echo esc_url( $source['url'] ?? '' ); ?>" placeholder="https://" />

                    <button type="button" class="button cps-remove-item">
                        <?php esc_html_e( 'Remove', 'custom-project-slider' ); ?>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <button type="button" class="button cps-add-item" data-repeatable="source_urls">
            <?php esc_html_e( 'Add Source URL', 'custom-project-slider' ); ?>
        </button>
    </div>

    <!-- <div class="cps-field-row cps-half-row">
                <div>
                    <label for="cps_cta_label"><?php // esc_html_e( 'CTA Label', 'custom-project-slider' ); ?></label>
                    <input type="text" id="cps_cta_label" name="cps_cta_label" value="<?php echo esc_attr( $cta_label ); ?>" />
                </div>
                <div>
                    <label for="cps_cta_url"><?php// esc_html_e( 'CTA URL', 'custom-project-slider' ); ?></label>
                    <input type="url" id="cps_cta_url" name="cps_cta_url" value="<?php echo esc_url( $cta_url ); ?>" />
                </div>
            </div> -->
</div>
<?php
    }

    public function save_project_meta( $post_id ) {
        if ( ! isset( $_POST['cps_project_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cps_project_meta_nonce'] ) ), 'cps_project_meta_nonce' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( 'cps_project' !== get_post_type( $post_id ) ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( isset( $_POST['cps_short_description'] ) ) {
            update_post_meta( $post_id, '_cps_short_description', sanitize_textarea_field( wp_unslash( $_POST['cps_short_description'] ) ) );
        }

        if ( isset( $_POST['cps_background_color'] ) ) {
            update_post_meta( $post_id, '_cps_background_color', sanitize_hex_color( wp_unslash( $_POST['cps_background_color'] ) ) );
        }

        $store_available = isset( $_POST['cps_store_available'] ) && 'yes' === sanitize_key( wp_unslash( $_POST['cps_store_available'] ) ) ? 'yes' : 'no';
        update_post_meta( $post_id, '_cps_store_available', $store_available );

        if ( isset( $_POST['cps_store_button_text'] ) ) {
            update_post_meta( $post_id, '_cps_store_button_text', sanitize_text_field( wp_unslash( $_POST['cps_store_button_text'] ) ) );
        }

        if ( isset( $_POST['cps_store_url'] ) ) {
            update_post_meta( $post_id, '_cps_store_url', esc_url_raw( wp_unslash( $_POST['cps_store_url'] ) ) );
        }

        if ( isset( $_POST['cps_display_order'] ) ) {
            update_post_meta( $post_id, '_cps_display_order', absint( wp_unslash( $_POST['cps_display_order'] ) ) );
        }

        if ( isset( $_POST['cps_main_image'] ) ) {
            $main_image_id = absint( wp_unslash( $_POST['cps_main_image'] ) );
            update_post_meta( $post_id, '_cps_main_image', $main_image_id );
        }

        if ( isset( $_POST['cps_gallery_images'] ) ) {
            $gallery_ids = array_filter( array_map( 'absint', preg_split( '/\s*,\s*/', wp_unslash( $_POST['cps_gallery_images'] ) ) ) );
            update_post_meta( $post_id, '_cps_gallery_images', array_values( array_unique( $gallery_ids ) ) );
        }

        if ( isset( $_POST['cps_icon_images'] ) ) {
            $icon_ids = array_filter( array_map( 'absint', preg_split( '/\s*,\s*/', wp_unslash( $_POST['cps_icon_images'] ) ) ) );
            update_post_meta( $post_id, '_cps_icon_images', array_values( array_unique( $icon_ids ) ) );
        }

        $features = array();
        if ( isset( $_POST['cps_features'] ) && is_array( $_POST['cps_features'] ) ) {
            foreach ( $_POST['cps_features'] as $feature ) {
                $item = array(
                    'label' => isset( $feature['label'] ) ? sanitize_text_field( wp_unslash( $feature['label'] ) ) : '',
                    'value' => isset( $feature['value'] ) ? sanitize_text_field( wp_unslash( $feature['value'] ) ) : '',
                    'icon'  => isset( $feature['icon'] ) ? sanitize_text_field( wp_unslash( $feature['icon'] ) ) : '',
                );

                if ( $item['label'] || $item['value'] || $item['icon'] ) {
                    $features[] = $item;
                }
            }
        }
        update_post_meta( $post_id, '_cps_features', $features );

        $source_urls = array();
        if ( isset( $_POST['cps_source_urls'] ) && is_array( $_POST['cps_source_urls'] ) ) {
            foreach ( $_POST['cps_source_urls'] as $source ) {
                $item = array(
                    'title' => isset( $source['title'] ) ? sanitize_text_field( wp_unslash( $source['title'] ) ) : '',
                    'icon'  => isset( $source['icon'] ) ? absint( wp_unslash( $source['icon'] ) ) : 0,
                    'url'   => isset( $source['url'] ) ? esc_url_raw( wp_unslash( $source['url'] ) ) : '',
                );

                if ( $item['title'] || $item['icon'] || $item['url'] ) {
                    $source_urls[] = $item;
                }
            }
        }
        update_post_meta( $post_id, '_cps_source_urls', $source_urls );

        if ( isset( $_POST['cps_cta_label'] ) ) {
            update_post_meta( $post_id, '_cps_cta_label', sanitize_text_field( wp_unslash( $_POST['cps_cta_label'] ) ) );
        }

        if ( isset( $_POST['cps_cta_url'] ) ) {
            update_post_meta( $post_id, '_cps_cta_url', esc_url_raw( wp_unslash( $_POST['cps_cta_url'] ) ) );
        }
    }
}