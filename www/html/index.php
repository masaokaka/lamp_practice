<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
//表示中のページを取得
if(isset($_GET['page'])){
  $page = (int)$_GET['page'];
} else{
  $page = 1;
}
//ページ数に応じて表示するデータを取得
$items = get_open_items($db,$page);
//全てのデータを取得
$All_items = get_open_items($db, null);

include_once VIEW_PATH . 'index_view.php';