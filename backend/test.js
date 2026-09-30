
        $(document).ready(function() {

            // ================================================================
            // GLOBAL INIT
            // ================================================================

            // Move modals to body to fix backdrop/z-index issues
            $('#spec-edit-modal, #dim-spec-edit-modal, #spec-copy-modal, #spec-save-template-modal, #spec-apply-template-modal')
                .appendTo('body');

            // Select2
            if ($.fn.select2) {
                $('.select2').select2({
                    width: '100%'
                });
            }

            // TinyMCE
            tinymce.init({
                selector: '#description',
                height: 400,
                convert_urls: false,
                relative_urls: false,
                plugins: 'advlist autolink lists link image charmap preview anchor searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons help',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | link image media | fullscreen preview',
                menubar: 'file edit view insert format tools table help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave();
                    });
                }
            });

            // ================================================================
            // SLUG GENERATOR
            // ================================================================

            var isSlugManual = false;
            $('#slug').on('input', function() {
                isSlugManual = $(this).val().length > 0;
            });
            $('#name').on('input', function() {
                if (!isSlugManual) {
                    var slug = $(this).val().toLowerCase().trim()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/[\s-]+/g, '-');
                    $('#slug').val(slug);
                }
            });

            // ================================================================
            // TAB SWITCHING
            // ================================================================

            $('#wizard-tabs a').on('click', function(e) {
                e.preventDefault();
                if ($(this).hasClass('disabled')) {
                    toastr.warning('Please save the product first to access this tab.');
                    return;
                }
                var target = $(this).attr('href').replace('#', 'pane-');
                $('#wizard-tabs a').removeClass('active');
                $(this).addClass('active');
                $('.wizard-pane').removeClass('active').hide();
                $('#' + target).show().addClass('active');
                if ($.fn.select2) {
                    $('#' + target).find('.select2').select2({
                        width: '100%'
                    });
                }
                history.replaceState(null, null, $(this).attr('href'));
            });

            if (window.location.hash) {
                var $target = $('#wizard-tabs a[href="' + window.location.hash + '"]');
                if ($target.length && !$target.hasClass('disabled')) {
                    $target.trigger('click');
                }
            }

            // ================================================================
            // SAVE WIZARD (BASIC INFO)
            // ================================================================

            $('#btn-save-wizard').on('click', function() {
                var $activeForm = $('#form-basic');
                if (!$activeForm.length) return;
                tinymce.triggerSave();
                var formData = new FormData($activeForm[0]);
                $.ajax({
                    url: $activeForm.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btn-save-wizard').attr('disabled', true).html(
                            '<i class="ft-loader spinner"></i> Saving...');
                    },
                    success: function(res) {
                        $('#btn-save-wizard').attr('disabled', false).html('Save Changes');
                        if (res.success) {
                            toastr.success(res.message);
                            if (res.redirect) {
                                setTimeout(function() {
                                    window.location.href = res.redirect;
                                }, 500);
                            }
                        } else {
                            toastr.error(res.message || 'Error saving data.');
                        }
                    },
                    error: function(err) {
                        $('#btn-save-wizard').attr('disabled', false).html('Save Changes');
                        if (err.responseJSON && err.responseJSON.errors) {
                            toastr.error(Object.values(err.responseJSON.errors)[0][0]);
                        } else {
                            toastr.error('Server error occurred.');
                        }
                    }
                });
            });

            // ================================================================
            // DELETE PRODUCT
            // ================================================================

            $('#btn-delete-product').on('click', function() {
                Swal.fire({
                    title: 'Delete Product?',
                    text: 'All colors, sizes, images, and specifications will be permanently deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '"BLADE"',
                            method: 'DELETE',
                            data: {
                                _token: '"BLADE"'
                            },
                            success: function(res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    setTimeout(function() {
                                        window.location.href =
                                            '"BLADE"';
                                    }, 500);
                                }
                            },
                            error: function() {
                                toastr.error('Error deleting product.');
                            }
                        });
                    }
                });
            });

            /*BLADE*/ (isset($product))

                // ================================================================
                // COLORS & SIZES SORTABLE
                // ================================================================

                var colorList = document.getElementById('colors-list');
                if (colorList) {
                    new Sortable(colorList, {
                        handle: '.handle',
                        animation: 150,
                        onEnd: function() {
                            var order = {};
                            $('#colors-list tr[data-id]').each(function(i) {
                                order[$(this).data('id')] = i + 1;
                            });
                            $.ajax({
                                url: '"BLADE"',
                                method: 'POST',
                                data: {
                                    _token: '"BLADE"',
                                    orders: order
                                }
                            });
                        }
                    });
                }

                var sizeList = document.getElementById('sizes-list');
                if (sizeList) {
                    new Sortable(sizeList, {
                        handle: '.handle',
                        animation: 150,
                        onEnd: function() {
                            var order = {};
                            $('#sizes-list tr[data-id]').each(function(i) {
                                order[$(this).data('id')] = i + 1;
                            });
                            $.ajax({
                                url: '"BLADE"',
                                method: 'POST',
                                data: {
                                    _token: '"BLADE"',
                                    orders: order
                                }
                            });
                        }
                    });
                }

                // ================================================================
                // GALLERY SORTABLE (drag-only, save on button)
                // ================================================================

                var galleryGrid = document.getElementById('gallery-grid');
                if (galleryGrid) {
                    new Sortable(galleryGrid, {
                        handle: '.handle',
                        animation: 150
                    });
                }

                // ================================================================
                // COLOR IMAGES TAB
                // ================================================================

                $('#image-color-select').on('change', function() {
                    var val = $(this).val();
                    if (val) {
                        var mainImg = $(this).find('option:selected').data('main');
                        var lightImg = $(this).find('option:selected').data('lighton');
                        $('#color_image_id').val(val);
                        $('#form-color-images')[0].reset();

                        if (mainImg) {
                            $('#color-main-preview').attr('src', mainImg).show();
                            $('#color-main-placeholder').hide();
                        } else {
                            $('#color-main-preview').hide();
                            $('#color-main-placeholder').show();
                        }

                        if (lightImg) {
                            $('#color-lighton-preview').attr('src', lightImg).show();
                            $('#color-lighton-placeholder').hide();
                        } else {
                            $('#color-lighton-preview').hide();
                            $('#color-lighton-placeholder').show();
                        }

                        $('#color-image-fields').fadeIn(200);
                    } else {
                        $('#color-image-fields').hide();
                    }
                });

                $('#btn-save-color-images').on('click', function() {
                    var id = $('#color_image_id').val();
                    if (!id) return;
                    var formData = new FormData($('#form-color-images')[0]);
                    $.ajax({
                        url: '"BLADE"/' + id + '/images',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $('#btn-save-color-images').attr('disabled', true).html(
                                '<i class="ft-loader spinner"></i> Uploading...');
                        },
                        success: function(res) {
                            $('#btn-save-color-images').attr('disabled', false).html(
                                '<i class="ft-upload"></i> Upload & Save');
                            if (res.success) {
                                toastr.success(res.message);
                                var mainUrl = res.color.main_image ?
                                    '"BLADE"/' + res.color
                                    .main_image : '';
                                var lightUrl = res.color.lighton_image ?
                                    '"BLADE"/' + res.color
                                    .lighton_image : '';
                                $('#image-color-select option[value="' + id + '"]').data('main',
                                    mainUrl).data('lighton', lightUrl);
                                if (mainUrl) {
                                    $('#color-main-preview').attr('src', mainUrl).show();
                                    $('#color-main-placeholder').hide();
                                }
                                if (lightUrl) {
                                    $('#color-lighton-preview').attr('src', lightUrl).show();
                                    $('#color-lighton-placeholder').hide();
                                }
                            }
                        },
                        error: function() {
                            $('#btn-save-color-images').attr('disabled', false).html(
                                '<i class="ft-upload"></i> Upload & Save');
                            toastr.error('Error uploading images');
                        }
                    });
                });

                // ================================================================
                // GALLERY UPLOAD (preview-only, saved on button click)
                // ================================================================

                var newGalleryFiles = [];

                $('#gallery-file-input').on('change', function(e) {
                    var files = e.target.files;
                    if (!files.length) return;
                    $('#no-gallery-msg').hide();
                    Array.from(files).forEach(function(file) {
                        var fileId = 'new_' + Math.random().toString(36).substr(2, 9);
                        newGalleryFiles.push({
                            id: fileId,
                            file: file
                        });
                        var reader = new FileReader();
                        reader.onload = function(ev) {
                            var html =
                                '<div class="col-md-4 col-sm-6 col-6 mb-3 gallery-item" data-id="' +
                                fileId + '">' +
                                '<div class="card border mb-0 shadow-sm">' +
                                '<div class="card-img-top handle" style="height:150px; background:url(\'' +
                                ev.target.result +
                                '\') center/cover; cursor:move; position:relative; border-bottom:1px solid #eee;">' +
                                '<div style="position:absolute; top:5px; left:5px;"><span class="badge badge-success">New</span></div>' +
                                '<div style="position:absolute; top:5px; right:5px;"><button type="button" class="btn btn-sm btn-danger btn-delete-gallery p-1" data-id="' +
                                fileId +
                                '" style="line-height:1;"><i class="ft-trash-2"></i></button></div>' +
                                '</div>' +
                                '<div class="card-body p-2 bg-light"><input type="text" class="form-control form-control-sm gallery-caption" placeholder="Caption..." data-id="' +
                                fileId + '"></div>' +
                                '</div></div>';
                            $('#gallery-grid').append(html);
                        };
                        reader.readAsDataURL(file);
                    });
                    $(this).val('');
                });

                $('#btn-save-gallery').on('click', function() {
                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');
                    var orders = {};
                    var formData = new FormData();
                    formData.append('_token', '"BLADE"');

                    $('.gallery-item').each(function(index) {
                        var id = String($(this).data('id'));
                        var caption = $(this).find('.gallery-caption').val() || '';
                        if (id.startsWith('new_')) {
                            var f = newGalleryFiles.find(function(x) {
                                return x.id === id;
                            });
                            if (f) {
                                formData.append('new_images[]', f.file);
                                formData.append('new_captions[]', caption);
                            }
                        } else {
                            orders[id] = index + 1;
                            $.ajax({
                                url: '"BLADE"/' +
                                    id,
                                method: 'PUT',
                                data: {
                                    _token: '"BLADE"',
                                    caption: caption
                                }
                            });
                        }
                    });

                    // Save order for existing
                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: {
                            _token: '"BLADE"',
                            orders: orders
                        }
                    });

                    if (newGalleryFiles.length > 0) {
                        newGalleryFiles.forEach(function(item) {
                            formData.append('temp_ids[]', item.id);
                        });
                        $.ajax({
                            url: '"BLADE"',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(res) {
                                if (res.success && res.images) {
                                    res.images.forEach(function(img) {
                                        var item = $('.gallery-item[data-id="' + img
                                            .temp_id + '"]');
                                        item.attr('data-id', img.id);
                                        item.find('.btn-delete-gallery').attr('data-id',
                                            img.id);
                                        item.find('.gallery-caption').attr('data-id',
                                            img.id);
                                        item.find('.badge-success').closest('div')
                                            .remove();
                                    });
                                    newGalleryFiles = [];
                                }
                                $btn.attr('disabled', false).html(
                                    '<i class="ft-save"></i> Save Order/Captions');
                                toastr.success('Gallery saved successfully!');
                            },
                            error: function() {
                                $btn.attr('disabled', false).html(
                                    '<i class="ft-save"></i> Save Order/Captions');
                                toastr.error('Error uploading new images.');
                            }
                        });
                    } else {
                        setTimeout(function() {
                            $btn.attr('disabled', false).html(
                                '<i class="ft-save"></i> Save Order/Captions');
                            toastr.success('Gallery updated successfully!');
                        }, 400);
                    }
                });

                $(document).on('click', '.btn-delete-gallery', function() {
                    var id = String($(this).data('id'));
                    var card = $(this).closest('.gallery-item');
                    if (id.startsWith('new_')) {
                        newGalleryFiles = newGalleryFiles.filter(function(f) {
                            return f.id !== id;
                        });
                        card.fadeOut(function() {
                            $(this).remove();
                        });
                    } else {
                        $.ajax({
                            url: '"BLADE"/' + id,
                            method: 'DELETE',
                            data: {
                                _token: '"BLADE"'
                            },
                            success: function(res) {
                                if (res.success) {
                                    card.fadeOut(function() {
                                        $(this).remove();
                                    });
                                }
                            }
                        });
                    }
                });

                // ================================================================
                // COLOR FORM (ADD / EDIT)
                // ================================================================

                $('#btn-add-color, #btn-cancel-color').on('click', function() {
                    if ($(this).attr('id') === 'btn-cancel-color') {
                        $('#color-form-container').hide();
                        return;
                    }
                    $('#form-color')[0].reset();
                    $('#form-color .select2').trigger('change');
                    $('#color_id').val('');
                    $('#color-form-title').html('<i class="ft-plus-circle text-primary"></i> Add Color');
                    $('#color-form-container').show();
                    $('html, body').animate({
                        scrollTop: $('#color-form-container').offset().top - 100
                    }, 400);
                });

                $('#btn-save-color').on('click', function() {
                    if (!$('#color_color_master_id').val()) {
                        toastr.error('Select a color');
                        return;
                    }

                    var id = $('#color_id').val();
                    var url = id ? '"BLADE"/' + id :
                        '"BLADE"';
                    var method = id ? 'PUT' : 'POST';
                    $.ajax({
                        url: url,
                        method: method,
                        data: $('#form-color').serialize() + '&_token="BLADE"',
                        beforeSend: function() {
                            $('#btn-save-color').attr('disabled', true).html(
                                '<i class="ft-loader spinner"></i>');
                        },
                        success: function(res) {
                            $('#btn-save-color').attr('disabled', false).html(
                                '<i class="ft-check"></i> Save Color');
                            if (res.success) {
                                toastr.success(res.message);
                                window.location.reload();
                            }
                        },
                        error: function(err) {
                            $('#btn-save-color').attr('disabled', false).html(
                                '<i class="ft-check"></i> Save Color');
                            toastr.error('Server error occurred.');
                        }
                    });
                });

                $(document).on('click', '.btn-edit-color', function() {
                    var id = $(this).data('id');
                    var colorId = $(this).data('color-id');
                    $('#color_id').val(id);
                    $('#color_color_master_id').val(colorId).trigger('change');
                    $('#color-form-title').html('<i class="ft-edit-2 text-primary"></i> Edit Color');
                    $('#color-form-container').show();
                    $('html, body').animate({
                        scrollTop: $('#color-form-container').offset().top - 100
                    }, 400);
                });

                $(document).on('click', '.btn-delete-color', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Delete Color?',
                        text: 'All images related to this color will be lost.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete!'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '"BLADE"/' + id,
                                method: 'DELETE',
                                data: {
                                    _token: '"BLADE"'
                                },
                                success: function(res) {
                                    if (res.success) {
                                        toastr.success(res.message);
                                        $('tr[data-id="' + id + '"]').fadeOut(
                                    function() {
                                            $(this).remove();
                                        });
                                    }
                                }
                            });
                        }
                    });
                });

                // ================================================================
                // SIZE FORM (ADD / EDIT)
                // ================================================================

                $('#btn-add-size, #btn-cancel-size').on('click', function() {
                    if ($(this).attr('id') === 'btn-cancel-size') {
                        $('#size-form-container').hide();
                        return;
                    }
                    $('#form-size')[0].reset();
                    $('#size_id').val('');
                    $('#size-form-title').html('<i class="ft-plus-circle text-primary"></i> Add Size');
                    $('#size-form-container').show();
                    $('html, body').animate({
                        scrollTop: $('#size-form-container').offset().top - 100
                    }, 400);
                });

                $('#btn-save-size').on('click', function() {
                    if (!$('#size_label').val()) {
                        toastr.error('Label is required');
                        return;
                    }

                    var id = $('#size_id').val();
                    var url = id ? '"BLADE"/' + id :
                        '"BLADE"';
                    var method = id ? 'PUT' : 'POST';
                    $.ajax({
                        url: url,
                        method: method,
                        data: $('#form-size').serialize() + '&_token="BLADE"',
                        beforeSend: function() {
                            $('#btn-save-size').attr('disabled', true).html(
                                '<i class="ft-loader spinner"></i>');
                        },
                        success: function(res) {
                            $('#btn-save-size').attr('disabled', false).html(
                                '<i class="ft-check"></i> Save Size');
                            if (res.success) {
                                toastr.success(res.message);
                                window.location.reload();
                            }
                        },
                        error: function(err) {
                            $('#btn-save-size').attr('disabled', false).html(
                                '<i class="ft-check"></i> Save Size');
                            toastr.error('Server error occurred.');
                        }
                    });
                });

                $(document).on('click', '.btn-edit-size', function() {
                    var id = $(this).data('id');
                    var label = $(this).data('label');
                    var code = $(this).data('code') || '';
                    $('#size_id').val(id);
                    $('#size_label').val(label);
                    $('#size_code').val(code);
                    $('#size-form-title').html('<i class="ft-edit-2 text-primary"></i> Edit Size');
                    $('#size-form-container').show();
                    $('html, body').animate({
                        scrollTop: $('#size-form-container').offset().top - 100
                    }, 400);
                });

                $(document).on('click', '.btn-delete-size', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Delete Size?',
                        text: 'All dimension specs related to this size will be lost.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete!'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '"BLADE"/' + id,
                                method: 'DELETE',
                                data: {
                                    _token: '"BLADE"'
                                },
                                success: function(res) {
                                    if (res.success) {
                                        toastr.success(res.message);
                                        $('tr[data-id="' + id + '"]').fadeOut(
                                    function() {
                                            $(this).remove();
                                        });
                                    }
                                }
                            });
                        }
                    });
                });

                // ================================================================
                // SPECIFICATIONS TAB
                // ================================================================

                function renderSpecRow(row) {
                    var isRich = row.value_type === 'richtext';
                    var isChips = row.value_type === 'chips';
                    var valDisplay = isRich ?
                        '<span class="badge badge-light" style="font-size:10px; color:#6366f1;">Rich Text</span> ' :
                        (isChips ? '<span class="badge badge-light" style="font-size:10px; color:#8b5cf6;">Chips</span> ' : '');
                    var safeLabel = row.label ? row.label.replace(/"/g, '&quot;') : '';
                    var rawVal = row.value || '';

                    var displayVal = rawVal;
                    if (rawVal) {
                        if (isChips) {
                            try {
                                var parsed = JSON.parse(rawVal);
                                displayVal = parsed.length + ' chip(s)';
                            } catch(e) { displayVal = 'Invalid Chips'; }
                        } else {
                            var strippedVal = rawVal.replace(/<[^>]*>?/gm, ''); // strip HTML
                            displayVal =
                                '<div style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="' +
                                strippedVal.replace(/"/g, '&quot;') + '">' + strippedVal + '</div>';
                        }
                    } else {
                        displayVal = '<span class="text-muted" style="font-size:12px;">— empty —</span>';
                    }

                    return '<tr data-id="' + row.id + '" data-value-type="' + row.value_type + '" data-label="' +
                        safeLabel + '" class="spec-row" style="border-bottom:1px solid #f3f4f6;">' +
                        '<td style="padding:10px 8px; vertical-align:middle; width:28px;"><i class="ft-menu handle text-muted" style="cursor:move; font-size:14px;"></i></td>' +
                        '<td style="padding:10px 12px; vertical-align:middle; font-weight:500; color:#374151;">' + (
                            row.label || '') + '</td>' +
                        '<td style="padding:10px 12px; vertical-align:middle; color:#4b5563;">' + valDisplay +
                        displayVal + '</td>' +
                        '<td style="padding:10px 8px; vertical-align:middle; text-align:right; white-space:nowrap;">' +
                        '<button type="button" class="btn btn-sm btn-edit-spec p-1 mr-1" data-id="' + row.id +
                        '" style="color:#6366f1; background:none; border:none; font-size:14px;"><i class="ft-edit-2"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-delete-spec p-1" data-id="' + row.id +
                        '" style="color:#ef4444; background:none; border:none; font-size:14px;"><i class="ft-trash-2"></i></button>' +
                        '</td>' +
                        '</tr>';
                }

                function renderDimSpecRow(row) {
                    var safeLabel = row.label ? row.label.replace(/"/g, '&quot;') : '';

                    var sizesCols = '';
                    /*BLADE*/ ($product->sizes && $product->sizes->count() > 0)
                        /*BLADE*/ ($product->sizes as $size)
                            var sizeValData = (row.values && row.values["BLADE"]) ? row.values[
                                "BLADE"] : null;
                            var rawVal = sizeValData ? (sizeValData.value || '') : '';
                            var isRich = sizeValData ? (sizeValData.value_type === 'richtext') : false;
                            var isChips = sizeValData ? (sizeValData.value_type === 'chips') : false;
                            var valDisplay = isRich ?
                                '<span class="badge badge-light" style="font-size:10px; color:#6366f1;">Rich Text</span> ' :
                                (isChips ? '<span class="badge badge-light" style="font-size:10px; color:#8b5cf6;">Chips</span> ' : '');
                            var displayVal = rawVal;
                            if (rawVal) {
                                if (isChips) {
                                    try {
                                        var parsed = JSON.parse(rawVal);
                                        displayVal = parsed.length + ' chip(s)';
                                    } catch(e) { displayVal = 'Invalid Chips'; }
                                } else {
                                    var strippedVal = rawVal.replace(/<[^>]*>?/gm, '');
                                    displayVal =
                                        '<div style="max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="' +
                                        strippedVal.replace(/"/g, '&quot;') + '">' + strippedVal + '</div>';
                                }
                            } else {
                                displayVal = '<span class="text-muted" style="font-size:12px;">— empty —</span>';
                            }
                            sizesCols += '<td style="padding:10px 12px; vertical-align:middle; color:#4b5563;">' +
                                valDisplay + displayVal + '</td>';
                        /*BLADE*/
                    /*BLADE*/
                        var sizeValData = (row.values && Object.values(row.values)[0]) ? Object.values(row.values)[
                            0] : null;
                        var rawVal = sizeValData ? (sizeValData.value || '') : '';
                        var isRich = sizeValData ? (sizeValData.value_type === 'richtext') : false;
                        var isChips = sizeValData ? (sizeValData.value_type === 'chips') : false;
                        var valDisplay = isRich ?
                            '<span class="badge badge-light" style="font-size:10px; color:#6366f1;">Rich Text</span> ' :
                            (isChips ? '<span class="badge badge-light" style="font-size:10px; color:#8b5cf6;">Chips</span> ' : '');
                        var displayVal = rawVal;
                        if (rawVal) {
                            if (isChips) {
                                try {
                                    var parsed = JSON.parse(rawVal);
                                    displayVal = parsed.length + ' chip(s)';
                                } catch(e) { displayVal = 'Invalid Chips'; }
                            } else {
                                var strippedVal = rawVal.replace(/<[^>]*>?/gm, '');
                                displayVal =
                                    '<div style="max-width:150px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="' +
                                    strippedVal.replace(/"/g, '&quot;') + '">' + strippedVal + '</div>';
                            }
                        } else {
                            displayVal = '<span class="text-muted" style="font-size:12px;">— empty —</span>';
                        }
                        sizesCols += '<td style="padding:10px 12px; vertical-align:middle; color:#4b5563;">' +
                            valDisplay + displayVal + '</td>';
                    /*BLADE*/

                    // encode values JSON to store in data attribute for editing
                    var valuesJson = encodeURIComponent(JSON.stringify(row.values || {}));

                    return '<tr data-id="' + row.id + '" data-label="' + safeLabel + '" data-values="' +
                        valuesJson + '" class="spec-row dimension-row" style="border-bottom:1px solid #f3f4f6;">' +
                        '<td style="padding:10px 8px; vertical-align:middle; width:28px;"><i class="ft-menu handle text-muted" style="cursor:move; font-size:14px;"></i></td>' +
                        '<td style="padding:10px 12px; vertical-align:middle; font-weight:500; color:#374151;">' + (
                            row.label || '') + '</td>' +
                        sizesCols +
                        '<td style="padding:10px 8px; vertical-align:middle; text-align:right; white-space:nowrap;">' +
                        '<button type="button" class="btn btn-sm btn-edit-dim-spec p-1 mr-1" data-id="' + row.id +
                        '" style="color:#6366f1; background:none; border:none; font-size:14px;"><i class="ft-edit-2"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-delete-dim-spec p-1" data-id="' + row.id +
                        '" style="color:#ef4444; background:none; border:none; font-size:14px;"><i class="ft-trash-2"></i></button>' +
                        '</td>' +
                        '</tr>';
                }

                function initSpecSortables() {
                    $('.spec-tbody').each(function() {
                        if (this._sortable) this._sortable.destroy();
                        this._sortable = new Sortable(this, {
                            handle: '.handle',
                            animation: 150,
                            onEnd: function(evt) {
                                var tbody = $(evt.item).closest('tbody');
                                var orders = {};
                                tbody.find('tr').each(function(i) {
                                    orders[$(this).data('id')] = i + 1;
                                });
                                $.ajax({
                                    url: '"BLADE"',
                                    method: 'POST',
                                    data: {
                                        _token: '"BLADE"',
                                        orders: orders
                                    }
                                });
                            }
                        });
                    });
                }

                function loadProductSpecs() {
                    $('.spec-tbody').empty();

                    $.get('"BLADE"', function(res) {
                        if (!res.success) return;

                        // Basic Specs
                        var basicTbody = $('#spec-tbody-basic');
                        if (res.rows.basic_specifications && res.rows.basic_specifications.length > 0) {
                            $('#no-specs-basic').hide();
                            res.rows.basic_specifications.forEach(function(row) {
                                var $tr = $(renderSpecRow(row));
                                $tr.data('value', row.value || '');
                                basicTbody.append($tr);
                            });
                        } else {
                            $('#no-specs-basic').show();
                        }

                        // Dimensions
                        var dimTbody = $('#spec-tbody-dimensions');
                        if (res.rows.dimensions && res.rows.dimensions.length > 0) {
                            $('#no-specs-dimensions').hide();
                            res.rows.dimensions.forEach(function(row) {
                                var $tr = $(renderDimSpecRow(row));
                                dimTbody.append($tr);
                            });
                        } else {
                            $('#no-specs-dimensions').show();
                        }

                        initSpecSortables();
                    });
                }

                // Load specs when the tab is shown or page loads
                $('a[href="#specifications"]').on('shown.bs.tab', function(e) {
                    loadProductSpecs();
                });
                // Load once initially just in case
                loadProductSpecs();

                // Add spec row
                $(document).on('click', '.btn-add-spec', function() {
                    var section = $(this).data('section');
                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i>');

                    var defaultAttrId = $('#spec-edit-label option:nth-child(2)').val() || 1;

                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: {
                            _token: '"BLADE"',
                            section: section,
                            dec_spec_attribute_id: defaultAttrId
                        },
                        success: function(res) {
                            $btn.attr('disabled', false).html(
                            '<i class="ft-plus"></i> Add Row');
                            if (res.success) {
                                if (section === 'dimensions') {
                                    $('#no-specs-dimensions').hide();
                                    $('#spec-tbody-dimensions').append(renderDimSpecRow(res
                                        .row));
                                    initSpecSortables();
                                    openDimSpecEdit(res.row);
                                } else {
                                    $('#no-specs-basic').hide();
                                    $('#spec-tbody-basic').append(renderSpecRow(res.row));
                                    initSpecSortables();
                                    openSpecEdit(res.row);
                                }
                            }
                        }
                    });
                });

                // Open edit modal for a spec row
                function openSpecEdit(row) {
                    $('#spec-edit-id').val(row.id);

                    var matchingOption = $('#spec-edit-label option').filter(function() {
                        return $(this).text() === row.label;
                    });
                    if (matchingOption.length) {
                        $('#spec-edit-label').val(matchingOption.val()).trigger('change');
                    } else if (row.dec_spec_attribute_id) {
                        $('#spec-edit-label').val(row.dec_spec_attribute_id).trigger('change');
                    } else {
                        $('#spec-edit-label').val($('#spec-edit-label option:nth-child(2)').val()).trigger(
                        'change');
                    }

                    $('#spec-edit-value-type').val(row.value_type || 'text');

                    $('.spec-type-option').css({
                        'border-color': '#e5e7eb',
                        'background': '#fff'
                    });
                    $('.spec-type-option[data-val="' + (row.value_type || 'text') + '"]').css({
                        'border-color': '#6366f1',
                        'background': '#f5f3ff'
                    });

                    if (row.value_type === 'richtext') {
                        $('#spec-edit-value-plain').hide();
                        $('#spec-edit-chips-wrap').hide();
                        $('#spec-edit-richtext-wrap').show();
                        if (tinymce.get('spec-edit-value-rich')) {
                            tinymce.get('spec-edit-value-rich').setContent(row.value || '');
                        } else {
                            tinymce.init({
                                selector: '#spec-edit-value-rich',
                                height: 220,
                                plugins: 'lists link charmap textcolor colorpicker nonbreaking',
                                toolbar: 'styles | bold italic underline superscript subscript forecolor backcolor | fontsize lineheight | bullist numlist | link nonbreaking | removeformat',
                                style_formats: [{
                                        title: 'Paragraph (300)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '300'
                                        }
                                    },
                                    {
                                        title: 'Normal (400)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '400'
                                        }
                                    },
                                    {
                                        title: 'Medium (500)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '500'
                                        }
                                    },
                                    {
                                        title: 'Semi Bold (600)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '600'
                                        }
                                    },
                                    {
                                        title: 'Bold (700)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '700'
                                        }
                                    }
                                ],
                                menubar: false,
                                statusbar: false,
                                content_style: 'body { font-family:Inter,sans-serif; font-size:13px; }',
                                setup: function(ed) {
                                    ed.on('change', function() {
                                        tinymce.triggerSave();
                                    });
                                },
                                init_instance_callback: function(ed) {
                                    ed.setContent(row.value || '');
                                }
                            });
                        }
                    } else if (row.value_type === 'chips') {
                        $('#spec-edit-value-plain').hide();
                        $('#spec-edit-richtext-wrap').hide();
                        if (tinymce.get('spec-edit-value-rich')) tinymce.get('spec-edit-value-rich').remove();
                        $('#spec-edit-chips-wrap').show();
                        
                        var chipsVal = row.value || '[]';
                        if (!chipsVal.startsWith('[')) chipsVal = '[]';
                        $('#spec-edit-value-chips').val(chipsVal);
                        renderChipsUI($('#spec-chips-container'), $('#spec-edit-value-chips'));
                    } else {
                        $('#spec-edit-value-plain').show().val(row.value || '');
                        $('#spec-edit-richtext-wrap').hide();
                        $('#spec-edit-chips-wrap').hide();
                        if (tinymce.get('spec-edit-value-rich')) tinymce.get('spec-edit-value-rich').remove();
                    }

                    $('#spec-edit-modal').modal('show');
                }

                // Open edit modal for dimension spec row
                function openDimSpecEdit(row) {
                    $('#dim-spec-edit-id').val(row.id); // id here is dec_spec_attribute_id

                    var matchingOption = $('#dim-spec-edit-label option').filter(function() {
                        return $(this).text() === row.label;
                    });
                    if (matchingOption.length) {
                        $('#dim-spec-edit-label').val(matchingOption.val()).trigger('change');
                    } else if (row.dec_spec_attribute_id) {
                        $('#dim-spec-edit-label').val(row.dec_spec_attribute_id).trigger('change');
                    } else {
                        $('#dim-spec-edit-label').val($('#dim-spec-edit-label option:nth-child(2)').val()).trigger(
                            'change');
                    }

                    // Determine value_type from the first available value, or default to text
                    var vType = 'text';
                    if (row.values && Object.values(row.values).length > 0) {
                        vType = Object.values(row.values)[0].value_type || 'text';
                    }
                    $('#dim-spec-edit-value-type').val(vType);

                    $('.dim-spec-type-option').css({
                        'border-color': '#e5e7eb',
                        'background': '#fff'
                    });
                    $('.dim-spec-type-option[data-val="' + vType + '"]').css({
                        'border-color': '#6366f1',
                        'background': '#f5f3ff'
                    });

                    $('.dim-spec-value-plain').each(function() {
                        var sizeId = $(this).data('size-id');
                        var val = '';
                        if (row.values && row.values[sizeId]) val = row.values[sizeId].value || '';
                        $(this).val(val);
                    });

                    if (vType === 'richtext') {
                        $('.dim-spec-value-plain').hide();
                        $('.dim-spec-chips-wrap').hide();
                        $('.dim-spec-richtext-wrap').show();

                        $('.dim-spec-value-rich').each(function() {
                            var sizeId = $(this).data('size-id');
                            var val = '';
                            if (row.values && row.values[sizeId]) val = row.values[sizeId].value || '';

                            if (tinymce.get($(this).attr('id'))) {
                                tinymce.get($(this).attr('id')).setContent(val);
                            } else {
                                tinymce.init({
                                    selector: '#' + $(this).attr('id'),
                                    height: 180,
                                    plugins: 'lists link charmap textcolor colorpicker nonbreaking',
                                    toolbar: 'styles | bold italic underline superscript subscript forecolor backcolor | fontsize lineheight | bullist numlist | link nonbreaking | removeformat',
                                    style_formats: [{
                                            title: 'Normal (400)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '400'
                                            }
                                        },
                                        {
                                            title: 'Medium (500)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '500'
                                            }
                                        },
                                        {
                                            title: 'Semi Bold (600)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '600'
                                            }
                                        },
                                        {
                                            title: 'Bold (700)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '700'
                                            }
                                        }
                                    ],
                                    menubar: false,
                                    statusbar: false,
                                    content_style: 'body { font-family:Inter,sans-serif; font-size:13px; }',
                                    setup: function(ed) {
                                        ed.on('change', function() {
                                            tinymce.triggerSave();
                                        });
                                    },
                                    init_instance_callback: function(ed) {
                                        ed.setContent(val);
                                    }
                                });
                            }
                        });
                    } else if (vType === 'chips') {
                        $('.dim-spec-value-plain').hide();
                        $('.dim-spec-richtext-wrap').hide();
                        $('.dim-spec-value-rich').each(function() {
                            if (tinymce.get($(this).attr('id'))) tinymce.get($(this).attr('id')).remove();
                        });
                        $('.dim-spec-chips-wrap').show();
                        
                        $('.dim-spec-value-chips').each(function() {
                            var sizeId = $(this).data('size-id');
                            var val = '[]';
                            if (row.values && row.values[sizeId]) val = row.values[sizeId].value || '[]';
                            if (!val.startsWith('[')) val = '[]';
                            $(this).val(val);
                            var $container = $(this).closest('.dim-spec-chips-wrap').find('.dim-spec-chips-container');
                            renderChipsUI($container, $(this));
                        });
                    } else {
                        $('.dim-spec-value-plain').show();
                        $('.dim-spec-richtext-wrap').hide();
                        $('.dim-spec-chips-wrap').hide();
                        $('.dim-spec-value-rich').each(function() {
                            if (tinymce.get($(this).attr('id'))) tinymce.get($(this).attr('id')).remove();
                        });
                    }

                    $('#dim-spec-edit-modal').modal('show');
                }

                // Reinit tinymce when value type changes in modal
                $('#spec-edit-value-type').on('change', function() {
                    var type = $(this).val();
                    if (type === 'richtext') {
                        $('#spec-edit-value-plain').hide();
                        $('#spec-edit-chips-wrap').hide();
                        $('#spec-edit-richtext-wrap').show();
                        var plainVal = $('#spec-edit-value-plain').val();
                        if (!tinymce.get('spec-edit-value-rich')) {
                            tinymce.init({
                                selector: '#spec-edit-value-rich',
                                height: 220,
                                plugins: 'lists link charmap textcolor colorpicker nonbreaking',
                                toolbar: 'styles | bold italic underline superscript subscript forecolor backcolor | fontsize lineheight | bullist numlist | link nonbreaking | removeformat',
                                style_formats: [{
                                        title: 'Normal (400)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '400'
                                        }
                                    },
                                    {
                                        title: 'Medium (500)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '500'
                                        }
                                    },
                                    {
                                        title: 'Semi Bold (600)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '600'
                                        }
                                    },
                                    {
                                        title: 'Bold (700)',
                                        inline: 'span',
                                        styles: {
                                            'font-weight': '700'
                                        }
                                    }
                                ],
                                menubar: false,
                                statusbar: false,
                                content_style: 'body { font-family:Inter,sans-serif; font-size:13px; }',
                                setup: function(ed) {
                                    ed.on('change', function() {
                                        tinymce.triggerSave();
                                    });
                                },
                                init_instance_callback: function(ed) {
                                    ed.setContent(plainVal);
                                }
                            });
                        }
                        }
                    } else if (type === 'chips') {
                        $('#spec-edit-richtext-wrap').hide();
                        $('#spec-edit-value-plain').hide();
                        if (tinymce.get('spec-edit-value-rich')) {
                            tinymce.get('spec-edit-value-rich').remove();
                        }
                        $('#spec-edit-chips-wrap').show();
                        var $hidden = $('#spec-edit-value-chips');
                        var chipsVal = $hidden.val() || '[]';
                        if (!chipsVal.startsWith('[')) chipsVal = '[]';
                        $hidden.val(chipsVal);
                        renderChipsUI($('#spec-chips-container'), $hidden);
                    } else {
                        $('#spec-edit-richtext-wrap').hide();
                        $('#spec-edit-chips-wrap').hide();
                        $('#spec-edit-value-plain').show();
                        if (tinymce.get('spec-edit-value-rich')) {
                            $('#spec-edit-value-plain').val(tinymce.get('spec-edit-value-rich').getContent({
                                format: 'text'
                            }));
                            tinymce.get('spec-edit-value-rich').remove();
                        }
                    }
                });

                // Reinit tinymce when value type changes in DIMENSIONS modal
                $('#dim-spec-edit-value-type').on('change', function() {
                    var type = $(this).val();
                    if (type === 'richtext') {
                        $('.dim-spec-value-plain').hide();
                        $('.dim-spec-chips-wrap').hide();
                        $('.dim-spec-richtext-wrap').show();

                        $('.dim-spec-value-plain').each(function() {
                            var sizeId = $(this).data('size-id');
                            var plainVal = $(this).val();
                            var richId = 'dim-spec-rich-' + sizeId;
                            if (!tinymce.get(richId)) {
                                tinymce.init({
                                    selector: '#' + richId,
                                    height: 180,
                                    plugins: 'lists link charmap textcolor colorpicker nonbreaking',
                                    toolbar: 'styles | bold italic underline superscript subscript forecolor backcolor | fontsize lineheight | bullist numlist | link nonbreaking | removeformat',
                                    style_formats: [{
                                            title: 'Normal (400)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '400'
                                            }
                                        },
                                        {
                                            title: 'Medium (500)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '500'
                                            }
                                        },
                                        {
                                            title: 'Semi Bold (600)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '600'
                                            }
                                        },
                                        {
                                            title: 'Bold (700)',
                                            inline: 'span',
                                            styles: {
                                                'font-weight': '700'
                                            }
                                        }
                                    ],
                                    menubar: false,
                                    statusbar: false,
                                    content_style: 'body { font-family:Inter,sans-serif; font-size:13px; }',
                                    setup: function(ed) {
                                        ed.on('change', function() {
                                            tinymce.triggerSave();
                                        });
                                    },
                                    init_instance_callback: function(ed) {
                                        ed.setContent(plainVal);
                                    }
                                });
                            }
                        });
                    } else if (type === 'chips') {
                        $('.dim-spec-richtext-wrap').hide();
                        $('.dim-spec-value-plain').hide();
                        $('.dim-spec-value-rich').each(function() {
                            if (tinymce.get($(this).attr('id'))) tinymce.get($(this).attr('id')).remove();
                        });
                        $('.dim-spec-chips-wrap').show();
                        $('.dim-spec-value-chips').each(function() {
                            var chipsVal = $(this).val() || '[]';
                            if (!chipsVal.startsWith('[')) chipsVal = '[]';
                            $(this).val(chipsVal);
                            var $container = $(this).closest('.dim-spec-chips-wrap').find('.dim-spec-chips-container');
                            renderChipsUI($container, $(this));
                        });
                    } else {
                        $('.dim-spec-richtext-wrap').hide();
                        $('.dim-spec-chips-wrap').hide();
                        $('.dim-spec-value-plain').show();
                        $('.dim-spec-value-rich').each(function() {
                            var richId = $(this).attr('id');
                            var sizeId = $(this).data('size-id');
                            if (tinymce.get(richId)) {
                                $('.dim-spec-value-plain[data-size-id="' + sizeId + '"]').val(
                                    tinymce.get(richId).getContent({
                                        format: 'text'
                                    }));
                                tinymce.get(richId).remove();
                            }
                        });
                    }
                });

                // Value type selectors
                $('.spec-type-option').on('click', function() {
                    $('.spec-type-option').css({
                        'border-color': '#e5e7eb',
                        'background': '#fff'
                    });
                    $(this).css({
                        'border-color': '#6366f1',
                        'background': '#f5f3ff'
                    });
                    $('#spec-edit-value-type').val($(this).data('val')).trigger('change');
                });

                $('.dim-spec-type-option').on('click', function() {
                    $('.dim-spec-type-option').css({
                        'border-color': '#e5e7eb',
                        'background': '#fff'
                    });
                    $(this).css({
                        'border-color': '#6366f1',
                        'background': '#f5f3ff'
                    });
                    $('#dim-spec-edit-value-type').val($(this).data('val')).trigger('change');
                });

                // Click edit button on row
                $(document).on('click', '.btn-edit-spec', function() {
                    var tr = $(this).closest('tr');
                    var id = tr.data('id');
                    openSpecEdit({
                        id: id,
                        label: tr.attr('data-label') || tr.find('td:nth-child(2)').text().trim(),
                        value_type: tr.data('value-type'),
                        value: tr.data('value') || ''
                    });
                });

                // Click edit button on DIMENSION row
                $(document).on('click', '.btn-edit-dim-spec', function() {
                    var tr = $(this).closest('tr');
                    var id = tr.data('id');
                    var valuesJson = tr.attr('data-values');
                    var values = valuesJson ? JSON.parse(decodeURIComponent(valuesJson)) : {};

                    openDimSpecEdit({
                        id: id,
                        dec_spec_attribute_id: id,
                        label: tr.attr('data-label') || tr.find('td:nth-child(2)').text().trim(),
                        values: values
                    });
                });

                // Save from modal (Basic)
                $('#btn-spec-modal-save').on('click', function() {
                    var id = $('#spec-edit-id').val();
                    var type = $('#spec-edit-value-type').val();
                    var attrId = $('#spec-edit-label').val();
                    var label = $('#spec-edit-label option:selected').text();

                    var value = '';
                    if (type === 'richtext') {
                        value = tinymce.get('spec-edit-value-rich') ? tinymce.get('spec-edit-value-rich').getContent() : '';
                    } else if (type === 'chips') {
                        value = $('#spec-edit-value-chips').val();
                    } else {
                        value = $('#spec-edit-value-plain').val();
                    }

                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');

                    $.ajax({
                        url: '"BLADE"/' + id,
                        method: 'PUT',
                        data: {
                            _token: '"BLADE"',
                            dec_spec_attribute_id: attrId,
                            value_type: type,
                            value: value,
                            section: 'basic_specifications'
                        },
                        success: function(res) {
                            $btn.attr('disabled', false).html('<i class="ft-save"></i> Save');
                            if (res.success) {
                                $('#spec-edit-modal').modal('hide');
                                var tr = $('tr.spec-row[data-id="' + id + '"]');
                                var newTr = $(renderSpecRow(res.row));
                                newTr.data('value', res.row.value || '');
                                tr.replaceWith(newTr);
                                initSpecSortables();
                                toastr.success('Specification updated');
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html('<i class="ft-save"></i> Save');
                        }
                    });
                });

                // Save from modal (Dimensions)
                $('#btn-dim-spec-modal-save').on('click', function() {
                    var id = $('#dim-spec-edit-id').val();
                    var type = $('#dim-spec-edit-value-type').val();
                    var attrId = $('#dim-spec-edit-label').val();
                    var label = $('#dim-spec-edit-label option:selected').text();

                    var values = {};
                    if (type === 'richtext') {
                        $('.dim-spec-value-rich').each(function() {
                            var sizeId = $(this).data('size-id');
                            values[sizeId] = tinymce.get($(this).attr('id')) ? tinymce.get($(this)
                                .attr('id')).getContent() : '';
                        });
                    } else if (type === 'chips') {
                        $('.dim-spec-value-chips').each(function() {
                            var sizeId = $(this).data('size-id');
                            values[sizeId] = $(this).val();
                        });
                    } else {
                        $('.dim-spec-value-plain').each(function() {
                            var sizeId = $(this).data('size-id');
                            values[sizeId] = $(this).val();
                        });
                    }

                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');

                    $.ajax({
                        url: '"BLADE"/' + id,
                        method: 'PUT',
                        data: {
                            _token: '"BLADE"',
                            dec_spec_attribute_id: attrId,
                            value_type: type,
                            values: values,
                            section: 'dimensions'
                        },
                        success: function(res) {
                            $btn.attr('disabled', false).html('<i class="ft-save"></i> Save');
                            if (res.success) {
                                $('#dim-spec-edit-modal').modal('hide');
                                var tr = $('tr.dimension-row[data-id="' + id + '"]');
                                var newTr = $(renderDimSpecRow(res.row));
                                tr.replaceWith(newTr);
                                initSpecSortables();
                                toastr.success('Dimension specification updated');
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html('<i class="ft-save"></i> Save');
                        }
                    });
                });

                $('#spec-edit-modal, #dim-spec-edit-modal').on('hidden.bs.modal', function() {
                    if (tinymce.get('spec-edit-value-rich')) tinymce.get('spec-edit-value-rich').remove();
                    $('.dim-spec-value-rich').each(function() {
                        if (tinymce.get($(this).attr('id'))) tinymce.get($(this).attr('id'))
                        .remove();
                    });
                });

                $(document).on('click',
                    '#spec-edit-modal .close, #spec-edit-modal [data-dismiss="modal"], #dim-spec-edit-modal .close, #dim-spec-edit-modal [data-dismiss="modal"], #spec-copy-modal .close, #spec-copy-modal [data-dismiss="modal"], #spec-save-template-modal .close, #spec-save-template-modal [data-dismiss="modal"], #spec-apply-template-modal .close, #spec-apply-template-modal [data-dismiss="modal"]',
                    function() {
                        var modal = $(this).closest('.modal');
                        if (modal.length) {
                            modal.modal('hide');
                        }
                    });

                // Delete spec row
                $(document).on('click', '.btn-delete-spec', function() {
                    if (!confirm('Are you sure you want to delete this specification?')) return;
                    var id = $(this).data('id');
                    var tr = $(this).closest('tr');
                    var tbody = tr.closest('tbody');
                    $.ajax({
                        url: '"BLADE"/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '"BLADE"',
                            section: 'basic_specifications'
                        },
                        success: function(res) {
                            if (res.success) {
                                tr.fadeOut(function() {
                                    $(this).remove();
                                    if (tbody.find('tr').length === 0) {
                                        $('#no-specs-basic').show();
                                    }
                                });
                                toastr.success(res.message);
                            }
                        }
                    });
                });

                // Delete dimension spec row
                $(document).on('click', '.btn-delete-dim-spec', function() {
                    if (!confirm(
                            'Are you sure you want to delete this dimension specification for all sizes?'))
                        return;
                    var id = $(this).data('id');
                    var tr = $(this).closest('tr');
                    var tbody = tr.closest('tbody');
                    $.ajax({
                        url: '"BLADE"/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '"BLADE"',
                            section: 'dimensions'
                        },
                        success: function(res) {
                            if (res.success) {
                                tr.fadeOut(function() {
                                    $(this).remove();
                                    if (tbody.find('tr').length === 0) {
                                        $('#no-specs-dimensions').show();
                                    }
                                });
                                toastr.success(res.message);
                            }
                        }
                    });
                });

                // Copy Specs
                $('#btn-open-copy-spec').on('click', function() {
                    $('#spec-copy-modal').modal('show');
                });

                $('#btn-spec-copy-confirm').on('click', function() {
                    var sourceId = $('#spec-copy-source').val();
                    if (!sourceId) {
                        toastr.error('Select a source product');
                        return;
                    }

                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Copying...');

                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: {
                            _token: '"BLADE"',
                            source_product_id: sourceId
                        },
                        success: function(res) {
                            $btn.attr('disabled', false).html('<i class="ft-check"></i> Copy');
                            if (res.success) {
                                $('#spec-copy-modal').modal('hide');
                                toastr.success(res.message);
                                loadProductSpecs();
                            } else {
                                toastr.error(res.message);
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html('<i class="ft-check"></i> Copy');
                            toastr.error('Error copying specifications.');
                        }
                    });
                });

                // Save Template
                $('#btn-open-save-template').on('click', function() {
                    $('#spec-template-name').val('');
                    $('#spec-save-template-modal').modal('show');
                });

                $('#btn-spec-save-template-confirm').on('click', function() {
                    var name = $('#spec-template-name').val().trim();
                    if (!name) {
                        toastr.error('Template name is required');
                        return;
                    }

                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');

                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: {
                            _token: '"BLADE"',
                            name: name
                        },
                        success: function(res) {
                            $btn.attr('disabled', false).html('<i class="ft-check"></i> Save');
                            if (res.success) {
                                $('#spec-save-template-modal').modal('hide');
                                toastr.success(res.message);
                            } else {
                                toastr.error(res.message);
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html('<i class="ft-check"></i> Save');
                            toastr.error('Error saving template.');
                        }
                    });
                });

                // Apply Template
                $('#btn-open-apply-template').on('click', function() {
                    $('#spec-template-select').html('<option value="">Loading templates...</option>');
                    $('#spec-apply-template-modal').modal('show');

                    $.get('"BLADE"', function(res) {
                        if (res.success) {
                            var opts = '<option value="">-- Select a template --</option>';
                            res.templates.forEach(function(t) {
                                opts += '<option value="' + t.id + '">' + t.name +
                                    '</option>';
                            });
                            $('#spec-template-select').html(opts);
                        }
                    });
                });

                $('#btn-spec-apply-template-confirm').on('click', function() {
                    var templateId = $('#spec-template-select').val();
                    if (!templateId) {
                        toastr.error('Select a template');
                        return;
                    }

                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Applying...');

                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: {
                            _token: '"BLADE"',
                            template_id: templateId
                        },
                        success: function(res) {
                            $btn.attr('disabled', false).html('<i class="ft-check"></i> Apply');
                            if (res.success) {
                                $('#spec-apply-template-modal').modal('hide');
                                toastr.success(res.message);
                                loadProductSpecs();
                            } else {
                                toastr.error(res.message);
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html('<i class="ft-check"></i> Apply');
                            toastr.error('Error applying template.');
                        }
                    });
                });
            /*BLADE*/

            // ================================================================
            // PHASE 7 - DOWNLOADS TAB
            // ================================================================

            /*BLADE*/ (isset($product))
                $('.btn-replace-download').on('click', function() {
                    var type = $(this).data('type');
                    var idPrefix = type === 'installation_guide' ? 'installation-guide' :
                        'care-instructions';
                    $('#' + idPrefix + '-display').hide();
                    $('#' + idPrefix + '-upload').show();
                });

                $('.form-download-upload').on('submit', function(e) {
                    e.preventDefault();
                    var type = $(this).data('type');
                    var idPrefix = type === 'installation_guide' ? 'installation-guide' :
                        'care-instructions';
                    var formData = new FormData(this);
                    formData.append('_token', '"BLADE"');
                    formData.append('type', type);

                    var $btn = $(this).find('button[type="submit"]');
                    var originalBtn = $btn.html();
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Uploading...');

                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            $btn.attr('disabled', false).html(originalBtn);
                            if (res.success) {
                                $('#' + idPrefix + '-filename').text(res.filename);
                                $('#' + idPrefix + '-link').attr('href',
                                    '"BLADE"/' +
                                    res.filename);
                                $('#' + idPrefix + '-upload').hide();
                                $('#' + idPrefix + '-display').show();
                                toastr.success('File uploaded successfully');
                            } else {
                                toastr.error(res.message || 'Error uploading file');
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html(originalBtn);
                            toastr.error('Error uploading file');
                        }
                    });
                });

                $('.btn-remove-download').on('click', function() {
                    var type = $(this).data('type');
                    var idPrefix = type === 'installation_guide' ? 'installation-guide' :
                        'care-instructions';

                    var $btn = $(this);
                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i>');

                    $.ajax({
                        url: '"BLADE"',
                        method: 'DELETE',
                        data: {
                            _token: '"BLADE"',
                            type: type
                        },
                        success: function(res) {
                            $btn.attr('disabled', false).html('<i class="ft-trash-2"></i>');
                            if (res.success) {
                                $('#' + idPrefix + '-display').hide();
                                $('#' + idPrefix + '-upload').show();
                                $('#' + idPrefix + '-upload form')[0].reset();
                                toastr.success('File removed successfully');
                            }
                        }
                    });
                });
            /*BLADE*/

            // ================================================================
            // PHASE 8 - RELATED PRODUCTS TAB
            // ================================================================

            /*BLADE*/ (isset($product))
                // Move modal to body
                $('#related-search-modal').appendTo('body');

                // Explicit close handler for related search modal
                $(document).on('click',
                    '#related-search-modal .close, #related-search-modal [data-dismiss="modal"]',
                    function() {
                        $('#related-search-modal').modal('hide');
                    });

                var relatedSearchTimer;

                function renderRelatedRow(item) {
                    var prod = item.related_product || {};
                    var image = prod.featured_image ? '"BLADE"/' + prod
                        .featured_image : '';
                    return '<tr data-id="' + item.id + '" style="border-bottom:1px solid #f3f4f6;">' +
                        '<td style="padding:10px 8px; vertical-align:middle; width:28px;"><i class="ft-menu handle text-muted" style="cursor:move; font-size:14px;"></i></td>' +
                        '<td style="padding:10px 12px; vertical-align:middle;">' +
                        '<div class="d-flex align-items-center">' +
                        '<div style="width:36px; height:36px; border-radius:6px; border:1px solid #eee; background:url(\'' +
                        image + '\') center/cover; margin-right:12px; flex-shrink:0;"></div>' +
                        '<div>' +
                        '<div style="font-weight:600; color:#374151;">' + (prod.name || 'Unknown') + '</div>' +
                        '</div>' +
                        '</div>' +
                        '</td>' +
                        '<td style="padding:10px 8px; vertical-align:middle; text-align:right; white-space:nowrap;">' +
                        '<button type="button" class="btn btn-sm btn-delete-related p-1" data-id="' + item.id +
                        '" style="color:#ef4444; background:none; border:none; font-size:14px;"><i class="ft-trash-2"></i></button>' +
                        '</td>' +
                        '</tr>';
                }

                function initRelatedSortables() {
                    $('.related-tbody').each(function() {
                        if (this._sortable) this._sortable.destroy();
                        this._sortable = new Sortable(this, {
                            handle: '.handle',
                            animation: 150,
                            onEnd: function(evt) {
                                var tbody = $(evt.item).closest('tbody');
                                var orders = {};
                                tbody.find('tr').each(function(i) {
                                    orders[$(this).data('id')] = i + 1;
                                });
                                $.ajax({
                                    url: '"BLADE"',
                                    method: 'POST',
                                    data: {
                                        _token: '"BLADE"',
                                        orders: orders
                                    }
                                });
                            }
                        });
                    });
                }

                function loadRelatedProducts() {
                    $.ajax({
                        url: '"BLADE"',
                        method: 'GET',
                        cache: false,
                        success: function(res) {
                            if (res.success) {

                                // Render Manual
                                var mBody = $('.related-tbody[data-type="manual"]').empty();
                                if (res.manual.length > 0) {
                                    $('#no-related-manual').hide();
                                    res.manual.forEach(function(item) {
                                        mBody.append(renderRelatedRow(item));
                                    });
                                } else {
                                    $('#no-related-manual').show();
                                }

                                initRelatedSortables();
                            }
                        }
                    });
                }

                // Tab clicked -> load
                $('a[href="#related"]').on('click', function() {
                    if (!$(this).hasClass('disabled')) {
                        loadRelatedProducts();
                    }
                });



                // Open Search Modal
                $('.btn-add-related').on('click', function() {
                    var type = $(this).data('type');
                    $('#related-search-type').val(type);
                    $('#related-search-type-label').text('Related');
                    $('#related-search-input').val('');
                    $('#related-search-results').empty().hide();
                    $('#related-search-modal').modal('show');
                    setTimeout(function() {
                        $('#related-search-input').focus();
                    }, 300);
                });

                // Search input typing
                $('#related-search-input').on('keyup', function() {
                    var q = $(this).val().trim();
                    clearTimeout(relatedSearchTimer);

                    if (q.length < 2) {
                        $('#related-search-results').empty().hide();
                        return;
                    }

                    relatedSearchTimer = setTimeout(function() {
                        $('#related-search-results').empty().hide();
                        $('#related-search-loading').show();

                        $.ajax({
                            url: '"BLADE"',
                            method: 'GET',
                            data: {
                                q: q,
                                exclude_id: "BLADE"
                            },
                            cache: false,
                            success: function(res) {
                                $('#related-search-loading').hide();
                                var $res = $('#related-search-results').empty();
                                if (res.results && res.results.length > 0) {
                                    res.results.forEach(function(r) {
                                        var imgHtml = r.image ?
                                            '<div style="width:36px; height:36px; border-radius:6px; border:1px solid #eee; background:url(\'' +
                                            r.image +
                                            '\') center/cover; margin-right:12px; flex-shrink:0;"></div>' :
                                            '<div style="width:36px; height:36px; border-radius:6px; border:1px solid #eee; background:#f9fafb; margin-right:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center;"><i class="ft-image text-muted"></i></div>';
                                        var skuHtml = r.sku ?
                                            '<div style="font-size:11px; color:#6b7280;">SKU: ' +
                                            r.sku + '</div>' : '';
                                        $res.append(
                                            '<div class="search-result-item d-flex align-items-center justify-content-between" style="padding:10px 16px; border-bottom:1px solid #f3f4f6;">' +
                                            '<div class="d-flex align-items-center">' +
                                            imgHtml +
                                            '<div><div style="font-weight:600; font-size:13px; color:#374151;">' +
                                            r.name + '</div>' + skuHtml +
                                            '</div></div>' +
                                            '<button type="button" class="btn btn-sm btn-outline-primary btn-attach-related p-1 px-2" data-id="' +
                                            r.id +
                                            '" style="font-size:11px; font-weight:600;"><i class="ft-plus"></i> Add</button>' +
                                            '</div>'
                                        );
                                    });
                                    $res.show();
                                } else {
                                    $res.html(
                                        '<div class="p-3 text-center text-muted" style="font-size:13px;">No products found.</div>'
                                        ).show();
                                }
                            }
                        });
                    }, 400);
                });

                // Attach product
                $(document).on('click', '.btn-attach-related', function() {
                    var $btn = $(this);
                    var relatedId = $btn.data('id');
                    var type = $('#related-search-type').val();

                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i>');

                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: {
                            _token: '"BLADE"',
                            related_product_id: relatedId,
                            type: type
                        },
                        success: function(res) {
                            if (res.success) {
                                var tbody = $('.related-tbody[data-type="' + type + '"]');
                                var noMsg = $('#no-related-' + type);
                                noMsg.hide();
                                tbody.append(renderRelatedRow(res.item));
                                initRelatedSortables();
                                toastr.success(res.message);
                                $btn.removeClass('btn-outline-primary').addClass('btn-success')
                                    .html('<i class="ft-check"></i> Added');
                            } else {
                                $btn.attr('disabled', false).html(
                                '<i class="ft-plus"></i> Add');
                                toastr.error(res.message);
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html('<i class="ft-plus"></i> Add');
                            toastr.error('Error adding product.');
                        }
                    });
                });

                // Detach product
                $(document).on('click', '.btn-delete-related', function() {
                    var id = $(this).data('id');
                    var tr = $(this).closest('tr');
                    var tbody = tr.closest('tbody');
                    var type = tbody.data('type');

                    $.ajax({
                        url: '"BLADE"/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '"BLADE"'
                        },
                        success: function(res) {
                            if (res.success) {
                                tr.fadeOut(function() {
                                    $(this).remove();
                                    if (tbody.find('tr').length === 0) {
                                        $('#no-related-' + type).show();
                                    }
                                });
                            }
                        }
                    });
                });
            /*BLADE*/

            // ================================================================
            // PHASE 9 - SEO & SETTINGS
            // ================================================================

            /*BLADE*/ (isset($product))
                function updateSeoCounters() {
                    var title = $('#meta_title').val() || '';
                    var desc = $('#meta_description').val() || '';

                    $('#meta-title-counter').text(title.length + ' / 60').css('color', title.length > 60 ?
                        '#ef4444' : '');
                    $('#meta-desc-counter').text(desc.length + ' / 160').css('color', desc.length > 160 ?
                        '#ef4444' : '');

                    $('#seo-preview-title').text(title || '"BLADE"');
                    $('#seo-preview-desc').text(desc || 'No description provided.');
                }

                $('#meta_title, #meta_description').on('keyup change', updateSeoCounters);
                updateSeoCounters(); // init

                $('#form-seo').on('submit', function(e) {
                    e.preventDefault();
                    var $btn = $('#btn-save-seo');
                    var originalBtn = $btn.html();

                    $btn.attr('disabled', true).html('<i class="ft-loader spinner"></i> Saving...');

                    $.ajax({
                        url: '"BLADE"',
                        method: 'POST',
                        data: $(this).serialize() + '&_token="BLADE"',
                        success: function(res) {
                            $btn.attr('disabled', false).html(originalBtn);
                            if (res.success) {
                                toastr.success(res.message);
                            } else {
                                toastr.error('Error saving SEO settings');
                            }
                        },
                        error: function() {
                            $btn.attr('disabled', false).html(originalBtn);
                            toastr.error('Server Error');
                        }
                    });
                });

                $('#btn-duplicate-product').on('click', function() {
                    if (confirm(
                            'Are you sure you want to duplicate this product? This will clone the product, colors, sizes, and specifications.'
                            )) {
                        var $btn = $(this);
                        var originalBtn = $btn.html();
                        $btn.attr('disabled', true).html(
                        '<i class="ft-loader spinner"></i> Duplicating...');

                        $.ajax({
                            url: '"BLADE"',
                            method: 'POST',
                            data: {
                                _token: '"BLADE"'
                            },
                            success: function(res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    window.location.href = res.redirect;
                                } else {
                                    $btn.attr('disabled', false).html(originalBtn);
                                    toastr.error('Error duplicating product');
                                }
                            },
                            error: function() {
                                $btn.attr('disabled', false).html(originalBtn);
                                toastr.error('Server Error');
                            }
                        });
                    }
                });
            /*BLADE*/

            // Initialize Select2 for Specification Attribute Dropdown
            if ($('#spec-edit-label').length) {
                $('#spec-edit-label').select2({
                    dropdownParent: $('#spec-edit-modal'),
                    placeholder: "-- Select Attribute --",
                    allowClear: true,
                    width: '100%'
                });
            }
            
            // ================================================================
            // CHIPS LOGIC
            // ================================================================
            window.renderChipsUI = function($container, $hiddenInput) {
                var chips = [];
                try {
                    chips = JSON.parse($hiddenInput.val() || '[]');
                } catch(e) {}
                $container.empty();
                chips.forEach(function(chip, index) {
                    var codeHtml = chip.code ? '<sup class="text-muted ml-1" style="font-size:10px;">' + chip.code + '</sup>' : '';
                    $container.append('<div class="badge border d-flex align-items-center" style="font-size:13px; padding:6px 10px; background:#fff; color:#374151; border-color:#e5e7eb;">' +
                        '<span class="mr-2 font-weight-bold">' + chip.value + codeHtml + '</span>' +
                        '<i class="ft-x text-danger cursor-pointer btn-remove-chip" data-index="' + index + '"></i>' +
                    '</div>');
                });
            };

            $(document).on('click', '.btn-remove-chip', function() {
                var $wrap = $(this).closest('.dim-spec-chips-wrap, #spec-edit-chips-wrap');
                var $hidden = $wrap.find('input[type="hidden"]');
                var $container = $wrap.find('.dim-spec-chips-container, #spec-chips-container');
                var idx = $(this).data('index');
                
                var chips = [];
                try { chips = JSON.parse($hidden.val() || '[]'); } catch(e) {}
                chips.splice(idx, 1);
                $hidden.val(JSON.stringify(chips));
                window.renderChipsUI($container, $hidden);
            });

            $('#btn-add-spec-chip').on('click', function() {
                var val = $('#spec-chip-value').val().trim();
                var code = $('#spec-chip-code').val().trim();
                if (!val) {
                    toastr.error('Value is required');
                    return;
                }
                var $hidden = $('#spec-edit-value-chips');
                var chips = [];
                try { chips = JSON.parse($hidden.val() || '[]'); } catch(e) {}
                chips.push({ value: val, code: code });
                $hidden.val(JSON.stringify(chips));
                window.renderChipsUI($('#spec-chips-container'), $hidden);
                $('#spec-chip-value').val('');
                $('#spec-chip-code').val('');
            });

            $('.btn-add-dim-spec-chip').on('click', function() {
                var sizeId = $(this).data('size-id');
                var val = $('.dim-spec-chip-value[data-size-id="' + sizeId + '"]').val().trim();
                var code = $('.dim-spec-chip-code[data-size-id="' + sizeId + '"]').val().trim();
                if (!val) {
                    toastr.error('Value is required');
                    return;
                }
                var $hidden = $('.dim-spec-value-chips[data-size-id="' + sizeId + '"]');
                var chips = [];
                try { chips = JSON.parse($hidden.val() || '[]'); } catch(e) {}
                chips.push({ value: val, code: code });
                $hidden.val(JSON.stringify(chips));
                window.renderChipsUI($('.dim-spec-chips-container[data-size-id="' + sizeId + '"]'), $hidden);
                $('.dim-spec-chip-value[data-size-id="' + sizeId + '"]').val('');
                $('.dim-spec-chip-code[data-size-id="' + sizeId + '"]').val('');
            });

        }); // end document.ready

        // ================================================================
        // IMAGE PICKER PREVIEW (global, outside ready)
        // ================================================================
        function previewPickerImage(input) {
            if (input.classList.contains('image-picker-input')) {
                var container = input.closest('.image-picker-container');
                var preview = container.querySelector('.image-picker-preview');
                var placeholder = container.querySelector('.picker-placeholder');
                var img = preview.querySelector('img');
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        placeholder.style.display = 'none';
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.style.display = 'none';
                    placeholder.style.display = 'block';
                    img.src = '';
                }
            } else {
                var previewDiv = input.nextElementSibling;
                var img = previewDiv.querySelector('img');
                var placeholder = previewDiv.querySelector('span');
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        if (placeholder) placeholder.style.display = 'none';
                        img.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    img.style.display = 'none';
                    if (placeholder) placeholder.style.display = 'block';
                    img.src = '';
                }
            }
        }
    