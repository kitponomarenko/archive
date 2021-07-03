<?php

namespace archive\page;

include __DIR__ . '/../loader.php';

use archive\config\config;

class cookie {

    private $config;

    function __construct() {
        $this->config = new config();
    }

    function set_cookie(
            string $cookie_name = 'cookies_accepted',
            string $value = 'accepted',
            int $time = (3600 * 24 * 30)
    ) {
        $cookie_name = $this->config->get_site_pfx() . '_' . $cookie_name;
        return setcookie($cookie_name, $value, time() + $time, '/');
    }

    function unset_cookie(
            string $cookie_name = ''
    ) {
        $cookie_name = $this->config->get_site_pfx() . '_' . $cookie_name;
        if (isset($cookie_name)) {
            setcookie($cookie_name, null, -1, '/');
        }
    }

}
