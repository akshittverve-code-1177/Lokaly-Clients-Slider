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

The project slider only animates the incoming slide. In frontend.js:36-41, it toggles is-active on all slides first, then animates the new one. The CSS makes every inactive slide display: none in frontend.css:55-71, so the outgoing slide disappears immediately and can’t be seen moving out.

The gallery image preview behaves differently: it clones the current image, animates that clone out, and removes it when the animation finishes (frontend.js:80-114). That outgoing-image handling is why the preview transition looks right while project navigation does not. No code changed.


The smallest change is to reuse the same pattern already working for the gallery: clone the current project slide before changing the active slide, animate the clone out, then remove it when the animation ends. Keep the existing incoming-slide animation as it is.

In updateSlider(), that means:

Save the current slide and append a clone to .cps-slider-stage.
Make the clone an absolute, non-interactive overlay and mark it aria-hidden.
Toggle is-active as you do now, then animate the clone out in the same direction.
Remove the clone on animation finish.
You’ll also likely want overflow: hidden on .cps-slider-stage so the slides stay clipped to the slider while moving. This is a small, localized change and avoids redesigning the slider’s active-slide logic. The key is that the clone must be created before toggling is-active; otherwise the outgoing slide is already hidden.



Replace updateSlider() with this. It finds the currently active slide before switching, clones it, then animates that clone out while your existing incoming-slide animation runs:


function updateSlider(direction) {
    const parentContainer = slider.querySelector('.cps-slider-stage');
    const outgoingSlide = slides.find(function (slide) {
        return slide.classList.contains('is-active');
    });

    parentContainer.querySelectorAll('.cps-slide-outgoing').forEach(function (slide) {
        slide.remove();
    });

    let outgoingClone = null;

    if (outgoingSlide) {
        outgoingClone = outgoingSlide.cloneNode(true);
        outgoingClone.classList.add('cps-slide-outgoing');
        outgoingClone.setAttribute('aria-hidden', 'true');
        outgoingClone.inert = true;
        outgoingClone.style.position = 'absolute';
        outgoingClone.style.inset = '0';
        outgoingClone.style.zIndex = '2';
        outgoingClone.style.pointerEvents = 'none';
        parentContainer.appendChild(outgoingClone);
    }

    slides.forEach(function (slide, index) {
        slide.classList.toggle('is-active', index === currentIndex);
    });

    animateHorizontal(slides[currentIndex], direction);

    if (outgoingClone) {
        if (outgoingClone.animate && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            outgoingClone.animate([
                { transform: 'translateX(0)' },
                { transform: 'translateX(' + (-direction * 100) + '%)' }
            ], {
                duration: 750,
                easing: 'cubic-bezier(0.22, 1, 0.36, 1)'
            }).onfinish = function () {
                outgoingClone.remove();
            };
        } else {
            outgoingClone.remove();
        }
    }
}


Add overflow: hidden to .cps-slider-stage so the moving slides are clipped to the slider:

.cps-slider-stage {
    position: relative;
    overflow: hidden;
}

Two issues in your current attempt would stop it working: '..cps-slider-stage' is an invalid selector (it has an extra dot), and appendChild(currentIndex) passes a number where the browser expects a DOM node. Also, use slider.querySelector(...) so each slider instance finds its own stage.

