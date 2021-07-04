<?php
include 'php/archive/layout/layout_top.php';

$admin = new archive\catalog\admin();
$invs_content = $admin->get_invs($page['content']['fund']);
?>

<div class="main_wrap">
    <div class="cabinet_wrap">
        <div>
            <h3>Создать опись</h3>
            <div class="add_fund_form">
                <div>
                    <input  id="inv_num" type="num" placeholder="5" min="1">
                    <label for="fund_num">номер описи<span> *</span></label>
                </div>
                <button id="create_inv" class="button_brand_back">создать</button>
            </div>
        </div>
        <div class="cabinet_side" id="inv_data" data-fund="<?= $page['content']['fund'] ?>">
            <h3>Описи фонда № <?= $page['content']['fund'] ?></h3>
            <div id="invs_results" class="search_results"><?= $invs_content['content'] ?></div>
            <div id="invs_more" class="btn_more"><?= $invs_content['more'] ?></div>
        </div>
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
