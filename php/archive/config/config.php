<?php

namespace archive\config;

class config {

    private $config;

    function __construct() {
        $this->config = [
            'site_name' => 'archive',
            'site_owner' => 'kitponomarenko', 
            'site_url' => 'https://kitponomarenko.ru/cipher',
            'site_prefix' => 'archive', 
            'site_email' => 'kitponomarenko@gmail.com',

            'host' => 'localhost',
            'database_name' => 'user6221_cipher',
            'master_login' => 'user6221_cphr_master',
            'master_password' => 'NQmA*l,;hkRx',
            'writer_login' => 'user6221_cphr_writer',
            'writer_password' => 'fX}=G_&w76(m',
            'reader_login' => 'user6221_cphr_reader',
            'reader_password' => 'oU,]QQ5FU@_X',

            'code_length' => 10,
            'code_char_set' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

            'first_name_mask' => '/[a-zа-я]{2,}/iu',
            'second_name_mask' => '/[a-zа-я]{2,}/iu',
            'third_name_mask' => '/[a-zа-я]{2,}/iu'
        ];
    }

    function get_config(){
        return $this->config;
    }
    
    function get_site_pfx(){
        return $this->get_config()['site_prefix'];
    }
    
    function get_site_owner(){
        return $this->get_config()['site_owner'];
    }
    
    function get_site_url(){
        return $this->get_config()['site_url'];
    }
        
    function get_site_email(){
        return $this->get_config()['site_email'];
    } 

}