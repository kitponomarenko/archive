<?php
    $type = filter_input(INPUT_POST, 'type');
    $count = filter_input(INPUT_POST, 'count');
    
    include 'loader.php';
    $file = new dusty\tools\file();
    $response = $file->upload_file($type,$count);
    
    echo json_encode($response);