document.addEventListener('DOMContentLoaded', function () {
    function openMediaPicker(button, multiple) {
        if (typeof wp === 'undefined' || !wp.media) {
            return;
        }

        const target = document.getElementById(button.dataset.target);
        if (!target) {
            return;
        }

        const frame = wp.media({
            title: cpsAdmin.mediaTitle || 'Select image',
            multiple: multiple,
            library: { type: 'image' }
        });

        if (multiple) {
            frame.on('open', function () {
                const selection = frame.state().get('selection');
                const selectedIds = target.value.split(',').map(function (id) {
                    return parseInt(id, 10);
                }).filter(Boolean);

                selectedIds.forEach(function (id) {
                    selection.add(wp.media.attachment(id));
                });
            });
        }
        
        // frame.on('select', function () {
        //     const selection = frame.state().get('selection');

        //     if (multiple) {
        //         const ids = selection.map(function (attachment) {
        //             return attachment.id;
        //         });

        //         target.value = ids.join(',');

        //         const previewWrap = document.getElementById(button.dataset.preview);
        //         console.log(previewWrap);
        //         if (previewWrap) {
        //             previewWrap.innerHTML = '';
        //             selection.each(function (attachment) {
        //                 const img = document.createElement('img');
        //                 img.src = attachment.attributes.url;
        //                 img.alt = attachment.attributes.title || '';
        //                 img.className = 'cps-gallery-thumb';
        //                 previewWrap.appendChild(img);
        //             });
        //         }
        //         return;
        //     }

        //     const attachment = selection.first();
        //     if (!attachment) {
        //         return;
        //     }

        //     target.value = attachment.id;

        //     const preview = document.getElementById(button.dataset.preview);
        //     // preview.style.display='block';
        //     if (preview) {
        //         const sizes = attachment.attributes.sizes || {};
        //         preview.src = sizes.thumbnail ? sizes.thumbnail.url : attachment.attributes.url;
        //         preview.hidden = false;
        //     }
        // });
        frame.on('select', function () {
            const selection = frame.state().get('selection');

            if (multiple) {
                const ids = selection.map(function (attachment) {
                    return attachment.id;
                });

                target.value = ids.join(',');

                const previewWrap = document.getElementById(button.dataset.preview);
                console.log(previewWrap);

                if (previewWrap) {
                    previewWrap.innerHTML = '';

                    selection.each(function (attachment) {
                        const itemDiv = document.createElement('div');
                        itemDiv.className = target.id === 'cps_icon_images' ? 'cps-gallery-thumb-item' : 'cps-gallery-item';
                        itemDiv.setAttribute('data-id', attachment.id);

                        const removeSpan = document.createElement('span');
                        removeSpan.className = 'cps-remove-image';
                        removeSpan.innerHTML = '&times;';

                        const img = document.createElement('img');
                        img.src = attachment.attributes.sizes.thumbnail ? attachment.attributes.sizes.thumbnail.url : attachment.attributes.url; // Fallback to full size if thumbnail isn't loaded
                        img.alt = attachment.attributes.title || '';
                        img.className = 'cps-gallery-thumb';

                        itemDiv.appendChild(removeSpan);
                        itemDiv.appendChild(img);
                        previewWrap.appendChild(itemDiv);
                    });
                }
            } else {
                const attachment = selection.first();
                if (!attachment) {
                    return;
                }

                target.value = attachment.id;

                const preview = document.getElementById(button.dataset.preview);
                if (preview) {
                    const sizes = attachment.attributes.sizes || {};
                    preview.src = sizes.thumbnail ? sizes.thumbnail.url : attachment.attributes.url;
                    preview.hidden = false;
                }
            }
        });


        frame.open();
    }

    document.querySelectorAll('.cps-media-button').forEach(function (button) {
        button.addEventListener('click', function () {
            openMediaPicker(button, button.dataset.multiple === 'true');
        });
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.cps-source-icon-button');
        if (button) {
            event.preventDefault();
            openMediaPicker(button, false);
        }
    });

    function addRepeatableRow(groupName) {
        const group = document.querySelector('[data-repeatable-group="' + groupName + '"]');
        if (!group) {
            return;
        }

        let nextIndex = 0;
        group.querySelectorAll('input[name^="cps_' + groupName + '["]').forEach(function (input) {
            const match = input.name.match(/\[(\d+)\]/);
            if (match) {
                nextIndex = Math.max(nextIndex, parseInt(match[1], 10) + 1);
            }
        });
        const item = document.createElement('div');
        item.className = 'cps-repeatable-item';

        const inputs = groupName === 'features'
            ? '<input type="text" name="cps_features[' + nextIndex + '][label]" value="" placeholder="Label" />' +
            '<input type="text" name="cps_features[' + nextIndex + '][value]" value="" placeholder="Value" />' +
            '<input type="text" name="cps_features[' + nextIndex + '][icon]" value="" placeholder="Icon class" />'
            : '<input type="text" name="cps_source_urls[' + nextIndex + '][title]" value="" placeholder="Title" />' +
            '<div class="cps-source-icon-picker">' +
            '<input type="hidden" id="cps_source_icon_' + nextIndex + '" name="cps_source_urls[' + nextIndex + '][icon]" value="" />' +
            '<img id="cps_source_icon_preview_' + nextIndex + '" class="cps-source-icon-preview" alt="" hidden />' +
            '<button type="button" class="button cps-source-icon-button" data-target="cps_source_icon_' + nextIndex + '" data-preview="cps_source_icon_preview_' + nextIndex + '">Select Icon</button>' +
            '</div>' +
            '<input type="url" name="cps_source_urls[' + nextIndex + '][url]" value="" placeholder="https://" />';

        item.innerHTML = '<div class="cps-repeatable-row">' + inputs +
            '<button type="button" class="button cps-remove-item">Remove</button>' +
            '</div>';

        group.appendChild(item);

        item.querySelector('.cps-remove-item').addEventListener('click', function () {
            item.remove();
        });
    }

    document.querySelectorAll('.cps-add-item').forEach(function (button) {
        button.addEventListener('click', function () {
            addRepeatableRow(button.dataset.repeatable);
        });
    });

    document.querySelectorAll('.cps-remove-item').forEach(function (button) {
        button.addEventListener('click', function () {
            const item = button.closest('.cps-repeatable-item');
            if (item) {
                item.remove();
            }
        });
    });

    jQuery(function ($) {
        $('.cps-color-picker').wpColorPicker();
    });

    jQuery(document).ready(function ($) {
        const $storeFields = $('#cps-store-conditional-fields');
        const $storeInputs = $storeFields.find('input');

        function updateStoreFields() {
            const available = $('input[name="cps_store_available"]:checked').val() === 'yes';
            $storeFields.stop(true, true)[available ? 'slideDown' : 'slideUp'](200);
            $storeInputs.prop('required', available);
        }

        $('input[name="cps_store_available"]').on('change', updateStoreFields);
        updateStoreFields();
    });


    // jQuery(document).ready(function ($) {
    //     $('#cps_gallery_preview_wrap').on('click', '.cps-remove-image', function (e) {
    //         e.preventDefault();

    //         var $itemToRemove = $(this).closest('.cps-gallery-item');
    //         var $input = $('#cps_gallery_images');

    //         $itemToRemove.remove();

    //         var updatedIDs = [];
    //         $('#cps_gallery_preview_wrap .cps-gallery-thumb').each(function () {
    //             var id = $(this).data('id');
    //             if (id) {
    //                 updatedIDs.push(id);
    //             }
    //         });

    //         $input.val(updatedIDs.join(','));
    //     });
    // });
    // check in my class-cps-meta-boxes.php file and admin.js file during adding new project slider the cross icon in the icons images are showing but it is not working and also the drag and drop functionality for ordering the images make sure nothing i want to change i just want this remove image functionality using cross icon and drag-&-drop functionality works on both image previewers screenshot and icons and also in both state creating time and updating time.

    // screenshot image preview
    jQuery(document).ready(function ($) {
        var $previewWrap = $('#cps_gallery_preview_wrap');
        var $hiddenInput = $('#cps_gallery_images');

        function updateImageOrder() {
            var ids = [];
            $previewWrap.find('.cps-gallery-item').each(function () {
                var id = $(this).data('id');
                if (id) {
                    ids.push(id);
                }
            });
            $hiddenInput.val(ids.join(','));
        }

        if ($previewWrap.length) {
            $previewWrap.sortable({
                items: '.cps-gallery-item',
                cursor: 'move',
                opacity: 0.7,
                placeholder: 'cps-sortable-placeholder',
                update: function (event, ui) {
                    updateImageOrder();
                }
            });
        }

        $previewWrap.on('click', '.cps-remove-image', function (e) {
            e.preventDefault();
            $(this).closest('.cps-gallery-item').remove();
            updateImageOrder();
        });
    });

    // icons admin js 
    jQuery(document).ready(function ($) {

        function updateIconIdsString() {
            var ids = [];
            $('#cps_icon_preview_wrap .cps-gallery-thumb-item').each(function () {
                var id = $(this).data('id');
                if (id) {
                    ids.push(id);
                }
            });
            $('#cps_icon_images').val(ids.join(','));
        }

        $('#cps_icon_preview_wrap').sortable({
            items: '.cps-gallery-thumb-item',
            cursor: 'grabbing',
            opacity: 0.6,
            update: function (event, ui) {
                updateIconIdsString();
            }
        });

        $(document).on('click', '.cps-remove-image', function (e) {
            e.preventDefault();
            $(this).closest('.cps-gallery-thumb-item').remove();
            updateIconIdsString();
        });
    });

});




// if (multiple) {
//             frame.on('open', function () {
//                 const selection = frame.state().get('selection');
//                 const selectedIds = target.value.split(',').map(function (id) {
//                     return parseInt(id, 10);
//                 }).filter(Boolean);

//                 selectedIds.forEach(function (id) {
//                     selection.add(wp.media.attachment(id));
//                 });
//             });
//         }

//         frame.on('select', function () {
//             const selection = frame.state().get('selection');

//             if (multiple) {
//                 const ids = selection.map(function (attachment) {
//                     return attachment.id;
//                 });

//                 target.value = ids.join(',');

//                 const previewWrap = document.getElementById(button.dataset.preview);
//                 console.log(previewWrap);
//                 if (previewWrap) {
//                     previewWrap.innerHTML = '';
//                     selection.each(function (attachment) {
//                         const img = document.createElement('img');
//                         img.src = attachment.attributes.url;
//                         img.alt = attachment.attributes.title || '';
//                         img.className = 'cps-gallery-thumb';
//                         previewWrap.appendChild(img);
//                     });
//                 }
//                 return;
//             }

//             const attachment = selection.first();
//             if (!attachment) {
//                 return;
//             }
// console.log(target)
//             target.value = attachment.id;

//             const preview = document.getElementById(button.dataset.preview);
//             // preview.style.display='block';
//             if (preview) {
//                 const sizes = attachment.attributes.sizes || {};
//                 preview.src = sizes.thumbnail ? sizes.thumbnail.url : attachment.attributes.url;
//                 preview.hidden = false;
//             }
//         });

//         frame.open();
