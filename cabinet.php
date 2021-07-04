<?php
include 'php/archive/layout/layout_top.php';
?>

<div class="main_wrap">
    <div class="cabinet_wrap">
        <div class="user_data">
            <h3>Личный кабинет</h3>
            <p class="user_status">администратор</p>
            <p>Пономаренко Никита Дмитриевич</p>
            <p>18.09.1994 года рождения</p>
            <p>kitponomarenko@gmail.com</p>
            <a class="button_brand_back">изменить данные</a>
        </div>
        <div>
            <a class="home_card_wrap" href="funds">
                <div>
                    <h3>Управление фондами</h3>
                    <p>создание фондов, описей, дел, ручная загрузка сканов, синхронизация с БД</p>
                </div>
                <div>
                    <p>01</p>
                    <svg width="130" height="16" viewBox="0 0 130 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 7H0V9H1V7ZM129.707 8.70711C130.098 8.31658 130.098 7.68342 129.707 7.29289L123.343 0.928932C122.953 0.538408 122.319 0.538408 121.929 0.928932C121.538 1.31946 121.538 1.95262 121.929 2.34315L127.586 8L121.929 13.6569C121.538 14.0474 121.538 14.6805 121.929 15.0711C122.319 15.4616 122.953 15.4616 123.343 15.0711L129.707 8.70711ZM1 9H129V7H1V9Z" fill="#020100"/>
                    </svg>
                </div>
            </a>
            <a class="home_card_wrap" href="autoload">
                <div>
                    <h3>Автозагрузка</h3>
                    <p>автоматическое добавление фондов из zip архива</p>
                </div>
                <div>
                    <p>02</p>
                    <svg width="130" height="16" viewBox="0 0 130 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 7H0V9H1V7ZM129.707 8.70711C130.098 8.31658 130.098 7.68342 129.707 7.29289L123.343 0.928932C122.953 0.538408 122.319 0.538408 121.929 0.928932C121.538 1.31946 121.538 1.95262 121.929 2.34315L127.586 8L121.929 13.6569C121.538 14.0474 121.538 14.6805 121.929 15.0711C122.319 15.4616 122.953 15.4616 123.343 15.0711L129.707 8.70711ZM1 9H129V7H1V9Z" fill="#020100"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</div>

<?php
include 'php/archive/layout/layout_bottom.php';
