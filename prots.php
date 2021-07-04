<?php
include 'php/archive/layout/layout_top.php';

$admin = new archive\catalog\admin();
$doc_content = $admin->get_docs($page['content']['fund'], $page['content']['inv']);
?>

<div class="main_wrap">
    <div class="cabinet_wrap">
        <div class="autoload_wrap">
            <h3>Загрузить из описи</h3>
            <p>загрузить список дел и каталоги из любой описи в формате .doc</p>
            <div class="add_inv_form">
                 <form class="autoload_form">
                    <div class="file_list">
                        <input type="text" id="inv_doc_files" name = "inv_doc"  hidden>
                        <progress class="progress_bar" id="inv_doc_progress" value="0" max="100" hidden></progress>
                        <div id="inv_doc_list" class="uploaded_files"></div>                    
                    </div>
                    <label class="input_file" id="inv_doc_input">
                        <p>+ загрузить опись.doc</p>
                        <input type="file" name="file[]" id="inv_doc" data-type="inv_doc">
                    </label>                
                </form>
                <button id="import_inv" class="button_brand_back" hidden>импортировать</button>
            </div>
        </div>
        <div class="cabinet_side" id="inv_data" data-fund="<?= $page['content']['fund'] ?>" id="inv_data" data-inv="<?= $page['content']['inv'] ?>">
            <h3>Документы описи № <?= $page['content']['inv'] ?> фонда № <?= $page['content']['fund'] ?></h3>
            <div id="docs_results" class="search_results"><?= $doc_content['content'] ?></div>
            <div id="docs_more" class="btn_more"><?= $doc_content['more'] ?></div>
        </div>
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
