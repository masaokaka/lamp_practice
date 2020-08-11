<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
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
$order_id = get_post('order_id');

//管理ユーザーでログインしていた場合は全ての購入履歴を取得
if(is_admin($user)===false){
  //ログイン中のユーザーの購入履歴詳細
  $order_details = get_user_order_details($db, $order_id, $user['user_id']);
} else {
  //全ての購入履歴詳細
  $order_details = get_all_order_details($db, $order_id);
}

$total_price = sum_orders($order_details);

include_once VIEW_PATH . 'order_details_view.php';