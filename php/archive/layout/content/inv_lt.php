<?php

$fund = $content['fund'];
$inv = $content['inv'];
$query = $this->db->get_query('protocols', "WHERE fund='$fund' AND inv='$inv'");
$docs = mysqli_num_rows($query);

return '<a class="inv_ent" href="prots?id=' . $content['id'] . '">
            <div>
                <p>№ ' . $inv . '</p>
                <p>фонд ' . $fund . '</p>   
            </div>
            <div>
                    <p>документов</p>
                    <p>' . $docs . '</p>

            </div>
    </a>';
