<?php

namespace archive\catalog;

include 'php/archive/loader.php';

use archive\database\database as db;

class viewer {

    private $db;

    function __construct() {
        $this->db = new db();
    }

    function is_doc_exist(
            $doc_id
    ) {
        $result = false;

        $doc_data = $this->db->fetch_query('protocols', "WHERE id='$doc_id'");
        if (!empty($doc_data)) {
            $file_path = __DIR__ . '/../../../fond/Фонд ' . $doc_data['fund'] . '/д.' . $doc_data['inv'];
            if (file_exists($file_path)) {
                $result = $doc_data;
            }
        }

        return $result;
    }
    
    function is_page_exist(
            $doc_id
    ) {
        $result = false;

        $doc_data = $this->db->fetch_query('protocols', "WHERE id='$doc_id'");
        if (!empty($doc_data)) {
            $file_path = __DIR__ . '/../../../fond/Фонд ' . $doc_data['fund'] . '/д.' . $doc_data['inv'];
            if (file_exists($file_path)) {
                $result = $doc_data;
            }
        }

        return $result;
    }
    
    
    
    

}
