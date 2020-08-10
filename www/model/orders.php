<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//全ての購入履歴を取得
function get_all_orders($db){
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      orders.user_id
    FROM
      orders
  ";
  return fetch_all_query($db, $sql);
}

//ユーザーの購入履歴のみを取得
function get_user_orders($db, $user_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      orders.user_id
    FROM
      orders
    WHERE
      orders.user_id = ?
  ";
  return fetch_all_query($db, $sql, [$user_id]);
}

//全ての購入履歴を詳細まで取得
function get_all_order_details($db){
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      orders.user_id,
      order_details.item_id,
      order_details.amount,
      order_details.price
    FROM
      orders
    JOIN
      order_details
    ON
      orders.order_id = order_details.order_id
  ";
  return fetch_all_query($db, $sql);
}


//ユーザーの購入履歴を詳細まで取得
function get_user_order_details($db, $user_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      orders.user_id,
      order_details.item_id,
      order_details.amount,
      order_details.price
    FROM
      orders
    JOIN
      order_details
    ON
      orders.order_id = order_details.order_id
    WHERE
      orders.user_id = ?
  ";
  return fetch_all_query($db, $sql, [$user_id]);
}

function sum_orders($orders){
  $total_price = 0;
  foreach($orders as $order){
    $total_price += $order['price'] * $order['amount'];
  }
  return $total_price;
}