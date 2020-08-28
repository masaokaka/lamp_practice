<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <!--メッセージを表示-->
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    
    <!--商品の並べ替え機能を追加-->
    <div style="text-align: right;">
          <form method="get" action="index_order_by.php" >
            <select name="order_by" style="padding: 2px 0 7px; vertical-align: middle;">
              <option value="new" <?php if($order === 'new'){ print 'selected';} ?>>新着順</option>
              <option value="low" <?php if($order === 'low'){ print 'selected';} ?>>価格の安い順</option>
              <option value="high" <?php if($order === 'high'){ print 'selected';} ?>>価格の高い順</option>
            </select>
            <input type="submit" value="並び替え" class="btn btn-primary btn-sm">
          </form>
    </div>
    
    <!--商品一覧-->
    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print(h($item['name'])); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . h($item['image'])); ?>">
              <figcaption>
                <?php print(number_format(h($item['price']))); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print(h($item['item_id'])); ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  
</body>
</html>