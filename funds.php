<?php
include 'php/archive/layout/layout_top.php';

$admin = new archive\catalog\admin();
$funds_content = $admin->get_funds();
?>

<div class="main_wrap">
    <div class="cabinet_wrap">
        <div>
            <h3>Создать фонд</h3>
            <div class="add_fund_form">
                <div>
                    <input  id="fund_num" type="num" placeholder="5" min="1">
                    <label for="fund_num">номер фонда<span> *</span></label>
                </div>
                <button id="create_fund" class="button_brand_back">создать</button>
            </div>

        </div>
        <div class="cabinet_side">
            <h3>Фонды</h3>
            <div id="funds_results" class="search_results"><?= $funds_content['content'] ?></div>
            <div id="funds_more" class="btn_more"><?= $funds_content['more'] ?></div>
        </div>
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
