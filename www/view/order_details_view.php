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
  <h1>購入明細</h1>
  <div class="container">
    <!--エラーがあればここで表示-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($order_details) > 0){ ?>
      <h5>注文番号：<?php print(h($order_id)); ?>
      　　購入日時：<?php print(h($order_details[0]['order_date'])); ?>
      　　合計金額：<?php print number_format(h($total_price)); ?>円
    　</h5>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>商品価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($order_details as $order){ ?>
          <tr>
            <td><?php print(h($order['name'])); ?></td>
            <td><?php print number_format(h($order['price'])); ?>円</td>
            <td><?php print number_format(h($order['amount'])); ?></td>
            <td>小計：<?php print number_format(h($order['price'] * $order['amount'])); ?>円</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入明細はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>