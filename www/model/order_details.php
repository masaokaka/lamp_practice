<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//全ての購入履歴詳細を取得
function get_all_order_details($db, $order_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      orders.user_id,
      order_details.order_id,
      order_details.item_id,
      order_details.amount,
      order_details.price,
      items.name
    FROM
      order_details
    JOIN
      orders
    ON
      orders.order_id = order_details.order_id
    JOIN
      items
    ON
      order_details.item_id = items.item_id
    WHERE
      orders.order_id = ? 
  ";
  return fetch_all_query($db, $sql, [$order_id]);
}


//ユーザーの購入履歴を詳細まで取得
function get_user_order_details($db, $user_id, $order_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      orders.user_id,
      order_details.order_id,
      order_details.item_id,
      order_details.amount,
      order_details.price,
      items.name
    FROM
      order_details
    JOIN
      orders
    ON
      orders.order_id = order_details.order_id
    JOIN
      items
    ON
      order_details.item_id = items.item_id
    WHERE
      orders.user_id = ? AND orders.order_id = ? 
  ";
  return fetch_all_query($db, $sql, [$user_id, $order_id]);
}

function sum_orders($order_details){
  $total_price = 0;
  foreach($order_details as $details){
    $total_price += $details['price'] * $details['amount'];
  }
  return $total_price;
}
