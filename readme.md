Lokaly Clients
1. Product Title
2. Product Description
3. Is Customer Store Available? - YES / NO, If yes, Button Text, URL field
4. Product Icons
5. Product Screenshots
6. Source URLs - Title, Icon, URL (multiple)
7. Product Slider Images


<div class="cps-slider-nav">
                    <button type="button" class="cps-arrow cps-prev" aria-label="Previous project"><svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24" height="24"
                            fill="currentColor"
                            style="display: inline-block; vertical-align: middle; transform: scaleX(-1);">
                            <path
                                d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
                        </svg>
                    </button>
                    <button type="button" class="cps-arrow cps-next" aria-label="Next project"><svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="24" height="24"
                            fill="currentColor" style="display: inline-block; vertical-align: middle;">
                            <path
                                d="M566.6 342.6C579.1 330.1 579.1 309.8 566.6 297.3L406.6 137.3C394.1 124.8 373.8 124.8 361.3 137.3C348.8 149.8 348.8 170.1 361.3 182.6L466.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L466.7 352L361.3 457.4C348.8 469.9 348.8 490.2 361.3 502.7C373.8 515.2 394.1 515.2 406.6 502.7L566.6 342.7z" />
        </svg></button>
</div>

overflow: hidden clips everything outside the wrapper’s box, including long descriptions. Keep clipping on the slider’s viewport or image/track area, not on the container that holds the description. Let the information area grow and wrap:

.cps-slider-wrapper {
  overflow: visible;
}

.cps-slide-info {
  min-width: 0;
  overflow-wrap: anywhere;
}
If you only need to prevent horizontal spill from the slider, try clipping horizontally while leaving vertical content visible:

.cps-slider-wrapper {
  overflow-x: clip;
  overflow-y: visible;
}

If the wrapper or slide has a fixed height, remove it or use min-height so longer descriptions can expand.