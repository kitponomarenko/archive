<?php
include 'php/archive/layout/layout_top.php';
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
            <div style="background-image: url('fond/Фонд 6/Опись 1/д.1/док 001 титульный лист.jpg')"></div>
        </div>
        <div class="doc_bar right">
            <div class="doc_info">
                <h5><?= $page['content']['title'] ?></h3>
                    <p><?= $page['content']['date'] ?></p>
            </div>
            <div class="doc_page_info">
                <p>док 001 титульный лист</p>
            </div>
            <div class="doc_page_controls">   
                <div><p>страница</p><p><span id="doc_page_num"><?= $page['content']['num'] ?></span>/<?= $page['content']['pages'] ?></p></div>
                <div>                    
                    <button id="doc_page_prev">
                        <svg width="130" height="16" viewBox="0 0 130 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 7H0V9H1V7ZM129.707 8.70711C130.098 8.31658 130.098 7.68342 129.707 7.29289L123.343 0.928932C122.953 0.538408 122.319 0.538408 121.929 0.928932C121.538 1.31946 121.538 1.95262 121.929 2.34315L127.586 8L121.929 13.6569C121.538 14.0474 121.538 14.6805 121.929 15.0711C122.319 15.4616 122.953 15.4616 123.343 15.0711L129.707 8.70711ZM1 9H129V7H1V9Z" fill="#020100"/>
                        </svg>
                    </button>
                    <button id="doc_page_next">
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
