--購入履歴画面のテーブル(orders)--
CREATE TABLE `orders` (
    --注文番号--
    `order_id` int(11) NOT NULL AUTO_INCREMENT,
    --注文日時--
    `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    --購入ユーザーID--
    `user_id` int(11) NOT NULL,
    --主キー--
    primary key(order_id)
)

--購入明細画面のテーブル(order_details)--
CREATE TABLE `order_details` (
    --注文番号--
    `order_id` int(11) NOT NULL,
    --商品ID--
    `item_id` int(11) NOT NULL,
    --商品の購入数--
    `amount` int(11) NOT NULL
)
