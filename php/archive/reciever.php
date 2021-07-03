<?php

$class = filter_input(INPUT_POST, 'class');
$method = filter_input(INPUT_POST, 'method');
$params = filter_input(INPUT_POST, 'params', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
$arg = filter_input(INPUT_POST, 'arg', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
if (isset($params)) {
    $params = array_values($params);
} else {
    $params = [];
}

if (isset($arg)) {
    $arg = array_values($arg);
} else {
    $arg = [];
}

include 'loader.php';

$class_name = '\archive\\' . $class;
$class_obj = new $class_name(...$arg);
$response = $class_obj->$method(...$params);

echo json_encode($response);
exit;