<?php

namespace archive\database;
include __DIR__ . '/../loader.php';

use archive\config\config;

class database {

    protected $config_obj;
    protected $config;

    function __construct() {
        $this->config_obj = new config();
        $this->config = $this->config_obj->get_config();
    }

    function link(
            string $type
    ) {
        $host = $this->config['host'];
        $database = $this->config['database_name'];
        $login = $this->config[$type . '_login'];
        $password = $this->config[$type . '_password'];
        $link = mysqli_connect("$host", "$login", "$password", "$database");
        mysqli_set_charset($link, "utf8");
        
        return $link;
    }

    function create_table(
            string $tbl_name = '',
            string $tbl_cols = ''
    ) {
        return mysqli_query($this->link('master'), "CREATE TABLE $tbl_name $tbl_cols COLLATE=utf8mb4_unicode_ci");
    }
    
    function is_tbl_exists(
            string $tbl_name = ''
    ) {
        $link = $this->link('reader');
        $tbl_list_query = mysqli_query($link, "SHOW TABLES LIKE '$tbl_name'");
        $tbl_list_result = mysqli_fetch_array($tbl_list_query);
        return $tbl_list_result;
    }

    function get_query(
            string $tbl_name = '',
            string $condition = '',
            string $cols = '*'
    ) {
        $link = $this->link('reader');
        $tbl_query = mysqli_query($link, "SELECT $cols FROM $tbl_name $condition");

        return $tbl_query;
    }

    function fetch_query(
            string $tbl_name = '',
            string $condition = '',
            string $cols = '*'
    ) {
        $tbl_query = $this->get_query($tbl_name, $condition, $cols);
        $query_array = mysqli_fetch_assoc($tbl_query);

        return $query_array;
    }

    function remove_row(
            string $tbl_name = '',
            int $row_id = 0
    ) {
        $link = $this->create_link('writer');
        $delete_result = mysqli_query($link, "DELETE FROM $tbl_name WHERE id='$row_id'");

        return $delete_result;
    }

    function alter_setter_element(
            $key,
            $val,
            int $counter = 1
    ) {            
        $val_clean = mysqli_real_escape_string($this->link('writer'),$val);
        
        if ($counter == 1) {
            $setter_string = "SET ";
        } else {
            $setter_string = ", ";
        }
        if ($val == 'NULL') {
            $setter_string .= "$key=$val_clean";
        } else {
            $setter_string .= "$key='$val_clean'";
        }

        return $setter_string;
    }

    function bake_setter_data(
            array $data_arr = []
    ) {
        $data = "";
        $counter = 0;
        foreach ($data_arr as $key => $val) {
            if (($val != '') && ($val != NULL)) {
                ++$counter;
                $data .= $this->alter_setter_element($key, $val, $counter);
            }
        }

        return $data;
    }

    function insert_row(
            string $tbl_name,
            array $data_arr
    ) {
        $new_data = $this->bake_setter_data($data_arr);
        $link = $this->link('writer');

        $insert_result = mysqli_query($link, "INSERT INTO $tbl_name $new_data");
        $row_id = mysqli_insert_id($link);

        return [
            'result' => $insert_result,
            'id' => $row_id
        ];
    }

    function update_row(
            string $tbl_name,
            array $data_arr,
            string $condition
    ) {
        $new_data = $this->bake_setter_data($data_arr);
        $link = $this->link('writer');

        $update_result = mysqli_query($link, "UPDATE $tbl_name $new_data $condition");

        return $update_result;
    }

    function fetch_txt_data(
            string $tbl_name = 'dictionary',
            string $label = ''
    ) {
        $result = $label;
        $label_query = $this->fetch_query($tbl_name, "WHERE label='$label'");
        if (!empty($label_query)) {
            $result = $label_query['val'];
        }

        return $result;
    }

    function dictionary(
            string $label = ''
    ) {
        return $this->fetch_txt_data('txt_dictionary', $label, 'val');
    }

    function message(
            string $label = ''
    ) {
        return $this->fetch_txt_data('txt_message', $label, 'val');
    }

    function content(
            string $label = ''
    ) {
        return $this->fetch_txt_data('txt_content', $label,'val');
    }

}
