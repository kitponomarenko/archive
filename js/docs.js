$(document).on('change', '#inv_doc_files', function () {
    if ($(this).val() === '') {
        $('#import_inv').hide();
    } else {
        $('#import_inv').show();
    }
});

$(document).on('click', '#import_inv', async function () {
    let fund = $('#inv_data').data('fund');
    let inv = $('#inv_data').data('inv');
    let doc = $('#inv_doc_files').val();
    $('#inv_doc_list').html('');
    $(this).hide();
    $('#docs_more').html('');
    $('#docs_results').html('<div class="loader"><div></div></div>');
    await run_method('catalog\\admin', 'install_inv', [fund, inv, doc]);
    content = await run_method('catalog\\admin', 'get_docs', [fund, inv]);
    $('#inv_doc_input').show();
    $('#inv_doc_files').val('');
    $('#docs_results').html(content['content']);
    $('#docs_more').html(content['more']);
});

$(document).on('click', '#docs_more>button', async function () {
    let fund = $('#inv_data').data('fund');
    let inv = $('#inv_data').data('inv');
    let request_num = $(this).data('request_num');
    data = await run_method('catalog\\admin', 'get_docs', [fund, inv, request_num]);
    $('#docs_results').append(data['content']);
    $('#docs_more').html(data['more']);
});
