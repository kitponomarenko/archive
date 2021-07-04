<?php

$pages_total = '';
if (!empty($cat_data['pages'])) {
    $pages_total = '<p>' . $doc_data['pages'] . 'стр.</p> ';
}

$abs_class = '';
if ($content['ready'] == 0) {
    $abs_class = 'class="doc_temp_abs"';
}

return '<div class="protocol_search_res">
        <div>
            <p>' . $content['num'] . '</p>
            <a ' . $abs_class . ' href="doc?id=' . $content['id'] . '">' . $content['title'] . '</a>    
        </div>
        <div>
            <p>(' . $content['num_old'] . ')</p>
            <p>' . $content['date'] . '</p>
            ' . $pages_total . '   
        </div>
    </div>';
