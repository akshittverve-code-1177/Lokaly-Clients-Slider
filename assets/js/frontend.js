document.addEventListener('DOMContentLoaded', function () {
    const sliders = document.querySelectorAll('[data-cps-slider]');

    sliders.forEach(function (slider) {
        const slides = Array.from(slider.querySelectorAll('.cps-slide'));
        const prevButtons = slider.querySelectorAll('.cps-prev');
        const nextButtons = slider.querySelectorAll('.cps-next');
        // console.log(slides)
        // console.log(prevButtons)
        // console.log(nextButtons)
        if (!slides.length) {
            return;
        }

        let currentIndex = 0;

        function animateHorizontal(element, direction) {
            if (!element || !element.animate || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            element.getAnimations().forEach(function (animation) {
                animation.cancel();
            });

            element.animate([
                { transform: 'translateX(' + (direction * 100) + '%)' },
                { transform: 'translateX(0)' }
            ], {
                // duration: 450,
                duration: 750,
                easing: 'cubic-bezier(0.22, 1, 0.36, 1)'
            });
        }

        // function updateSlider(direction) {
        //     const currentSlide = slides[currentIndex];
        //     const parentContainer = document.querySelector('..cps-slider-stage');
        //     parentContainer.appendChild(currentIndex); 
        //     slides.forEach(function (slide, index) {
        //         // console.log(currentIndex)
        //         slide.classList.toggle('is-active', index === currentIndex);
        //     });
        //     animateHorizontal(slides[currentIndex], direction);
        // }


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

        prevButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                // console.log(currentIndex)
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateSlider(-1);
            });
        });

        nextButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                // console.log(currentIndex)
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlider(1);
            });
        });

        slides.forEach(function (slide) {
            const thumbs = Array.from(slide.querySelectorAll('.cps-thumb'));
            const galleryPrev = slide.querySelector('.cps-gallery-prev');
            const galleryNext = slide.querySelector('.cps-gallery-next');

            function selectThumb(index, direction) {
                if (!thumbs.length) {
                    return;
                }

                const selectedIndex = (index + thumbs.length) % thumbs.length;
                const selectedThumb = thumbs[selectedIndex];
                const mainImage = slide.querySelector('.cps-main-image');
                const imageUrl = selectedThumb.getAttribute('data-image');
                // const mainImage = slide.querySelector('.cps-main-image- child');
                // const imageUrl = selectedThumb.getAttribute('data-image-child');
                const previousIndex = thumbs.findIndex(function (thumb) {
                    return thumb.classList.contains('is-selected');
                });

                if (mainImage && imageUrl && imageUrl !== mainImage.src) {
                    const frame = mainImage.closest('.cps-gallery-frame');
                    const outgoingImage = mainImage.cloneNode(true);
                    const slideDirection = direction || (selectedIndex > previousIndex ? 1 : -1);

                    frame.querySelectorAll('.cps-gallery-outgoing').forEach(function (image) {
                        image.remove();
                    });
                    outgoingImage.classList.add('cps-gallery-outgoing');
                    outgoingImage.setAttribute('aria-hidden', 'true');
                    outgoingImage.style.position = 'absolute';
                    outgoingImage.style.inset = '0';
                    outgoingImage.style.width = '100%';
                    outgoingImage.style.height = '100%';
                    // outgoingImage.style.width = '90%';
                    // outgoingImage.style.height = '100%';
                    outgoingImage.style.zIndex = '1';
                    outgoingImage.style.pointerEvents = 'none';
                    frame.appendChild(outgoingImage);
                    mainImage.src = imageUrl;
                    animateHorizontal(mainImage, slideDirection);

                    if (outgoingImage.animate && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        outgoingImage.animate([
                            { transform: 'translateX(0)' },
                            { transform: 'translateX(' + (-slideDirection * 100) + '%)' }
                        ], {
                            // duration: 450,
                            duration: 650,
                            easing: 'cubic-bezier(0.22, 1, 0.36, 1)'
                        }).onfinish = function () {
                            outgoingImage.remove();
                        };
                    } else {
                        outgoingImage.remove();
                    }
                }

                thumbs.forEach(function (thumb, thumbIndex) {
                    thumb.classList.toggle('is-selected', thumbIndex === selectedIndex);
                    thumb.setAttribute('aria-pressed', thumbIndex === selectedIndex ? 'true' : 'false');
                });
            }

            galleryPrev && galleryPrev.addEventListener('click', function () {
                const selectedIndex = thumbs.findIndex(function (thumb) {
                    return thumb.classList.contains('is-selected');
                });
                selectThumb(selectedIndex - 1, -1);
            });

            galleryNext && galleryNext.addEventListener('click', function () {
                const selectedIndex = thumbs.findIndex(function (thumb) {
                    return thumb.classList.contains('is-selected');
                });
                selectThumb(selectedIndex + 1, 1);
            });

            thumbs.forEach(function (thumb, index) {
                thumb.addEventListener('click', function () {
                    selectThumb(index);
                });
            });

            // galleryPrev && galleryPrev.addEventListener('click', function () {
            //     const selectedIndex = thumbs.findIndex(function (thumb) {
            // console.log(thumb)
            //         return thumb.classList.contains('is-selected');
            //     });
            //     selectThumb(selectedIndex - 1, -1);
            // });

            // galleryNext && galleryNext.addEventListener('click', function () {
            //     const selectedIndex = thumbs.findIndex(function (thumb) {
            // console.log(thumb)
            //         return thumb.classList.contains('is-selected');
            //     });
            //     selectThumb(selectedIndex + 1, 1);
            // });

            // thumbs.forEach(function (thumb, index) {
            //     thumb.addEventListener('click', function () {
            // console.log(thumb)
            //         selectThumb(index);
            //     });
            // });


            const frame = slide.querySelector('.cps-gallery-frame');

            if (frame) {
                let isDragging = false;
                let startX = 0;
                let currentX = 0;

                frame.addEventListener('mousedown', function (e) {
                    isDragging = true;
                    startX = e.clientX;
                    frame.style.cursor = 'grabbing';
                });

                frame.addEventListener('mousemove', function (e) {
                    if (!isDragging) return;
                    currentX = e.clientX;
                });

                window.addEventListener('mouseup', function (e) {
                    if (!isDragging) return;
                    isDragging = false;
                    frame.style.cursor = 'grab';

                    const diffX = currentX - startX;
                    const swipeThreshold = 50;

                    const currentIndex = thumbs.findIndex(function (thumb) {
                        return thumb.classList.contains('is-selected');
                    });

                    if (Math.abs(diffX) > swipeThreshold && currentX !== 0) {
                        if (diffX > 0) {
                            selectThumb(currentIndex - 1, -1);
                        } else {
                            selectThumb(currentIndex + 1, 1);
                        }
                    }

                    startX = 0;
                    currentX = 0;
                });

                frame.addEventListener('dragstart', function (e) {
                    e.preventDefault();
                });

                frame.style.cursor = 'grab';
            }

        });

    });
});