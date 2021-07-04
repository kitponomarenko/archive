<?php

namespace archive\tools;

include __DIR__ . '/../loader.php';

use archive\database\database;

class mail {

    private $db;

    function __construct() {
        $this->db = new database();
    }

    function bake_mail(
            string $mail_label,
            array $params
    ) {
        $result = [];

        $mail_data = $this->db->fetch_query_loc('mail', "WHERE label='$mail_label'");
        $result['from'] = $mail_data['sender'];
        $result['subject'] = '' . $mail_data['subject'] . '';
        if (empty($params)) {
            $result['message'] = $this->stylize_mail($mail_data['message']);
        } else {
            $result['message'] = $this->stylize_mail(vsprintf($mail_data['message'], $params));
        }
        
        return $result;
    }

    function stylize_mail(
            $message
    ) {
        $msg_block = '<div style="max-width: 640px; margin: 0 auto; padding: 32px 0; box-sizing: border-box;}">                
                <div style="width: 100%; padding: 32px 0">
                    '.$message.'
                </div>
            </div>';
        
        return $msg_block;
    }

    function send_mail(
            string $mail_label,
            string $mail_to,
            array $params
    ) {
        $mail_data = $this->bake_mail($mail_label, $params);
        $from = $mail_data['from'];

        $message_ready = '
                        <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                            <title>' . $mail_data['subject'] . '</title>
       
                        </head>
                        <html>                            
                            ' . $mail_data['message'] . '                            
                        </html>';
        $headers = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: $from\r\n";
        return mail($mail_to, $mail_data['subject'], $message_ready, $headers);
    }

}
