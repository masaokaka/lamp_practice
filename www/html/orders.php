<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'orders.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//管理ユーザーでログインしていた場合は全ての購入履歴を取得
if(is_admin($user)===false){
  //特定ユーザーの購入履歴のみ取得
  $orders = get_user_order_details($db, $user['user_id']);
} else {
  //全ての購入履歴を取得
  $orders = get_all_order_details($db);
}

$total_price = sum_orders($orders);

include_once VIEW_PATH . 'orders_view.php';