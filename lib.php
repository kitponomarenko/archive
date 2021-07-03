<?php
include 'php/archive/layout/layout_top.php';
?>

<div class="main_wrap">
    <div class="lib_wrap">
        <div>
            <div>
                <h3>Читальный зал</h3>
                <p>воспользуйтесь поиском, чтобы найти нужный вам документ</p>
            </div>
            <div class="search_controls">
                <div id="search_filters">
                    <button data-filter_type="protocols" class="active">по документам</button>
                    <button data-filter_type="items_catalog">предметный</button>
                    <button data-filter_type="names_catalog">именной</button>
                </div>
                <input  id="search_formula" type="text" placeholder="например, “население”">
                <label for="search_formula">поисковый запрос</label>
            </div>
            <div></div>
        </div>
        <div>
            <h3>Результаты поиска</h3>
            <div id="search_results" class="search_results">

            </div>
            <div id="search_more" class="btn_more">

            </div>
        </div>
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
