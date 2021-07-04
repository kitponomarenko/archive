$(document).on('change', '.input_file>input', function () {
    upload_file($(this));
});

$(document).on('click', '[name="remove_file"]', function () {
    let file = $(this).parent('div');
    remove_file($(file));
});

async function upload_file(input) {
    let input_id = $(input).attr('id');
    let type = $(input).data('type');
    let form = $(input).parents('form')[0];
    let count = $('#' + input_id + '_list>div').length;
    let form_data = new FormData(form);
    form_data.append('type', type);
    form_data.append('count', count);
    $('#' + input_id + '_input').hide(200);
    $('#' + input_id + '_progress').show(200);
    let data = await $.ajax({
        type: "POST",
        url: "php/archive/reciever_file.php",
        processData: false,
        contentType: false,
        data: form_data,
        dataType: 'json',
        xhr: function () {
            let xhr = $.ajaxSettings.xhr();
            xhr.upload.addEventListener('progress', function (event) {
                if (event.lengthComputable) {
                    let progress_percent = Math.ceil(event.loaded / event.total * 100);
                    $('#' + input_id + '_progress').val(progress_percent);
                }
            }, false);
            return xhr;
        },
        success: function () {}
    });

    $('#' + input_id + '_list').parent('.file_list').children('.error_bar').remove();
    $('#' + input_id + '_progress').hide(200);
    $(input).val('');
    if (data['valid'] === 1) {
        await $.each(data['file'], function (key, value) {
            if ($('#' + input_id + '_files').val() === '') {
                $('#' + input_id + '_files').val(value);
            } else {
                $('#' + input_id + '_files').val($('#' + input_id + '_files').val() + ',' + value);
            }
        });
        if (data['output'] !== '') {
            await $('#' + input_id + '_list').append(data['output']);
        }
        $('#' + input_id + '_files').trigger('change');
    }
    $.each(data['error'], function (key, value) {
        $('#' + input_id + '_list').parent('.file_list').prepend('<p class="error_bar">' + value + '</p>');
    });

    if (data['limit'] > 0) {
        $('#' + input_id + '_input').show(200);
    }

    return;
}

async function remove_file(file_block) {
    let path = $(file_block).data('path');
    let file = $(file_block).data('file');
    let parent_id = $(file_block).parents('.uploaded_files').attr('id');
    let parent_id_arr = parent_id.split('_');
    parent_id_arr.pop();
    let input_id = parent_id_arr.join('_');


    await run_method('tools\\file', 'remove_file', [path, file]);
    $('#' + input_id + '_list').parent('.file_list').children('.error_bar').remove();
    let cur_files = $('#' + input_id + '_files').val();
    let cur_files_arr = cur_files.split(',');
    for (let i = 0; i < cur_files_arr.length; i++) {
        if (cur_files_arr[i] === file) {
            await cur_files_arr.splice(i, 1);
            break;
        }
    }
    let new_files = cur_files_arr.join(',');
    $('#' + input_id + '_files').val(new_files);
    await $(file_block).remove();
    if ($('#' + input_id + '_input').is(':not(:visible)')) {
        await $('#' + input_id + '_input').parent('.input_wrap').show(200);
        await $('#' + input_id + '_input').show(200);
    }
    $('#' + input_id + '_files').trigger('change');
    return;
}