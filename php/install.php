<?php

echo "<h3>CIPHER ARCHIVE DATABASE STRUCTURE ARCHIVE</h3><br>";

include 'archive/loader.php';
$db = new archive\database\database();

$tbl_arr = [
    'funds' =>
    "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            fund VARCHAR(100) NOT NULL
	)",
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
	)",
    'pages' =>
    "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            url VARCHAR(255) NOT NULL,
            access VARCHAR(255) NOT NULL,
            title VARCHAR(255) NOT NULL,	
            description VARCHAR(255) NOT NULL,
            keywords VARCHAR(255) NOT NULL,
            js LONGTEXT NOT NULL,
            css LONGTEXT NOT NULL,
            parent_id INT NOT NULL DEFAULT 0,
            inherit VARCHAR(6) NOT NULL DEFAULT 'false',
            content_tbl VARCHAR(255) NOT NULL,
            dynamic_meta VARCHAR(6) NOT NULL DEFAULT 'false',
            redirect_id INT NOT NULL DEFAULT 1,
            priority INT NOT NULL DEFAULT 1,
            active VARCHAR(6) NOT NULL DEFAULT 'true'            
	)",
    'users' =>
    "(
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            access VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            first_name VARCHAR(255) NOT NULL,	
            second_name VARCHAR(255) NOT NULL,
            third_name VARCHAR(255) NOT NULL,
            birthday DATE NOT NULL,            
            password TEXT NOT NULL,
            token TEXT NOT NULL, 
            new_password TEXT NOT NULL,
            new_email VARCHAR(255) NOT NULL,
            new_token TEXT NOT NULL     
	)"
];

foreach ($tbl_arr as $key => $val) {
    $result = $db->create_table($key, $val);
    if ($result) {
        echo "<b>S U C C E S S:</b> table $key has been created<br>";
    }
}

exit;
