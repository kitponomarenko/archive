$(document).on('keyup', '#search_formula', delay(async function () {
    let search_string = $(this).val();
    let filter_type = $('#search_filters>button.active').data('filter_type');
    data = await run_method('catalog\\search', 'find_docs', [search_string, filter_type]);
    $('#search_results').html(data['content']);
    $('#search_more').html(data['more']);
}, 500));


$(document).on('click', '#search_filters>button:not(.active)', async function () {
    $('#search_filters>button.active').removeClass('active');
    $(this).addClass('active');
    let search_string = $('#search_formula').val();
    let filter_type = $(this).data('filter_type');
    data = await run_method('catalog\\search', 'find_docs', [search_string, filter_type]);
    $('#search_results').html(data['content']);
    $('#search_more').html(data['more']);
});

$(document).on('click', '#search_more>button', async function () {
    let search_string = $('#search_formula').val();
    let filter_type = $('#search_filters>button.active').data('filter_type');
    let request_num = $(this).data('request_num');
    data = await run_method('catalog\\search', 'find_docs', [search_string, filter_type, request_num]);
    $('#search_results').append(data['content']);
    $('#search_more').html(data['more']);
});