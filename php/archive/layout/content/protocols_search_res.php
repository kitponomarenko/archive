<?php

$pages_total = '';
if (!empty($cat_data['pages'])) {
    $pages_total = '<p>' . $doc_data['pages'] . 'стр.</p> ';
}

return '<div class="protocol_search_res">
        <div>
            <p>' . $content['num'] . '</p>
            <a href="doc?id=' . $content['id'] . '">' . $content['title'] . '</a>    
        </div>
        <div>
            <p>' . $content['num_old'] . '</p>
            <p>' . $content['date'] . '</p>
            ' . $pages_total . '   
        </div>
    </div>';
