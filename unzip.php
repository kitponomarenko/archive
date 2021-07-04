<?php

include 'php/archive/loader.php';

$zip = filter_input(INPUT_GET, 'zip');
if (isset($zip)) {
    echo 'Пожалуйста, не закрывайте страницу подождите окончания распаковки файла - это может занять некоторое время.<br><br>';
    $admin = new archive\catalog\admin();
    echo $admin->install_zip($zip);
}else{
    echo 'Пожалуйста, укажите в адресной строке параметр "zip", должно быть примерно так: "unzip?zip=Фонд 6-20210703T160112Z-001.zip".<br><br>';
}