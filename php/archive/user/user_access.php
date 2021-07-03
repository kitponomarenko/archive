<?php

namespace archive\user;

include __DIR__ . '/../loader.php';

use archive\database\database;
use archive\config\config;

class user_access {

    private $token_cookie_name;
    private $token_cookie;
    private $common_access;
    private $guest_acces;
    private $user_access;
    private $admin_access;
    private $db;
    private $config;

    function __construct() {

        $this->config = new config();
        $this->token_cookie_name = $this->config->get_site_pfx() . '_token';
        $this->token_cookie = filter_input(INPUT_COOKIE, $this->token_cookie_name);
        $this->common_access = [1];
        $this->guest_acces = [1, 2];
        $this->user_acces = [1, 3];
        $this->admin_acces = [1, 3, 4];
        $this->db = new database();
    }

    function get_token_cookie_name() {
        return $this->token_cookie_name;
    }

    function get_token_cookie() {
        return $this->token_cookie;
    }

    function get_common_access() {
        return $this->common_access;
    }

    function get_guest_access() {
        return $this->guest_acces;
    }

    function open_session() {
        if (session_status() == 1) {
            session_start();
        }
    }

    function get_user_session() {

        $user = false;

        $this->open_session();

        if (isset($this->token_cookie)) {
            $user_token = $this->token_cookie;
            $user = $this->db->fetch_query('users', "WHERE token='$user_token'");
            $_SESSION['id'] = $user['id'];
        }

        if (isset($_SESSION['id'])) {
            $user_id = $_SESSION['id'];
            if (empty($user)) {
                $user = $this->db->fetch_query('users', "WHERE id='$user_id'");
            }
        }

        return $user;
    }


    function get_user_access_arr() {
        $user = $this->get_user_session();
        if ($user == false) {
            $user_access = $this->guest_acces;
        } else {
            $user_access = array_merge($this->common_access, $this->get_access_arr('user', $user['id']));
        }

        return [
            'user' => $user,
            'access' => $user_access
        ];
    }

    function check_user_access(
            $page_access
    ) {
        $result = false;
        
        $user_access_arr = $this->get_user_access_arr();
        $page_access_arr = explode(',', $page_access);
        foreach($page_access_arr as $access){
            if(in_array($access, $user_access_arr['access'])){
                $result = true;
                break;
            }
        }        

        return [
            'result' => $result,
            'user' => $user_access_arr['user']
        ];
    }

    function get_avaible_pages() {
        
        $avaible = [];
        $pages = $this->db->get_query('pages', '', 'id, access');
        while($page = mysqli_fetch_assoc($pages)){
            $check = $this->check_user_access($page['access']);
            if($check['result'] == true){
                $avaible[] = $page['id'];
            }
        }

        return $avaible;
    }

    function user_start_session(
            int $user_id,
            string $user_token,
            int $cookie = 1
    ) {
        if (!empty($user_id)) {
            $this->open_session();
            $_SESSION['id'] = $user_id;

            if ($cookie == 1) {
                setcookie($this->token_cookie_name, $user_token, time() + (3600 * 24 * 30), "/");
            }

            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

}
