$(document).on('click', '#invs_more>button', async function () {
    let request_num = $(this).data('request_num');
    let fund = $('#inv_data').data('fund');
    data = await run_method('catalog\\admin', 'get_invs', [fund, request_num]);
    $('#invs_results').append(data['content']);
    $('#invs_more').html(data['more']);
});

$(document).on('click', '#create_inv', async function () {
    let fund = $('#inv_data').data('fund');
    let inv = $('#inv_num').val();
    data = await run_method('catalog\\admin', 'create_inv', [fund, inv]);
    if (data['valid'] === 1) {
        content = await run_method('catalog\\admin', 'get_invs', [fund]);
        $('#inv_num').val('');
        $('#invs_results').html(content['content']);
        $('#invs_more').html(content['more']);
        $('.error_bar').remove();
    } else {
        $('.error_bar').remove();
        $('#inv_num').before('<p class="error_bar">' + data['message'] + '</p>');
    }
});