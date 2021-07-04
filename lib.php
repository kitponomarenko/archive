<?php
include 'php/archive/layout/layout_top.php';


$search_obj = new archive\catalog\search();
$content = $search_obj->find_docs('%', 'protocols');
?>

<div class="main_wrap">
    <div class="lib_wrap">
        <div>
            <div>
                <h3>Читальный зал</h3>
                <p>воспользуйтесь поиском, чтобы найти нужный вам документ</p>
            </div>
            <div class="search_controls">
                <div>
                    <div id="search_filters">
                        <button data-filter_type="protocols" class="active">по документам</button>
                        <button data-filter_type="items_catalog">предметный</button>
                        <button data-filter_type="names_catalog">именной</button>
                    </div>
                    <button id="search_sort" data-search_sort="ASC"><svg width="8" height="25" viewBox="0 0 8 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.64645 24.3536C3.84171 24.5488 4.15829 24.5488 4.35355 24.3536L7.53553 21.1716C7.7308 20.9763 7.7308 20.6597 7.53553 20.4645C7.34027 20.2692 7.02369 20.2692 6.82843 20.4645L4 23.2929L1.17157 20.4645C0.976311 20.2692 0.659728 20.2692 0.464466 20.4645C0.269204 20.6597 0.269204 20.9763 0.464466 21.1716L3.64645 24.3536ZM4.5 24V0H3.5V24H4.5Z" fill="#FDFFFC"/>
                        </svg>
                    </button>
                </div>
                <input  id="search_formula" type="text" placeholder="например, “население”">
                    <label for="search_formula">поисковый запрос</label>
            </div>
            <div></div>
        </div>
        <div>
            <h3>Каталог</h3>
            <div id="search_results" class="search_results"><?= $content['content'] ?></div>
            <div id="search_more" class="btn_more"><?= $content['more'] ?></div>
        </div>
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
