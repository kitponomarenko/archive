<?php

namespace archive\catalog;

include __DIR__ . '/../loader.php';

use archive\database\database as db;
use archive\catalog\handler as handler;
use archive\catalog\parser as parser;
use archive\tools\file as file;
use archive\content\content as content;

class admin {

    private $db;
    private $file;
    private $handler;
    private $content;
    private $parser;

    function __construct() {
        $this->db = new db();
        $this->file = new file();
        $this->handler = new handler();
        $this->content = new content();
        $this->parser = new parser();
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

    function get_funds(
            $request_num = 0
    ) {
        return $this->content->get_content('funds', [], $request_num, ['fund' => 'DESC'], 20, null, 'fund_lt');
    }

    function create_fund(
            $fund
    ) {
        $result = [
            'valid' => 0,
            'message' => ''
        ];

        if (empty($fund)) {
            $result['message'] = 'необходимо указать номер фонда';
        } else {
            if (!$this->handler->is_fund_exist($fund)) {
                $this->db->insert_row('funds', ['fund' => $fund]);
                $result['valid'] = 1;
            } else {
                $result['message'] = 'такой фонд уже существует';
            }
        }

        return $result;
    }

    function get_invs(
            $fund_id,
            $request_num = 0
    ) {
        return $this->content->get_content('invs', [['fund', $fund_id]], $request_num, ['inv' => 'DESC'], 20, null, 'inv_lt');
    }

    function create_inv(
            $fund,
            $inv
    ) {
        $result = [
            'valid' => 0,
            'message' => ''
        ];

        if (empty($inv)) {
            $result['message'] = 'необходимо указать номер описи';
        } else {
            if (!$this->handler->is_inv_exist($fund, $inv)) {
                $this->db->insert_row('invs', ['fund' => $fund, 'inv' => $inv]);
                $result['valid'] = 1;
            } else {
                $result['message'] = 'такая опись уже существует';
            }
        }

        return $result;
    }

    function install_inv(
            $fund,
            $inv,
            $doc
    ) {
        $this->parser->compile_fund_catalog($fund, $inv, $doc);
        $this->parser->compile_fund_protocol($fund, $inv, $doc);
        $this->handler->handle_funds();
        return;
    }

    function get_docs(
            $fund_id,
            $inv_id,
            $request_num = 0
    ) {
        return $this->content->get_content('protocols', [['fund', $fund_id], ['inv', $inv_id]], $request_num, ['ready' => 'DESC', 'inv' => 'DESC'], 20, null, 'protocol_lt');
    }

}
