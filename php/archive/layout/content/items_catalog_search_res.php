<?php

$fund = $content['fund'];
$inv = $content['inv'];
$cat_data = json_decode($content['data'], 1);

$docs_list = '';

foreach ($cat_data as $doc) {
    $doc_id = $doc['doc'];
    $pages = '';
    $doc_data = $this->db->fetch_query('protocols', "WHERE fund='$fund' AND inv='$inv' AND num_old='$doc_id'", 'id,title');
    if (!empty($doc_data)) {
        $abs_class = '';
        if ($doc_data['ready'] == 0) {
            $abs_class = 'class="doc_temp_abs"';
        }

        if (!empty($doc['pages'])) {
            if (count($doc['pages']) == 1) {
                $page_str = $doc['pages'][0];
                if (stripos($doc['pages'][0], 'об') > 0){
                    $page_str = trim(str_replace('об', '', $doc['pages'][0])) + 1;
                }
                $page_link = $page_str;
            } else if (count($doc['pages']) == 2) {
                $page_str = implode('-', $doc['pages']);
                $page_link = $doc['pages'][0];
            } else if (count($doc['pages']) > 2) {
                $page_str = implode(',', $doc['pages']);
                $page_link = $doc['pages'][0];
            }
            $pages = '<a ' . $abs_class . ' href="doc?id=' . $doc_data['id'] . '&page=' . $page_link . '">стр. ' . $page_str . '</a>';
        }

        $docs_list .= '<div><a ' . $abs_class . ' href="doc?id=' . $doc_data['id'] . '">' . $doc_data['title'] . '</a>' . $pages . '</div>';
    }
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
