<?php

spl_autoload_register(function ($class_name) {
    $class = str_replace('\\', '/', $class_name);   
    $class_file = __DIR__ . '/../' . $class . '.php';
    if (is_readable($class_file)) {
        include $class_file;
    }
});