<?php 

//ユーザーの購入履歴を取得
function get_user_orders($db, $user_id = null){
  $user = array();
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      (SELECT SUM(price * amount) FROM order_details WHERE order_id = orders.order_id)
    AS
      total
    FROM
      orders"
    ;
    if($user_id!==null){
      $user[] = $user_id;
      $sql .= " WHERE
              user_id = ? ";
    }

  return fetch_all_query($db, $sql, $user);
}

//全てのユーザーの注文の詳細データの取得（管理者のみ）
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

//特定ユーザーの注文の詳細データの取得
function get_user_order_details($db, $order_id, $user_id){
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
      orders.order_id = ? AND orders.user_id = ?
  ";

  return fetch_all_query($db, $sql, [$order_id, $user_id]);
}
//商品の小計を表示
function sum_orders($order_details){
  $total_price = 0;
  foreach($order_details as $details){
    $total_price += $details['price'] * $details['amount'];
  }
  return $total_price;
}