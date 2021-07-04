<?php

namespace archive\catalog;

include __DIR__ . '/../loader.php';

use archive\database\database as db;
use archive\catalog\handler as handler;
use archive\tools\file as file;

class admin {

    private $db;
    private $file;
    private $handler;

    function __construct() {
        $this->db = new db();
        $this->file = new file();
        $this->handler = new handler();
    }

    function install_zip(
            $data
    ) {
        $stats = $this->handler->unpack_data_zip($data);

        return '<div class="autoload_result_stats">
                <p>в архив добавлено:</p>
                <div>
                    <p>' . $stats['funds'] . '</p>
                    <p> фондов</p>
                </div>
                <div>
                    <p>' . $stats['invs'] . '</p>
                    <p> описей</p>
                </div>
                <div>
                    <p>' . $stats['docs'] . '</p>
                    <p> документов</p>
                </div>
                <div>
                    <p>' . $stats['entries'] . '</p>
                    <p> страниц</p>
                </div>
            </div>';
    }

}
