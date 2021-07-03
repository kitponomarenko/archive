<?php

namespace archive\content;

include __DIR__ . '/../loader.php';

use archive\content\filter;
use archive\database\database;
use archive\config\config;

class content {

    protected $filter;
    protected $db;
    protected $config;

    function __construct() {
        $this->filter = new filter();
        $this->db = new database();
        $this->config = new config();
    }

    function get_content(
            string $content_tbl,
            array $filters = [],
            int $request = 0,
            array $sort = ['id' => 'DESC'],
            int $limit = null,
            int $active = null,
            string $layout = ''
    ) {
        $result['content'] = '';
        $result['more'] = '';
        $filter = $this->filter->complete_filter($filters, $request, $sort, $limit, $active);

        if (empty($layout)) {
            $layout = $content_tbl;
        }

        $content_query = $this->db->get_query($content_tbl, $filter);
        if (mysqli_num_rows($content_query) > 0) {
            while ($content = mysqli_fetch_assoc($content_query)) {
                $result['content'] .= include __DIR__ . '/../layout/content/' . $layout . '.php';
            }
            if ($limit != null) {
                $new_request = $request + 1;
                $more_filter = $this->filter->complete_filter($filters, $new_request, $sort, $limit, $active);
                $more_query = $this->db->fetch_query($content_tbl, $more_filter);
                if (!empty($more_query)) {
                    $result['more'] = '<button id="btn_more_' . $layout . '" data-request_num="' . $new_request . '">показать еще</button>';
                }
            }
        }

        return $result;
    }

}
