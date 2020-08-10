<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'orders.css'); ?>">
</head>
<body>
  <!--別ファイル参照でヘッダーを表示-->
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">
    <!--エラーがあればここで表示-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($orders) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $order){ ?>
          <tr>
            <td><?php print(h($order['order_id'])); ?></td>
            <td><?php print(h($order['order_date'])); ?></td>
            <td>合計金額: <?php print number_format(h($total_price)); ?>円</td>
            <!--購入履歴詳細画面へ飛ぶボタン-->
            <td>
                <form method="post" action="order_details.php">
                <input type="submit" value="詳細" class="btn btn-secondary">
                <input type="hidden" name="order_id" value="<?php print(h($order['order_id'])); ?>">
                <!--トークンを送信-->
                <input type="hidden" name= "token" value="<?php print $token; ?>">
              </form>
          </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>