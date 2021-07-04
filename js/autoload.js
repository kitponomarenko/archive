$(document).on('change', '#autoload_zip_files', function () {
    if($(this).val() === ''){
        $('#unpack_zip').hide();
    }else{
        $('#unpack_zip').show();
    }
});

$(document).on('click', '#unpack_zip', async function () {
    let zip = $('#autoload_zip_files').val();
    $('#autoload_zip_list').html('');
    $(this).hide();
    $('#autoload_results').html('<div class="loader"><div></div></div>');
    let data = await run_method('catalog\\admin', 'install_zip', [zip]);
    $('#autoload_results').html(data);
    $('#autoload_zip_input').show();
    $('#autoload_zip_files').val('');
});