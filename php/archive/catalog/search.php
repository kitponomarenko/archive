<?php

namespace archive\catalog;

include __DIR__ . '/../loader.php';

use archive\database\database as db;
use archive\content\content as content;

class search {

    private $db;
    private $content;

    function __construct() {
        $this->db = new db();
        $this->content = new content();
    }

    function find_docs(
            $search_str,
            $filter_type,
            $request_num = 0
    ) {
        $words = explode(' ', $search_str);
        $filter = [];

        $cols = [
            'protocols' => ['num', 'num_old', 'title', 'date'],
            'items_catalog' => ['name', 'type'],
            'names_catalog' => ['name', 'role']
        ];

        foreach ($words as $i => $word) {
            $filter[] = [$cols[$filter_type], [$word], 'LIKE'];
        }

        return $this->content->get_content($filter_type, $filter, $request_num, ['id' => 'DESC'], 10, null, $filter_type . '_search_res');
    }

}
