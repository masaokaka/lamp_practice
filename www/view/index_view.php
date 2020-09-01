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
          <form method="get" action="index.php" >
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

  <!--ページネーション-->
  <div style="text-align:center;">
      <?php 
        $item_per_page = count($items);
        $All_item_amount = count($All_items);
        //総アイテム数を1ページに表示する数で割り、切り上げた数が総ページ数
        $total_page = ceil(count($All_items)/ITEMS_PER_PAGE);
        //現在のページ取得
        if(isset($_GET['page'])===TRUE){
          $current_page = $_GET['page'];
        } else {
          $current_page = 1;
        }
      ?>
      <!--件数表示-->
      <p>
        全<?php print count($All_items);?>件中
        <?php
          if($current_page === 1){
            print 1;
          } else {
            print ($All_item_amount + 1) - ($All_item_amount - (($current_page -1) * ITEMS_PER_PAGE));
          }
        ?>〜
        <?php 
          if($current_page === 1){
            print $item_per_page;
          } else {
            print (($current_page - 1) * ITEMS_PER_PAGE) + $item_per_page;
          }
        ?>件
      </p>
      <!--ページごとリンク-->
      <div>
        <?php
        $page = 1; 
        while($total_page > 0){ 
          //現在のページと表示するページ番号が同じの場合はリンクなしの普通の文字
          if($page == $current_page){ ?>
            <span><?php print $page;?></span>
          <?php }else { 
          //並び替えが入った状態で次のページに行っても並び替えが継承されて商品を表示
          if(isset($_GET['order_by'])){
            $order = $_GET['order_by'];
          }?>
            <span><a href="?page=<?php print $page; ?>&order_by=<?php print $order;?>"><?php print $page; ?></a></span>
          <?php }
            $total_page--;
            $page++;
        } ?>
      </div>
  </div>
  
</body>
</html>