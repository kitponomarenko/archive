
$(document).on('click', '#funds_more>button', async function () {
    let request_num = $(this).data('request_num');
    data = await run_method('catalog\\admin', 'get_funds', [request_num]);
    $('#funds_results').append(data['content']);
    $('#funds_more').html(data['more']);
});

$(document).on('click', '#create_fund', async function () {
    let fund = $('#fund_num').val();
    data = await run_method('catalog\\admin', 'create_fund', [fund]);
    if (data['valid'] === 1) {
        content = await run_method('catalog\\admin', 'get_funds', []);
        $('#funds_results').html(content['content']);
        $('#funds_more').html(content['more']);
        $('#fund_num').val('');
        $('.error_bar').remove();
    } else {
        $('.error_bar').remove();
        $('#fund_num').before('<p class="error_bar">' + data['message'] + '</p>');
    }
});