$(document).on('keyup', '#search_formula', delay(async function () {
    let search_string = $(this).val();
    if(search_string === ''){
        search_string = '%';
    }
    let filter_type = $('#search_filters>button.active').data('filter_type');
    let sort = $('#search_sort').data('search_sort');
    data = await run_method('catalog\\search', 'find_docs', [search_string, filter_type, sort]);
    $('#search_results').html(data['content']);
    $('#search_more').html(data['more']);
}, 500));


$(document).on('click', '#search_filters>button:not(.active)', async function () {
    $('#search_filters>button.active').removeClass('active');
    $(this).addClass('active');
    let search_string = $('#search_formula').val();
    if(search_string === ''){
        search_string = '%';
    }
    let filter_type = $(this).data('filter_type');
    let sort = $('#search_sort').data('search_sort');
    data = await run_method('catalog\\search', 'find_docs', [search_string, filter_type, sort]);
    $('#search_results').html(data['content']);
    $('#search_more').html(data['more']);
});

$(document).on('click', '#search_sort', async function () { 
    if($(this).data('search_sort') === 'DESC'){
        $(this).data('search_sort', 'ASC');
        $(this).find('svg').css('transform', 'rotate(180deg)');
    }else{
        $(this).data('search_sort', 'DESC');
        $(this).find('svg').css('transform', 'rotate(0deg)');
    }
    let search_string = $('#search_formula').val();
    if(search_string === ''){
        search_string = '%';
    }
    let filter_type = $('#search_filters>button.active').data('filter_type');
    let sort = $(this).data('search_sort');
    data = await run_method('catalog\\search', 'find_docs', [search_string, filter_type, sort]);
    $('#search_results').html(data['content']);
    $('#search_more').html(data['more']);
});


$(document).on('click', '#search_more>button', async function () {
    let search_string = $('#search_formula').val();
    if(search_string === ''){
        search_string = '%';
    }
    let filter_type = $('#search_filters>button.active').data('filter_type');
    let sort = $('#search_sort').data('search_sort');
    let request_num = $(this).data('request_num');
    data = await run_method('catalog\\search', 'find_docs', [search_string, filter_type, sort, request_num]);
    $('#search_results').append(data['content']);
    $('#search_more').html(data['more']);
});