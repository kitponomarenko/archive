<?php

namespace archive\page;

include __DIR__ . '/../loader.php';

use archive\database\database;
use archive\user\user_access;

class page {

    private $db;
    private $uaccess;

    function __construct() {
        $this->db = new database();
        $this->uaccess = new user_access();
    }

    function get_actual_page(
            string $type
    ) {
        $url = filter_input(INPUT_SERVER, $type);
        $url_arr = explode('/', explode('?', $url)[0]);
        $page = array_pop($url_arr);
        if ($page == '') {
            $page = 'index';
        }

        return $page;
    }

    function get_current_page() {
        return $this->get_actual_page('REQUEST_URI');
    }

    function get_page_script() {
        return $this->get_actual_page('HTTP_REFERER');
    }

    function get_page() {
        $page_url = $this->get_current_page();

        $page = $this->db->fetch_query('pages', "WHERE url='$page_url'");

        $user_access_arr = $this->uaccess->check_user_access($page['access']);
        if ($user_access_arr['result'] == true) {
            $page_data_arr = $this->get_page_data($page);
        } else {
            $this->redirect_page($page['redirect_id']);
        }

        return [
            'meta' => $page_data_arr['meta'],
            'expansion' => $page_data_arr['expansion'],
            'content' => $page_data_arr['content']
        ];
    }

    function get_page_data(
            $page
    ) {
        $page_meta = [
            'title' => $page['title'],
            'description' => $page['description'],
            'keywords' => $page['keywords']
        ];
        $page_expansion = $this->complete_page_expansion($page);
        $content = '';
        if (!empty($page['content_tbl'])) {
            $dynamic_content = $this->get_page_content($page);
            $content = $dynamic_content['content'];
            if ($dynamic_content['meta'] != null) {
                foreach ($dynamic_content['meta'] as $key => $val) {
                    if (!empty($val)) {
                        $page_meta[$key] = $val;
                    }
                }
            }
        }

        return [
            'meta' => $page_meta,
            'expansion' => $page_expansion,
            'content' => $content
        ];
    }

    function complete_page_expansion(
            $page
    ) {
        $result = [
            'css' => '',
            'js' => ''
        ];

        if (($page['parent_id'] != 0) && ($page['inherit'] == 'true')) {
            $parent_id = $page['parent_id'];
            $parent = $this->db->fetch_query('pages', "WHERE id='$parent_id'");
            $page_active_expansion = $this->get_page_expansion($parent);
            $result['css'] .= $page_active_expansion['css'];
            $result['js'] .= $page_active_expansion['js'];
        }

        $page_expansion = $this->get_page_expansion($page);
        $result['css'] .= $page_expansion['css'];
        $result['js'] .= $page_expansion['js'];

        return $result;
    }

    function get_page_expansion(
            $page
    ) {
        $result = [
            'css' => '',
            'js' => ''
        ];

        foreach ($result as $type => $expansion) {
            $exp_arr = explode(',', $page[$type]);
            foreach ($exp_arr as $exp) {
                if (!empty($exp)) {
                    if ($type == 'css') {
                        $result['css'] .= '<link rel="stylesheet" href="css/' . $exp . '.css">';
                    } else if ($type == 'js') {
                        $result['js'] .= '<script src="js/' . $exp . '.js"></script>';
                    }
                }
            }
        }

        return $result;
    }

    function get_page_content(
            $page
    ) {
        $content_id = filter_input(INPUT_GET, 'id');

        $content_state = false;
        if (isset($content_id)) {
            $content = $this->db->fetch_query($page['content_tbl'], "WHERE id='$content_id'");
            $content_state = true;
            if (empty($content)) {
                $content_state = false;
            }
        }

        if ($content_state == false) {
            $this->redirect_page($page['redirect_id']);
        } else {
            if ($page['dynamic_meta'] == 'true') {
                $page_meta = [
                    'title' => '',
                    'description' => '',
                    'keywords' => ''
                ];
                foreach ($page_meta as $key => $val) {
                    if (isset($content[$key])) {
                        $page_meta[$key] = $content[$key];
                    }
                }
            }
        }

        return [
            'content' => $content,
            'meta' => $page_meta
        ];
    }

    function redirect_page(
            $redirect_id
    ) {
        if ($redirect_id != 0) {
            $redirect_url = $this->db->fetch_query('pages', "WHERE id='$redirect_id'")['url'];
            header('Location: ' . $redirect_url);
            exit;
        }
    }

}
