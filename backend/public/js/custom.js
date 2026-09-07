$(document).ready(function () {
    debugger;
    /*input style */
    $(document).on('click', '.file-input-browse', function () {
        var file = $(this).parent().parent().parent().find('.file-input');
        file.trigger('click');
    });
    $(document).on('change', '.file-input', function () {
        readURL(this);
        $(this).closest('.input-group').find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });

    $(document).on('click', '.upload-doc-btn', function () {
        var file = $(this).closest('.plan_details').find('.file-input').val();
        var work_att_id = $(this).closest('.plan_details').find('.work_id').val();
        var form = $('#frm_upload_doc')[0]; // You need to use standard javascript object here
        if ($(this).closest('.plan_details').find('input[type=file]').val() == '' || $(this).closest('.plan_details').find('input[type=file]').val() == undefined) {
            $(this).closest('.plan_details').find('.input-group').addClass('has-error');
            $(this).closest('.plan_details').find('.input-group').after('<span for="file_' + work_att_id + '" generated="true" class="help-block">This field is required.</span>')
            return false;
        }

        var formData = new FormData(form);
        formData.append('file', file);
        formData.append('work_att_id', work_att_id);
        if ($('#frm_upload_doc').valid()) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                type: 'post',
                url: baseUrl + '/upload-docs/insert',
                success: function (obj) {
                    if (obj.code == 1) {
                        location.reload();
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
    $(document).on('click', '.doc-delete-btn', function () {
        $('#delete_doc_popup').modal('show');
    })
    // COMMON DELETE
    var currDeleteRowId = '';
    var currDeleteRowThat = '';
    $(document).on('click', '.doc-delete-btn', function () {
        currDeleteRowId = $(this).closest('.plan_details').attr('data-id');
        currDeleteRowThat = $(this);
        var module = $(this).data('module');
        $('#delete_doc_popup').modal('show');
        $('#delete_doc_popup').find('.modal-body').html("<p>Are you sure you want to delete this document ? You cannot recover it back.</p>");
    });
    $('#delete_doc_popup .delete-confirm').on("click", function () {
        var tbl = $("#hdn").val();
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
            url: baseUrl + '/upload-docs/delete/' + currDeleteRowId,
            success: function (obj) {
                if (obj.code == 1) {

                    location.reload();
                }
                else {
                    errormsg(obj.message, 5000);
                }
            },
            error: function (obj) {
                if(obj.status == 422){
                    alert(obj.responseJSON.message);
                }
                errormsg(csrf_error);
            },
        });
        $('#delete_popup').modal('hide');
    });

});
