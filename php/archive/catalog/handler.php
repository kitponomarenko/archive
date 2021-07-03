<?php

namespace archive\catalog;

include 'php/archive/loader.php';

use archive\database\database as db;
use archive\tools\file as file;

class handler {

    private $db;
    private $file;

    function __construct() {
        $this->db = new db();
        $this->file = new file();
    }

    function is_fund_exist(
            $fund
    ) {
        return $this->db->fetch_query('funds', "WHERE fund='$fund'");
    }

    function is_inv_exist(
            $fund,
            $inv
    ) {
        return $this->db->fetch_query('invs', "WHERE fund='$fund' AND inv='$inv'");
    }

    function is_ent_exist(
            $fund,
            $inv,
            $entry
    ) {
        return $this->db->fetch_query('entries', "WHERE fund='$fund' AND inv='$inv' AND entry='$entry'");
    }

    function handle_funds() {
        $funds = $this->get_dir('fond');
        foreach ($funds as $fund) {
            if ($fund != 'tmp') {
                $fund_id = trim(stristr($fund, ' '));
                if ($this->is_fund_exist($fund_id) == false) {
                    $this->db->insert_row('funds', ['fund' => $fund_id]);
                }
                $inventories = $this->get_dir('fond/' . $fund);
                foreach ($inventories as $inv) {
                    $inv_id = trim(stristr($inv, ' '));
                    if ($this->is_inv_exist($fund_id, $inv_id) == false) {
                        $this->db->insert_row('invs', ['fund' => $fund_id, 'inv' => $inv_id]);
                    }
                    $docs = $this->get_dir('fond/' . $fund . '/' . $inv);
                    foreach ($docs as $doc) {
                        $doc_id = substr($doc, 3);
                        $entries = $this->get_dir('fond/' . $fund . '/' . $inv . '/' . $doc);
                        $i = 0;
                        foreach ($entries as $entry) {
                            ++$i;
                            if ($this->is_ent_exist($fund_id, $inv_id, $entry) == false) {
                                $this->db->insert_row('entries', ['fund' => $fund_id, 'inv' => $inv_id, 'doc' => $doc_id,'entry' => $entry, 'priority' => $i]);
                            }
                        }
                    }
                }
            }
        }

        return;
    }

    function get_dir(
            $dir
    ) {
        $content = scandir(__DIR__ . '/../../../' . $dir);
        unset($content[0]);
        unset($content[1]);
        sort($content);

        return $content;
    }

    function unpack_data_zip(
            $data
    ) {

        $zip = new \ZipArchive();
        $zip->open(__DIR__ . '/../../../fond/' . $data . '.zip');

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $old_name = $stat['name'];
            $new_name = $this->zip_stringer($old_name);
            $zip->renameName($old_name, $new_name);
        }
        $zip->close();

        $zip->open(__DIR__ . '/../../../fond/' . $data . '.zip');
        $zip->extractTo(__DIR__ . '/../../../fond/tmp/');
        $zip->close();

        $this->file->remove_file('fond/', $data . '.zip');

        $this->restore_zip();
        $this->handle_funds();

        return;
    }

    function restore_zip() {

        $tmp = $this->get_dir('fond/tmp');

        foreach ($tmp as $fund) {
            $fund_name = $this->zip_stringer($fund, 'dec');
            $this->file->rename_file('fond/tmp', $fund, $fund_name);
            $inventories = $this->get_dir('fond/tmp/' . $fund_name);
            foreach ($inventories as $inv) {
                $inv_name = $this->zip_stringer($inv, 'dec');
                $this->file->rename_file('fond/tmp/' . $fund_name, $inv, $inv_name);
                $docs = $this->get_dir('fond/tmp/' . $fund_name . '/' . $inv_name);
                foreach ($docs as $doc) {
                    $doc_name = $this->zip_stringer($doc, 'dec');
                    $this->file->rename_file('fond/tmp/' . $fund_name . '/' . $inv_name, $doc, $doc_name);
                    $entries = $this->get_dir('fond/tmp/' . $fund_name . '/' . $inv_name . '/' . $doc_name);
                    foreach ($entries as $entry) {
                        if (($entry == 'Thumbs.db') || ($entry == 'thumbs.db')) {
                            $this->file->remove_file('fond/tmp/' . $fund_name . '/' . $inv_name . '/' . $doc_name, $entry);
                        } else {
                            $file_name = explode('.', $entry);
                            array_pop($file_name);
                            $entry_name = implode('.', $file_name);
                            $ent_name = $this->zip_stringer($entry_name, 'dec');
                            $this->file->rename_file('fond/tmp/' . $fund_name . '/' . $inv_name . '/' . $doc_name, $entry, $ent_name);
                        }
                    }
                }
            }
            $this->file->move_file('fond/tmp', $fund_name, 'fond');
        }
    }

    function zip_stringer(
            $str,
            $op = 'enc'
    ) {
        $cyr = array(
            "Щ", "Ш", "Ч", "Ц", "Ь", "Ы", "Ъ", "Ю", "Я", "Ж", "А", "Б", "В",
            "Г", "Д", "Е", "Ё", "З", "И", "Й", "К", "Л", "М", "Н",
            "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Э",
            "щ", "ш", "ч", "ц", "ь", "ы", "ъ", "ю", "я", "ж", "а", "б", "в",
            "г", "д", "е", "ё", "з", "и", "й", "к", "л", "м", "н",
            "о", "п", "р", "с", "т", "у", "ф", "х", "э"
        );
        $lat = array(
            "Shch", "Sh", "Ch", "C", "Mz", "Yi", "Tz", "Yu", "Ya", "J", "A", "B", "V",
            "G", "D", "E", "E", "Z", "I", "Y", "K", "L", "M", "N",
            "O", "P", "R", "S", "T", "U", "F", "H", "E",
            "shch", "sh", "ch", "c", "mz", "yi", "tz", "Yu", "Ya", "j", "a", "b", "v",
            "g", "d", "e", "e", "z", "i", "y", "k", "l", "m", "n",
            "o", "p", "r", "s", "t", "u", "f", "h", "e"
        );
        if ($op == 'enc') {
            $result = str_replace($cyr, $lat, $str);
            $result = str_replace(' ', '_', $result);
        } else if ($op == 'dec') {
            $result = str_replace($lat, $cyr, $str);
            $result = str_replace('_', ' ', $result);
        }

        return $result;
    }

}
