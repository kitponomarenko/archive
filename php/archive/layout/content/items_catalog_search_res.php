<?php

$fund = $content['fund'];
$inv = $content['inv'];
$cat_data = json_decode($content['data'], 1);

$docs_list = '';

foreach ($cat_data as $doc) {
    $doc_id = $doc['doc'];
    $pages = '';
    $doc_data = $this->db->fetch_query('protocols', "WHERE fund='$fund' AND inv='$inv' AND num_old='$doc_id'", 'id,title');
    if (!empty($doc['pages'])) {
        if (count($doc['pages']) == 1) {
            $page_str = $doc['pages'][0];
        } else if (count($doc['pages']) == 2) {
            $page_str = implode('-', $doc['pages']);
        } else if (count($doc['pages']) > 2) {
            $page_str = implode(',', $doc['pages']);
        }
        $pages = '<a href="doc?id=' . $doc_data['id'] . '&page=' . $doc['pages'][0] . '">стр. ' . $page_str . '</a>';
    }

    $docs_list .= '<div><a href="doc?id=' . $doc_data['id'] . '">' . $doc_data['title'] . '</a>' . $pages . '</div>';
}

$pages_total = '';
if (!empty($cat_data['pages'])) {
    $pages_total = $doc_data['pages'] . 'стр.';
}

return '<div class="catalog_search_res">
        <div>
            <p>' . $content['name'] . '</p>
            <p>' . $content['type'] . '</p>    
        </div>
        ' . $docs_list . '
    </div>';
