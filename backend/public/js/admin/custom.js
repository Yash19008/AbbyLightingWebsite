var editnoOfVarientImages = 0;
var editnoOfIesFiles = 0;

$(document).ready(function () {
    $('.select2').select2();
    /*input style */
    $(document).on('click', '.file-input-browse', function () {
        var file = $(this).parent().parent().parent().find('.file-input');
        file.trigger('click');
    });
    $(document).on('change', '.file-input', function () {
        readURL(this);
        $(this).closest('.input-group').find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var fr = new FileReader;
            fr.onload = function (e) {
                var data = fr.result;
                closestImg = $(input).closest('.input-group');
                closestImg = closestImg.find('.file-input-img-span img');
                var node = closestImg.attr('src', e.target.result);
                var image = new Image();
                image.src = data;
                image.onload = function () {
                    /*EXIF.getData(image, function() {
                        var orientation = EXIF.getTag(this, "Orientation");
                        switch (orientation) {
                            case 3:
                                node.css('transform', 'rotate(180deg)');
                                break;
                            case 6:
                                node.css('transform', 'rotate(90deg)');
                                break;
                            case 8:
                                node.css('transform', 'rotate(-90deg)');
                                break;
                        }
                    });*/
                };
                $('.confomation_box').show();
                $('.edit-img .change-link').hide();
            };
            fr.readAsDataURL(input.files[0]);
        }
    }
    // $('select[name=state_id]').change(function() {
    //     if($(this).val() !=''){
    //         var url = '{{ url('states') }}/' + $(this).val() + '/cities/';
    //         $.get(url, function(data) {
    //             var select = $('form select[name= city_id]');
    //             select.empty();

    //             $.each(data,function(key, value) {
    //                 select.append('<option value=' + value.id + '>' + value.city + '</option>');
    //             });
    //         });
    //     }else{
    //         // var select = $('form select[name= city_id]');
    //         // select.empty();
    //         // select.append($("<option value=''>City</option>"));
    //         var url = '{{ url('city') }}/cities/';
    //         $.get(url, function(data) {
    //             var select = $('form select[name= city_id]');
    //             select.empty();
    //             select.append('<option value="">City</option>');
    //             $.each(data,function(key, value) {
    //                 select.append('<option value=' + value.id + '>' + value.city + '</option>');
    //             });
    //         });
    //     }

    // });


    var timer;
    //COMMON SWITCH ENABLE/DISABLE
    $(document).on('click', '.switch', function () {
        window.clearTimeout(timer);
        ele = $(this);
        id = $(this).closest('tr').attr('id').replace('data-', '');
        checked = $(ele).find('.switch-radio').removeAttr("checked");
        form_data = new FormData();
        form_data.append('id', id);
        form_data.append('tbl', $("#hdn").val());
        if ($(this).data('col')) {
            form_data.append('col', $(this).data('col'));
        }
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + '/status',
            success: function (obj) {
                if (obj.code == 1) {
                    if ($(ele).hasClass('on')) {
                        $(ele).removeClass('on');
                    } else {
                        $(ele).addClass('on');
                    }
                }
                else {
                    errormsg(obj.message, 5000);
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
        });
    });
    $(document).on('click', '.navbar-toggle', function (e) {
        e.stopPropagation();
        $(document).find('body').addClass('menu-open');
    })

    $('body,html').click(function (e) {
        $(document).find('body').removeClass('menu-open');
    });
    // $('#frm_profile').submit(function(e){
    //     alert()
    //     e.preventDefault();
    //     $('.qualification-sub-div').each(function(index,val){
    //         if($(this).closest().find('#title').val() =='' || $(this).closest().find('#qualification').val() == ''){
    //             alert();
    //         }
    //     })
    // })

    // COMMON DELETE
    var currDeleteRowId = '';
    var currDeleteRowThat = '';
    $(document).on('click', '.delete', function () {
        currDeleteRowId = $(this).closest('tr').attr('id').replace('data-', '');
        currDeleteRowThat = $(this);
        var module = $(this).data('module');
        $('#delete_popup').modal('show');
        $('.modal-body').html("<p>Are you sure you want to delete this " + module + "? You cannot recover it back.</p>");
    });
    $('#delete_popup .confirm').on("click", function () {
        var tbl = $("#hdn").val();
        console.log(tbl);
        console.log(currDeleteRowId);
        form_data = new FormData();
        form_data.append('id', currDeleteRowId);
        form_data.append('tbl', tbl);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + '/delete',
            success: function (obj) {
                if (obj.code == 1) {
                    if (currDeleteRowThat.closest('.data').hasClass('not-read')) {
                        var count = parseInt($('#contact').text() - 1);
                        if (count > 0) {
                            $('#contact').text(count);
                        }
                        else {
                            $('#contact').remove();
                        }
                    }
                    currDeleteRowThat.closest('.data').remove();
                }
                else {
                    errormsg(obj.message, 5000);
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
        });
        $('#delete_popup').modal('hide');
    });

    var currDeleteRowIdId = '';
    var currDeleteRowThatThat = '';
    $(document).on('click', '.delete-variant', function () {
        currDeleteRowIdId = $(this).attr('data-variant_id');
        currDeleteRowThatThat = $(this);
        var module = $(this).data('module');
        $('#delete_variant_popup').modal('show');
        $('#delete_variant_popup').find('.modal-body').html("<p>Are you sure you want to delete this Variant? You cannot recover it back.</p>");
    });
    $('#delete_variant_popup .confirm').on("click", function () {
        var tbl = $("#hdn").val();

        form_data = new FormData();
        form_data.append('id', currDeleteRowIdId);
        form_data.append('tbl', tbl);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + '/delete-variant',
            success: function (obj) {
                if (obj.code == 1) {
                    currDeleteRowThatThat.closest('.variant-wrapper').remove();
                }
                else {
                    errormsg(obj.message, 5000);
                }
            },
            error: function (obj) {
                errormsg(csrf_error);
            },
        });
        $('#delete_variant_popup').modal('hide');
    });
    var variant_id;




    $(document).on('click', '.edit-variant', function () {
        $('#edit_variant_popup').modal('show');
        var form = $('#edit_variant')[0];
        var formData = new FormData(form);
        variant_id = $(this).attr('data-variant_id');
        formData.append('id', variant_id);
        if ($('#edit_variant').valid()) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: 'post',
                url: siteUrl + '/edit-variant/' + variant_id,
                success: function (obj) {
                    console.log(obj);
                    if (obj.code == 1) {
                        $('#variant_name').val(obj.product.variant_name);
                        $('#slug').val(obj.product.slug);
                        $('#led_fitted').val(obj.product.led_fitted);
                        $('#co_related_color').val(obj.product.co_related_color);
                        $('#co_related_color_code').val(obj.product.co_related_color_code);
                        $('#lumens').val(obj.product.lumens);
                        $('#efficacy').val(obj.product.efficacy);
                        $('#beam_angle').val(obj.product.beam_angle);
                        $('#beam_angle_code').val(obj.product.beam_angle_code);
                        $('#led_power_watts').val(obj.product.led_power_watts);
                        $('#system_power_watts').val(obj.product.system_power_watts);
                        $('#operating_voltage').val(obj.product.operating_voltage);
                        $('#power_factor').val(obj.product.power_factor);
                        $('#variant_name').val(obj.product.variant_name);
                        if (obj.product.line_diagram) {
                            $('.line_diagram').val(obj.product.line_diagram);
                        }
                        if (obj.product.custom_specsheet) {
                            $('.custom_specsheet').val(obj.product.custom_specsheet);
                        }
                        $('#deleted_vectorImages').val('');
                        $("#edit-varientImages-table tbody").html(`<tr id="edit-noVarientImagesRow">
                                <td colspan="2">
                                    <p style="text-align: center;">No Photometry Files</p>
                                </td>
                            </tr>`);
                        if (obj.product.vectorImages.length > 0) {
                            editnoOfVarientImages = obj.product.vectorImages.length;
                            $(`#edit-noVarientImagesRow`).remove();
                            obj.product.vectorImages.forEach((vectorImage, index) => {
                                $("#edit-varientImages-table tbody").append(`
                                    <tr id="edit-varientImage-table-${index}">
                                        <td> ${vectorImage.file}</td>
                                        <td class="text-center"><i class="fa fa-trash" onclick="editremoveVarientImage(${vectorImage.id}, ${index})" style="cursor: pointer; font-size: 22px;"></i></td>
                                    </tr>`);
                            });
                        }

                        if (editnoOfVarientImages <= 3) {
                            $("#edit-business-varientImage-label").after(`<input id="edit-varientImage-${editnoOfVarientImages}" class="edit-businessVarientImage" type="file" name="varientImages[${editnoOfVarientImages}][item]" accept="image/*" style="background-color: transparent !important;">`);
                        }

                        $('#deleted_ies').val('');
                        $("#edit-iesFiles-table tbody").html(`<tr id="edit-noIesFilesRow">
                                <td colspan="2">
                                    <p style="text-align: center;">No Photometry Files</p>
                                </td>
                            </tr>`);
                        if (obj.product.iesFiles.length > 0) {
                            editnoOfIesFiles = obj.product.iesFiles.length;
                            $(`#edit-noIesFilesRow`).remove();
                            obj.product.iesFiles.forEach((iesFile, index) => {
                                $("#edit-iesFiles-table tbody").append(`
                                    <tr id="edit-iesFiles-table-${index}">
                                        <td> ${iesFile.file_name}</td>
                                        <td class="text-center"><i class="fa fa-trash" onclick="editremoveIesFile(${iesFile.id}, ${index})" style="cursor: pointer; font-size: 22px;"></i></td>
                                    </tr>`);
                            });
                        }

                        $("#edit-business-iesFile-label").after(`<input id="edit-iesFile-${editnoOfIesFiles}" class="edit-businessIesFile w-100" type="file" name="iesFiles[${editnoOfIesFiles}][item]" accept="image/*" style="background-color: transparent !important;">`);

                    }
                    else {
                        $("#error_verify").text(e.message);
                        $("#error_verify").show();
                    }
                },
                error: function (obj) {
                    errormsg(csrf_error);
                },
            });
        }

    });

    $('body').on("change", '.edit-businessVarientImage', function () {
        if ($(`#edit-noVarientImagesRow`).length) {
            $(`#edit-noVarientImagesRow`).remove();
        }

        var fileName = $(this).val();
        let prevElem = $("#edit-varientImage-" + editnoOfVarientImages);
        $(prevElem).addClass(`hide`);

        fileName = getFile(fileName);
        $("#edit-varientImages-table tbody").append(`
            <tr id="edit-varientImage-table-${editnoOfVarientImages}">
                <td> ${fileName}</td>
                <td class="text-center"><i class="fa fa-trash" onclick="editremoveVarientImage(null, ${editnoOfVarientImages})" style="cursor: pointer; font-size: 22px;"></i></td>
            </tr>`);

        editnoOfVarientImages++;
        if (editnoOfVarientImages <= 3) {
            $("#edit-business-varientImage-label").after(`<input id="edit-varientImage-${editnoOfVarientImages}" class="edit-businessVarientImage  w-100" type="file" name="varientImages[${editnoOfVarientImages}][item]" accept="image/*" style="background-color: transparent !important;">`);
        }
    });

    $('body').on("change", '.edit-businessIesFile', function () {
        if ($(`#edit-noIesFilesRow`).length) {
            $(`#edit-noIesFilesRow`).remove();
        }

        var fileName = $(this).val();
        let prevElem = $("#edit-iesFile-" + editnoOfIesFiles);
        $(prevElem).addClass(`hide`);

        fileName = getFile(fileName);
        $("#edit-iesFiles-table tbody").append(`
            <tr id="edit-iesFiles-table-${editnoOfIesFiles}">
                <td> ${fileName}</td>
                <td class="text-center"><i class="fa fa-trash" onclick="editremoveIesFile(null, ${editnoOfIesFiles})" style="cursor: pointer; font-size: 22px;"></i></td>
            </tr>`);

        editnoOfIesFiles++;
        $("#edit-business-iesFile-label").after(`<input id="edit-iesFile-${editnoOfIesFiles}" class="edit-businessIesFile  w-100" type="file" name="iesFiles[${editnoOfIesFiles}][item]" accept="*/*" style="background-color: transparent !important;">`);
    });


    $(document).on('click', '.update-variant', function () {
        var form = $('#edit_variant')[0];
        var formData = new FormData(form);
        formData.append('id', variant_id);
        if ($('#edit_variant').valid()) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: 'post',
                url: siteUrl + '/update-variant/' + variant_id,
                success: function (obj) {
                    if (obj.code == 1) {
                        location.reload();
                    }

                },
                error: function (obj) {
                    if (obj.status == 422) {
                        alert(obj.responseJSON.message);
                    }
                    errormsg(csrf_error);
                },
            });
        }

    });

    // var timer;
    // //COMMON SWITCH ENABLE/DISABLE
    // $(document).on('click', '.switch', function() {
    //     window.clearTimeout(timer);

    //     ele = $(this);
    //     var switchCheck;
    //     // if(('.switch').checked){
    //     //     switchCheck = '1';
    //     //     alert();
    //     // }else{
    //     //      switchCheck = '0';
    //     // }
    //     // console.log(switchCheck);return 
    //     id = $(this).closest('tr').attr('id').replace('data-', '');
    //     checked = $(ele).find('.switch-radio').removeAttr("checked");
    //     form_data = new FormData();
    //     form_data.append('id', id);
    //     form_data.append('tbl', $("#hdn").val());
    //     $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    //     $.ajax({
    //         dataType: 'json',
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         data: form_data,
    //         type: 'post',
    //         url: siteUrl + '/status',
    //         success: function(obj) {
    //             if (obj.code == 1) {
    //                 if ($(ele).hasClass('on')) {
    //                     $(ele).removeClass('on');
    //                 } else {
    //                     $(ele).addClass('on');
    //                 }
    //             }
    //             else {
    //                 errormsg(obj.message, 5000);
    //             }
    //         },
    //         error: function(obj) {
    //             errormsg(csrf_error);
    //         },
    //     });
    // });
});


function editremoveVarientImage(id, index) {
    if (id) {
        const ids = $('#deleted_vectorImages').val() ? $('#deleted_vectorImages').val() + ',' + id : id;
        $('#deleted_vectorImages').val(ids);
    }
    $(`#edit-varientImage-table-${index}`).remove();
    editnoOfVarientImages--;
    if (editnoOfVarientImages == 3) {
        $("#edit-business-varientImage-label").after(`<input id="edit-varientImage-${editnoOfVarientImages}" class="edit-businessVarientImage" type="file" name="varientImages[${editnoOfVarientImages}][item]" accept="image/*" style="background-color: transparent !important;">`);
    }
}

function editremoveIesFile(id, index) {
    if (id) {
        const ids = $('#deleted_ies').val() ? $('#deleted_ies').val() + ',' + id : id;
        $('#deleted_ies').val(ids);
    }
    $(`#edit-iesFiles-table-${index}`).remove();
}

function getFile(filePath) {
    return filePath.substr(filePath.lastIndexOf('\\') + 1).split('.')[0];
}

function uploadFile(files, path) {
    return new Rx.Observable(observer => {
        const form_data = new FormData();
        for (var i = 0; i < files.length; i++) {
            form_data.append("uploadedImages[" + i + "]", files[i]);
        }
        form_data.append("path", path);
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            url: siteUrl + '/upload-file',
            success: function (obj) {
                observer.next(obj);
                observer.complete();
            },
            error: function (obj) {
                errormsg(csrf_error);
                observer.error(obj);
            },
        });
    });
}