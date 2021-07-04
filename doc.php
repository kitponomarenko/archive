<?php
include 'php/archive/layout/layout_top.php';

$page_id = filter_input(INPUT_GET, 'page');
if (!isset($page_id)) {
    $page_id = 1;
}

$db = new archive\database\database();

$fund = $page['content']['fund'];
$inv = $page['content']['inv'];
$num = $page['content']['num_old'];

$pages_query = $db->get_query('entries', "WHERE fund='$fund' AND inv='$inv' AND doc='$num'");
$total_pages = mysqli_num_rows($pages_query);
if ($total_pages == 0) {
    $total_pages = 1;
}

$next_pr = $page_id + 1;
$next = $db->fetch_query('entries', "WHERE fund='$fund' AND inv='$inv' AND doc='$num' AND priority='$next_pr'", 'id, priority');
if (empty($next)) {
    $next_page = 1;
} else {
    $next_page = $next['priority'];
}

$prev_pr = $page_id - 1;
$prev = $db->fetch_query('entries', "WHERE fund='$fund' AND inv='$inv' AND doc='$num' AND priority='$prev_pr'", 'id, priority');
if (empty($prev)) {
    $prev_page = $total_pages;
} else {
    $prev_page = $prev['priority'];
}

$page_data = $db->fetch_query('entries', "WHERE fund='$fund' AND inv='$inv' AND doc='$num' AND priority='$page_id'");
$views = $page_data['views'] + 1;
$entry_id = $page_data['id'];
$db->update_row('entries', ['views' => $views], "WHERE id='$entry_id'");

$scan = 'fond/Фонд ' . $fund . '/Опись ' . $inv . '/д.' . $page['content']['num_old'] . '/' . $page_data['entry'];

$doc_not_ready = '';
if ($page['content']['ready'] == 0) {
    $scan = '';
    $doc_not_ready = '<p class="doc_not_ready">К сожалению, в настоящий момент сканы документа отсутствуют в электронной версии архива</p>';
}
?>

<div class="main_wrap">
    <div class="doc_wrap">
        <div class="doc_bar left">
            <button class="doc_close" id="doc_close">закрыть документ</button>  
            <div class="doc_view_controls">   
                <div><p>масштаб</p><p><span id="doc_zoom_scale">100</span>%</p></div>
                <div>                    
                    <button id="doc_zoom_out">-</button>
                    <button id="doc_zoom_reset">•</button>
                    <button id="doc_zoom_in">+</button>                    
                </div>
            </div>
        </div>
        <div id="doc_viewport" class="doc_viewport">
            <?= $doc_not_ready ?>
            <div style="background-image: url('<?= $scan ?>')"></div>
        </div>
        <div class="doc_bar right">
            <div class="doc_info">
                <h5><?= $page['content']['title'] ?></h3>
                    <p><?= $page['content']['date'] ?></p>
            </div>
            <div class="doc_page_info">
                <p>фонд <?= $fund ?></p>
                <p>опись <?= $inv ?></p>
                <p>документ №<?= $page['content']['num'] ?> (<?= $num ?>)</p>
                <p><?= $page_data['entry'] ?></p>
                <p>просмотрено, раз: <?= $views ?></p>
            </div>
            <div class="doc_page_controls">   
                <div><p>страница</p><p><span id="doc_page_num"><?= $page_id ?></span>/<?= $total_pages ?></p></div>
                <div>                    
                    <button name="doc_page_switch" data-link="doc?id=<?= $page['content']['id'] ?>&page=<?= $prev_page ?>">
                        <svg width="130" height="16" viewBox="0 0 130 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 7H0V9H1V7ZM129.707 8.70711C130.098 8.31658 130.098 7.68342 129.707 7.29289L123.343 0.928932C122.953 0.538408 122.319 0.538408 121.929 0.928932C121.538 1.31946 121.538 1.95262 121.929 2.34315L127.586 8L121.929 13.6569C121.538 14.0474 121.538 14.6805 121.929 15.0711C122.319 15.4616 122.953 15.4616 123.343 15.0711L129.707 8.70711ZM1 9H129V7H1V9Z" fill="#020100"/>
                        </svg>
                    </button>
                    <button name="doc_page_switch" data-link="doc?id=<?= $page['content']['id'] ?>&page=<?= $next_page ?>">
                        <svg width="130" height="16" viewBox="0 0 130 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 7H0V9H1V7ZM129.707 8.70711C130.098 8.31658 130.098 7.68342 129.707 7.29289L123.343 0.928932C122.953 0.538408 122.319 0.538408 121.929 0.928932C121.538 1.31946 121.538 1.95262 121.929 2.34315L127.586 8L121.929 13.6569C121.538 14.0474 121.538 14.6805 121.929 15.0711C122.319 15.4616 122.953 15.4616 123.343 15.0711L129.707 8.70711ZM1 9H129V7H1V9Z" fill="#020100"/>
                        </svg>
                    </button>                    
                </div>
            </div>
        </div>        
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
