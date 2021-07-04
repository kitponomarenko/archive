<?php

namespace archive\tools;

include __DIR__ . '/../loader.php';

use archive\config\config;

class file {

    private $config;
    private $type;
    private $upload_result;

    function __construct() {
        $this->config = new config();

        $this->type = [
            'default' => [],
            'autoload_zip' => [
                'size' => 10000,
                'path' => 'fond/',
                'tmp' => '',
                'success' => 'success_default',
                'output' => 'uploaded_zip',
                'mime' => [
                    'application/zip'
                ],
                'limit' => 1,
                'randomize_name' => false,
                'force_type' => ''
            ], 'inv_doc' => [
                'size' => 10000,
                'path' => 'docs/funds',
                'tmp' => '',
                'force_type' => '',
                'success' => 'success_default',
                'output' => 'uploaded_zip',
                'mime' => [
                    'application/msword',
                    'application/CDFV2'
                ],
                'limit' => 1,
                'randomize_name' => false
            ],
            'photo' => [
                'size' => 3,
                'path' => '../',
                'tmp' => '',
                'force_type' => '',
                'success' => 'success_default',
                'output' => 'uploaded_photo',
                'mime' => [
                    'image/jpeg',
                    'image/tiff',
                    'image/jpg',
                    'image/tif'
                ],
                'limit' => 0
            ]
        ];


        $this->upload_result = [
            'valid' => 0,
            'message' => '',
            'error' => [],
            'output' => '',
            'file' => [],
            'limit' => 0
        ];
    }

    function bake_file(
            string $key,
            string $type
    ) {
        if (is_uploaded_file($_FILES['file']['tmp_name'][$key])) {
            $path = $this->type[$type]['path'] . $this->type[$type]['tmp'];
            $file_type = $this->get_file_type($_FILES['file']['name'][$key]);
            $file_name_new = $_FILES['file']['name'][$key];
            if ($this->type[$type]['randomize_name'] == true) {
                $file_id = md5(uniqid(rand(), 1));
                $file_name_new = '' . $file_id . '_' . $file_type . '';
            }
            if ($this->type[$type]['force_type'] != '') {
                $file_name_new = $file_name_new . '.' . $this->type[$type]['force_type'] . '';
            }
            $tmp_file = $_FILES['file']['tmp_name'][$key];

            $file_name_arr = explode('/', $file_name_new);
            $file = array_pop($file_name_arr);
            move_uploaded_file($tmp_file, "__DIR__ /../../../$path/$file");
            array_push($this->upload_result['file'], $file);
            $this->upload_result['output'] .= $this->bake_output($this->type[$type]['output'], $path, $file);
        }
    }

    function bake_output(
            string $output,
            string $path,
            string $file
    ) {
        $result = '';
        if (!empty($output)) {
            $result = include __DIR__ . '/../layout/blocks/' . $output . '.php';
        }

        return $result;
    }

    function upload_file(
            string $type = 'default',
            int $count = 0
    ) {
        $this->upload_result['limit'] = $this->type[$type]['limit'];

        if (isset($_FILES['file']['name'])) {
            $file_count = count($_FILES['file']['name']);
            $limit_actual = $this->get_limit($file_count, $count);

            for ($key = 0; $key < $limit_actual; $key++) {
                if ($this->is_correct_mime($key, $this->type[$type]['mime']) == true) {
                    $this->check_file_size($key, $type);
                } else {
                    array_push($this->upload_result['error'], 'Неверный тип файла');
                    $limit_actual = $this->check_limit($limit_actual, $file_count);
                }
            }
        }

        return $this->upload_result;
    }

    function get_limit(
            int $file_count,
            int $count = 0
    ) {
        if ($this->upload_result['limit'] > 0) {
            $limit_actual = $this->upload_result['limit'] - $count;
            if ($limit_actual < 0) {
                $limit_actual = 0;
            }
            if ($file_count > $limit_actual) {
                array_push($this->upload_result['error'], 'Максимальное количество файлов:' . $this->upload_result['limit']);
            } else {
                $limit_actual = $file_count;
            }
        } else {
            $limit_actual = $file_count;
        }

        return $limit_actual;
    }

    function check_limit(
            int $limit_actual,
            int $file_count
    ) {

        if ($file_count > $limit_actual) {
            ++$limit_actual;
        }

        return $limit_actual;
    }

    function is_correct_mime(
            string $key,
            array $mime
    ) {
        $result = false;
        if (!empty($mime)) {
            foreach ($mime as $type) {
                if ($type == mime_content_type($_FILES['file']['tmp_name'][$key])) {
                    $result = true;
                    break;
                }
            }
        } else {
            $result = true;
        }

        return $result;
    }

    function check_file_size(
            string $key,
            string $type
    ) {
        if ($_FILES['file']['size'][$key] <= ($this->type[$type]['size'] * 1024 * 1024)) {
            $this->upload_result['valid'] = 1;
            $this->upload_result['message'] = 'Файл(ы) успешно загружен!';
            $this->bake_file($key, $type);
            --$this->upload_result['limit'];
        } else {
            array_push($this->upload_result['error'], 'Максимальный размер файла: ' . $this->type[$type]['size']);
        }
    }

    function get_file_type(
            string $file
    ) {
        $type = false;

        $file_name_arr = explode('.', $file);
        if (count($file_name_arr) > 1) {
            $type = end($file_name_arr);
        }

        return $type;
    }

    function change_file(
            string $dir,
            string $file,
            string $new_dir = '',
            string $new_file = ''
    ) {
        if (empty($new_dir)) {
            $new_dir = $dir;
        }

        if (empty($new_file)) {
            $new_file = $file;
        }

        $root = __DIR__ . '/../../../';
        $path = $root . $dir . '/' . $file;
        $path_new = $root . $new_dir . '/' . $new_file;

        return rename($path, $path_new);
    }

    function rename_file(
            string $dir,
            string $file,
            string $new_file = ''
    ) {
        if ((!empty($new_file)) && (!is_dir(__DIR__ . '/../../../' . $dir . '/' . $file))) {
            $type = $this->get_file_type($file);
            if ($type != false) {
                $new_file = $new_file . '.' . $type;
            }
        }

        return $this->change_file($dir, $file, '', $new_file);
    }

    function move_file(
            string $dir,
            string $file,
            string $new_dir = ''
    ) {
        return $this->change_file($dir, $file, $new_dir);
    }

    function remove_file(
            string $dir,
            string $file
    ) {
        $root = __DIR__ . '/../../../';
        $path = $root . $dir . '/' . $file;
        unlink($path);
    }

    function get_file_content(
            string $dir = ''
    ) {
        $root = __DIR__ . '/../../../';
        $result = file_get_contents($root . $dir);
        return $result;
    }

    function clean_up_dir(
            string $dir,
            int $expire = 60
    ) {
        $path = __DIR__ . '/../../../' . $dir;
        $dir_content = scandir($path);
        foreach ($dir_content as $file) {
            if (($file != '.') && ($file != '..')) {
                $file_time = filemtime($path . '/' . $file);
                $diff = (time() - $file_time) / 60;
                if ($diff > $expire) {
                    $this->remove_file($dir, $file);
                }
            }
        }
    }

    function create_dir(
            string $dir
    ) {
        $result = false;
        $dir = __DIR__ . '/../../../' . $dir;
        if (!file_exists($dir) && !is_dir($dir)) {
            $result = mkdir($dir);
        }
        return $result;
    }

    function remove_dir(
            $dir
    ) {
        $path = __DIR__ . '/../../../' . $dir;
        $content = scandir($path);
        foreach ($content as $ent) {
            if ($ent != "." && $ent != "..") {
                if (is_dir($path . "/" . $ent)) {
                    $this->remove_dir($dir . "/" . $ent);
                } else {
                    unlink($path . "/" . $ent);
                }
            }
        }

        return rmdir($path);
    }

}
