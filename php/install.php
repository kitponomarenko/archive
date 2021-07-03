<?php

echo "<h3>CIPHER ARCHIVE DATABASE STRUCTURE ARCHIVE</h3><br>";

include 'dusty/loader.php';
$db = new dusty\database\database();

$tbl_arr = [
    'items_catalog' =>
    "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fund VARCHAR(100) NOT NULL,
            name VARCHAR(255) NOT NULL,	
            type VARCHAR(255) NOT NULL,
            data LONGTEXT NOT NULL
	)",
    'names_catalog' =>
    "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fund VARCHAR(100) NOT NULL,
            name VARCHAR(255) NOT NULL,	
            role VARCHAR(255) NOT NULL,
            data LONGTEXT NOT NULL
	)",
    'protocols' =>
    "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fund VARCHAR(100) NOT NULL,
            num VARCHAR(100) NOT NULL,	
            num_old VARCHAR(100) NOT NULL,
            title VARCHAR(255) NOT NULL,
            date VARCHAR(255) NOT NULL,
            pages INT NOT NULL,
            extra LONGTEXT NOT NULL
	)"
];

foreach ($tbl_arr as $key => $val) {
    $result = $db->create_table($key, $val);
    if ($result) {
        echo "<b>S U C C E S S:</b> table $key has been created<br>";
    }
}

exit;
