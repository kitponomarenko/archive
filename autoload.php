<?php
include 'php/archive/layout/layout_top.php';
?>

<div class="main_wrap">
    <div class="cabinet_wrap">
        <div class="autoload_wrap">
            <h3>Автозагрузка</h3>
            <p>загружать можно только zip-архивы в кодировке UTF-8, содержащие структуру вида: "Фонд n/Опись m/д.X/<файлы сканов .jpeg/.tiff>"</p>
            <form class="autoload_form">
                <div class="file_list">
                    <input type="text" id="autoload_zip_files" name = "autoload_zip"  hidden>
                    <progress class="progress_bar" id="autoload_zip_progress" value="0" max="100" hidden></progress>
                    <div id="autoload_zip_list" class="uploaded_files"></div>                    
                </div>
                <label class="input_file" id="autoload_zip_input">
                    <p>+ загрузить zip-архив</p>
                    <input type="file" name="file[]" id="autoload_zip" data-type="autoload_zip">
                </label>                
            </form>
            <button id="unpack_zip" class="button_brand_back" hidden>распаковать и установить на сайт</button>
        </div>
        <div class="cabinet_side">
            <h3>Результат</h3>
            <div class="autoload_results" id="autoload_results"></div>
        </div>
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
