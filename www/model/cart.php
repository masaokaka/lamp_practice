<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  return fetch_all_query($db, $sql, [$user_id]);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  return fetch_query($db, $sql, [$user_id,$item_id]);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";

  return execute_query($db, $sql, [$item_id,$user_id,$amount]);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  
  return execute_query($db, $sql, [$amount,$cart_id]);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  return execute_query($db, $sql, [$cart_id]);
}
//購入処理を行う関数
function purchase_carts($db, $carts){
  //商品が購入できるかどうかをユーザー関数(validate_cart_purchase)でチェックする
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  //上記チェックで商品が購入可能だった場合、トランザクション開始して以下の処理を連続して行う
  $db->beginTransaction();
  try {
    //stockテーブルから購入分の商品を引き去る処理を行う
    foreach($carts as $cart){
      if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
      }
      //購入履歴をデータベースに登録する
      insert_orders($db, $cart['user_id'], $cart['item_id'], $cart['amount']);
    }
    //stockテーブルの更新と購入履歴データの登録後、購入済のカート内商品を削除する
    delete_user_carts($db, $carts[0]['user_id']);  
    // コミット処理
    $db->commit();
  } catch (PDOException $e) {
    // ロールバック処理
    $db->rollback();
    // 例外をスロー
    throw $e;
  //エラーメッセージを表示
  } catch (PDOException $e) {
  echo 'データベース処理でエラーが発生しました。理由：'.$e->getMessage();
  }
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  execute_query($db, $sql, [$user_id]);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}
//カート画面から購入ボタンをクリックしたのちに、購入が可能かどうかをチェックする
function validate_cart_purchase($carts){
  //購入時点でカートに商品が入っているかをチェック、なければfalseを返す
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    //ユーザー関数is_openでカートの商品が公開されているかをチェック、非公開の場合はエラー情報を保存
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    //購入する商品数が在庫数を超えていないかチェック、超えていた場合はエラー情報を保存
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  //ここまででエラーが出ていないかをユーザー関数(has_error)でチェック、エラーがあればfalseを返す、なければtrueを返す
  if(has_error() === true){
    return false;
  }
  return true;
}
//購入履歴を登録するユーザー関数
function insert_orders($db, $user_id, $item_id, $amount){
  //購入履歴画面に必要な情報の登録
  $sql = "
    INSERT INTO
      orders(
        user_id,
      )
    VALUES(?)
  ";
  return execute_query($db, $sql, [$user_id]);
  $id = $db->lastInsertId();
  //購入履歴詳細画面に必要な情報の登録
  $sql = "
    INSERT INTO
      order_details(
        order_id,
        item_id,
        amount
      )
    VALUES(?,?,?)
  ";
  return execute_query($db, $sql, [$id, $item_id, $amount]);
}

