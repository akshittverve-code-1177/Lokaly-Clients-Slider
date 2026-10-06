<?php
if ( empty( $projects ) ) {
    return;
}
// echo "<pre>";
// print_r($projects);
// echo "</pre>";
?>
<div class="cps-slider-wrapper" data-cps-slider>

    <div class="cps-slider-nav">
        <div class="cps-slider-nav-btn">
            <button type="button" class="cps-arrow cps-prev" aria-label="Previous project"><svg
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24" height="24" fill="currentColor"
                    style="display: inline-block; vertical-align: middle; transform: scaleX(-1);">
                    <path
                        d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                </svg>
            </button>
            <button type="button" class="cps-arrow cps-next" aria-label="Next project"><svg
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24" height="24" fill="currentColor"
                    style="display: inline-block; vertical-align: middle;">
                    <path
                        d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                </svg></button>
        </div>
    </div>

    <div class="cps-slider-stage">
        <?php foreach ( $projects as $index => $project ) : ?>
        <?php $is_active = 0 === $index ? 'is-active' : ''; ?>
        <article class="cps-slide <?php echo esc_attr( $is_active ); ?>"
            data-slide-index="<?php echo esc_attr( $index ); ?>">
            <div class="cps-visual-panel"
                style="--cps-accent: <?php echo esc_attr( $project['background_color'] ); ?>;">
                <div class="cps-visual-inner">
                    <?php $gallery = $project['gallery']; ?>
                    <?php if ( ! empty( $gallery ) ) : ?>
                    <div class="cps-gallery-frame">
                        <img class="cps-main-image" src="<?php echo esc_url( $gallery[0] ); ?>"
                            alt="<?php echo esc_attr( $project['title'] ); ?>" />
                    </div>
                    <?php if ( count( $gallery ) > 1 ) : ?>
                    <button type="button" class="gallery-arrow-btn cps-arrow cps-gallery-arrow cps-gallery-prev"
                        style="background: color-mix(in srgb, <?php echo esc_attr( $project['background_color'] ); ?> 80%, #ffffff);"
                        aria-label="Previous image"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"
                            width="20" height="20" fill="currentColor"
                            style="display: inline-block; vertical-align: middle; transform: scaleX(-1);">
                            <path
                                d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                        </svg>
                    </button>
                    <button type="button" class="gallery-arrow-btn cps-arrow cps-gallery-arrow cps-gallery-next"
                        style="background: color-mix(in srgb, <?php echo esc_attr( $project['background_color'] ); ?> 80%, #ffffff);"
                        aria-label="Next image"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="20"
                            height="20" fill="currentColor" style="display: inline-block; vertical-align: middle;">
                            <path
                                d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                        </svg></button>
                    <?php endif; ?>
                    <?php elseif ( ! empty( $project['main_image'] ) ) : ?>
                    <div class="cps-gallery-frame">
                        <img class="cps-main-image" src="<?php echo esc_url( $project['main_image'] ); ?>"
                            alt="<?php echo esc_attr( $project['title'] ); ?>" />
                    </div>
                    <?php endif; ?>

                    <!-- <?php //if ( ! empty( $project['gallery'] ) ) : ?>
                                <div class="cps-thumb-list" aria-label="Project gallery">
                                    <?php //foreach ( $project['gallery'] as $thumb_index => $thumb_url ) : ?>
                                        <button type="button" class="cps-thumb <?php //echo 0 === $thumb_index ? 'is-selected' : ''; ?>" data-image="<?php echo esc_url( $thumb_url ); ?>" aria-label="Show gallery image <?php echo esc_attr( $thumb_index + 1 ); ?>">
                                            <img src="<?php //echo esc_url( $thumb_url ); ?>" alt="" />
                                        </button>
                                    <?php// endforeach; ?>
                                </div>
                            <?php //endif; ?> -->
                </div>
            </div>

            <div class="cps-copy-panel">
                <div class="cps-copy-inner">
                    <h3><?php echo esc_html( $project['title'] ); ?></h3>
                    <p><?php echo wp_kses_post( $project['description'] ); ?></p>

                    <?php if ( 'yes' === $project['store_available'] && $project['store_button_text'] && $project['store_url'] ) : ?>
                    <a href="<?php echo esc_url( $project['store_url'] ); ?>"
                        target="<?php echo ( 'yes' === $project['cps_store_button_checkbox'] ) ? '_blank' : '_self'; ?>"
                        class="cps-cta-button"
                        style="background: <?php echo esc_attr( $project['background_color'] ); ?>; display: inline-flex; align-items: center; gap: 8px;">
                        <?php echo esc_html( $project['store_button_text'] ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="16" height="16"
                            fill="currentColor" style="display: inline-block; vertical-align: middle;">
                            <path
                                d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                        </svg>
                    </a>
                    <?php endif; ?>


                    <?php if ( ! empty( $project['icon_images'] ) ) : ?>
                    <ul class="cps-feature-list cps-image-feature-list" aria-label="Project highlights">
                        <?php foreach ( $project['icon_images'] as $icon_image ) : ?>
                        <li><span class="cps-feature-icon"><img src="<?php echo esc_url( $icon_image ); ?>"
                                    alt="" /></span></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php elseif ( ! empty( $project['features'] ) ) : ?>
                    <ul class="cps-feature-list">
                        <?php foreach ( $project['features'] as $feature ) : ?>
                        <?php $label = $feature['label'] ?? ''; ?>
                        <?php $value = $feature['value'] ?? ''; ?>
                        <?php $icon = $feature['icon'] ?? ''; ?>
                        <?php if ( ! $label && ! $value && ! $icon ) { continue; } ?>
                        <li>
                            <?php if ( $icon ) : ?>
                            <span class="cps-feature-icon <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></span>
                            <?php endif; ?>
                            <?php if ( $label || $value ) : ?>
                            <span
                                class="cps-feature-text"><?php echo esc_html( $label ); ?><?php echo $label && $value ? ': ' : ''; ?><?php echo esc_html( $value ); ?></span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                    <?php if ( ! empty( $project['gallery'] ) ) : ?>
                    <div class="cps-thumb-list" aria-label="Project gallery">
                        <?php foreach ( $project['gallery'] as $thumb_index => $thumb_url ) : ?>
                        <button type="button" class="cps-thumb <?php echo 0 === $thumb_index ? 'is-selected' : ''; ?>"
                            data-image="<?php echo esc_url( $thumb_url ); ?>"
                            aria-label="Show gallery image <?php echo esc_attr( $thumb_index + 1 ); ?>">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" alt="" />
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>


                    <?php if ( ! empty( $project['source_urls'] ) ) : ?>

                    <label class="source_heading">Available for</label>
                    <div class="cps-source-url-list"
                        aria-label="<?php esc_attr_e( 'Source URLs', 'custom-project-slider' ); ?>">
                        <?php foreach ( $project['source_urls'] as $source ) : ?>
                        <?php $source_title = $source['title'] ?? ''; ?>
                        <?php $source_icon_id = absint( $source['icon'] ?? 0 ); ?>

                        <?php $source_icon_url = $source_icon_id ? wp_get_attachment_image_url( $source_icon_id, 'thumbnail' ) : ''; ?>
                        <?php $source_url = $source['url'] ?? ''; ?>
                        <?php if ( ! $source_title && ! $source_icon_url && ! $source_url ) { continue; } ?>
                        <?php if ( $source_url ) : ?>
                        <a class="cps-source-url-item" href="<?php echo esc_url( $source_url ); ?>" target="<?php echo ( 'yes' === $project['cps_icon_button_checkbox'] ) ? '_blank' : '_self'; ?>"
                            rel="noopener noreferrer">
                            <?php else : ?>
                            <span class="cps-source-url-item">
                                <?php endif; ?>
                                <?php if ( $source_icon_url ) : ?>
                                <img src="<?php echo esc_url( $source_icon_url ); ?>" alt="" />
                                <?php endif; ?>
                                <?php if ( $source_title ) : ?>
                                <span><?php echo esc_html( $source_title ); ?></span>
                                <?php endif; ?>
                                <?php if ( $source_url ) : ?>
                        </a>
                        <?php else : ?>
                        </span>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>


    <!-- bottom-navigation only shown on small devices along with top-navigation -->
    <div class="cps-slider-nav bottom-cps-slider-nav">
        <div class="cps-slider-nav-btn">
            <button type="button" class="cps-arrow cps-prev" aria-label="Previous project"><svg
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24" height="24" fill="currentColor"
                    style="display: inline-block; vertical-align: middle; transform: scaleX(-1);">
                    <path
                        d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                </svg>
            </button>
            <button type="button" class="cps-arrow cps-next" aria-label="Next project"><svg
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24" height="24" fill="currentColor"
                    style="display: inline-block; vertical-align: middle;">
                    <path
                        d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                </svg></button>
        </div>
    </div>



</div>
</div>