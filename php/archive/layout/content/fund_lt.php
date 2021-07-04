<?php

$fund = $content['fund'];
$query = $this->db->get_query('invs', "WHERE fund='$fund'");
$invs = mysqli_num_rows($query);

return '<a class="fund_ent" href="invs?id=' . $content['id'] . '">
            <p>№ ' . $fund . '</p>
            <div>
                    <p>описей</p>
                    <p>' . $invs . '</p>

            </div>
    </a>';
