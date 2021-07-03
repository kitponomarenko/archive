<?php

namespace archive\catalog;

include 'php/archive/loader.php';

use archive\database\database as db;

class parser {

    private $catalog_types;
    private $tbl_replace;
    private $char_replace;

    function __construct() {
        $this->catalog_types = [
            'items' => 'Предметный указатель',
            'names' => 'Именной указатель'
        ];

        $this->tbl_replace = [
            '' => '#',
            '###' => '# ##'
        ];

        $this->char_replace = [
            '\rn' => '$',
            '\nr' => '$',
            '\r' => '$',
            '\n' => '$'
        ];
    }

    function read_fund_doc(
            $doc
    ) {
        $file_path = __DIR__ . '/../../../docs/funds/' . $doc . '.doc';
        if (file_exists($file_path)) {
            if (($fund = fopen($file_path, 'r')) !== false) {
                $headers = fread($fund, 0xA00);

                $length_a = ( ord($headers[0x21C]) - 1 );
                $length_b = ( ( ord($headers[0x21D]) - 8 ) * 256 );
                $length_c = ( ( ord($headers[0x21E]) * 256 ) * 256 );
                $length_d = ( ( ( ord($headers[0x21F]) * 256 ) * 256 ) * 256 );

                $total_length = ($length_a + $length_b + $length_c + $length_d);

                $fund_doc = fread($fund, $total_length);
                $fund_doc_encoded = mb_convert_encoding($fund_doc, 'UTF-8', 'UTF-16LE');
                foreach ($this->tbl_replace as $key => $val) {
                    $fund_doc_encoded = str_replace($key, $val, $fund_doc_encoded);
                }
                $fund_doc_json = json_encode($fund_doc_encoded);
                foreach ($this->char_replace as $key => $val) {
                    $fund_doc_json = str_replace($key, $val, $fund_doc_json);
                }

                $fund_doc_dec = json_decode($fund_doc_json);
                $charset = mb_detect_encoding($fund_doc_dec);
                $fund_doc_ready = iconv($charset, "UTF-8", $fund_doc_dec);
                
                $words = explode(' ', $fund_doc_ready);
                foreach($words as $i => $word){
                    if((preg_match('/(^[а-я]|[а-я]$|[а-я].$)/u', $word)) && (preg_match('/[a-zA-Z]/u', $word))){
                        $words[$i] = preg_replace('/[А-Яa-zA-Z0-9$#;,.]/u', '', $word);
                    }
                }
                
                $fund_doc_ready = implode(' ', $words);

                return preg_replace('/[^а-я А-Яa-zA-Z0-9$#,.;-]/u', '', $fund_doc_ready);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_catalog_tbl(
            $fund_doc,
            $catalog_type = 'items'
    ) {
        $cat_start = stristr($fund_doc, $this->catalog_types[$catalog_type]);
        $cat_end = stripos($cat_start, '##$$');
        $cat_chunk = substr($cat_start, 0, $cat_end);
        $cat_tbl = stripos($cat_chunk, '##');
        $cat_headoff = substr($cat_chunk, $cat_tbl + 2);
        $cat_clean = stristr($cat_headoff, '##');
        $catalog = substr($cat_clean, 2);

        return $catalog;
    }

    function get_protocol_tbl(
            $fund_doc
    ) {
        $prot_start = stristr($fund_doc, 'Заголовок дела');
        $prot_end = stripos($prot_start, '##$');
        $prot_chunk = substr($prot_start, 0, $prot_end);
        $prot_tbl = stripos($prot_chunk, '##');
        $prot_headoff = substr($prot_chunk, $prot_tbl + 2);
        $prot_clean = stristr($prot_headoff, '##');
        $protocol = substr($prot_clean, 2);

        return $protocol;
    }

    function get_catalog_assoc(
            $catalog
    ) {
        $catalog_assoc = [];

        $cat_arr = explode('##', $catalog);

        foreach ($cat_arr as $row) {
            $cols = explode('#', $row);
            $catalog_assoc[$cols[0]] = $cols[1];
        }

        return $catalog_assoc;
    }

    function get_protocol_voc(
            $protocol
    ) {
        $voc = [];
        $protocol = str_replace('##', '@#', $protocol);
        $prot_arr = explode('#', $protocol);

        $i = 1;
        foreach ($prot_arr as $col) {
            if ($i == 1) {
                $voc[] = [
                    'num' => $col,
                    'num_old' => '',
                    'title' => '',
                    'date' => '',
                    'pages' => '',
                    'extra' => ''
                ];
                $i++;
            } else {
                end($voc);
                $f = key($voc);
                if ($i == 6) {
                    $voc[$f]['extra'] = explode('$', str_replace('@', '', $col));
                    $i = 1;
                } else {
                    $col_clean = str_replace('@', '', $col);
                    if (!empty(str_replace(' ', '', $col_clean))) {
                        if ($i == 2) {
                            $voc[$f]['num_old'] = $col_clean;
                        } else if ($i == 3) {
                            $voc[$f]['title'] = $col_clean;
                        } else if ($i == 4) {
                            $voc[$f]['date'] = $col_clean;
                        } else if ($i == 5) {
                            $voc[$f]['pages'] = $col_clean;
                        }
                        ++$i;
                    }
                    if (stripos($col, '@') > 0) {
                        ++$i;
                    }
                }
            }
        }

        return $voc;
    }

    function bake_catalog_data(
            $data
    ) {
        $data_arr = [];
        $array = explode(',', $data);
        foreach ($array as $val) {
            $val_arr = explode(';', $val);
            foreach ($val_arr as $data_el) {
                $el_clean = trim($data_el);
                $pages = '';
                if (stripos($el_clean, 'л.')) {
                    $el_arr = explode('л.', $el_clean);
                    $el_clean = trim($el_arr[0]);
                    $pages = explode('-', $el_arr[1]);
                }
                $data_arr[] = [
                    'doc' => $el_clean,
                    'pages' => $pages
                ];
            }
        }

        return $data_arr;
    }

    function get_items_voc(
            $cat_assoc
    ) {
        $voc = [];

        foreach ($cat_assoc as $key => $val) {
            $words = explode('$', $key);
            $data = explode('$', $val);
            $type = '';
            foreach ($words as $i => $value) {
                if (empty($value)) {
                    end($voc);
                    $f = key($voc);
                    $voc[$f]['data'] = $voc[$f]['data'] + $this->bake_catalog_data($data[$i]);
                } else {
                    if (empty($data[$i])) {
                        $type = $value;
                    } else {
                        $voc[] = [
                            'name' => $value,
                            'type' => $type,
                            'data' => $this->bake_catalog_data($data[$i])
                        ];
                    }
                }
            }
        }

        return $voc;
    }

    function get_names_voc(
            $cat_assoc
    ) {
        $voc = [];

        foreach ($cat_assoc as $key => $val) {
            $role = '';

            if (stripos($key, ',')) {
                $name_arr = explode(',', $key);
            } else if (stripos($key, '$')) {
                $name_arr = explode('$', $key);
            }

            if (isset($name_arr[1])) {
                $role = str_replace('$', '', $name_arr[1]);
            }

            $voc[] = [
                'name' => $name_arr[0],
                'role' => $role,
                'data' => $this->bake_catalog_data($val)
            ];
        }

        return $voc;
    }

    function push_catalog_db(
            $tbl_name,
            $fund_id,
            $voc
    ) {
        $db = new db();

        foreach ($voc as $row) {
            $name = $row['name'];
            $row_exist = $db->fetch_query($tbl_name . '_catalog', "WHERE fund='$fund_id' AND name='$name'", 'id');
            if (empty($row_exist)) {
                $row['fund'] = $fund_id;
                $row['data'] = json_encode($row['data']);
                $db->insert_row($tbl_name . '_catalog', $row);
            }
        }

        return;
    }

    function push_protocol_db(
            $fund_id,
            $voc
    ) {
        $db = new db();

        foreach ($voc as $row) {
            $num = $row['num'];
            $row_exist = $db->fetch_query('protocols', "WHERE fund='$fund_id' AND num='$num'", 'id');
            if (empty($row_exist)) {
                $row['fund'] = $fund_id;
                $row['extra'] = json_encode($row['extra']);
                $db->insert_row('protocols', $row);
            }
        }

        return;
    }

    function compile_fund_catalog(
            $fund_id,
            $doc
    ) {
        $fund_doc = $this->read_fund_doc($doc);
        foreach ($this->catalog_types as $key => $val) {
            $cat_tbl = $this->get_catalog_tbl($fund_doc, $key);
            $cat_assoc = $this->get_catalog_assoc($cat_tbl);
            $voc_method = 'get_' . $key . '_voc';
            $cat_voc = $this->$voc_method($cat_assoc);
            $this->push_catalog_db($key, $fund_id, $cat_voc);
        }

        return;
    }

    function compile_fund_protocol(
            $fund_id,
            $doc
    ) {
        $fund_doc = $this->read_fund_doc($doc);
        $prot_tbl = $this->get_protocol_tbl($fund_doc);
        $prot_voc = $this->get_protocol_voc($prot_tbl);
        $this->push_protocol_db($fund_id, $prot_voc);

        return $prot_voc;
    }

}
