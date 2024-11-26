-- cash_registerデータベースの全テーブルのデータに対する操作権限を付与
GRANT SELECT, INSERT, UPDATE, DELETE ON cash_register.* TO 'regi_db_user'@'%';

-- データベースの他の操作を制限する
REVOKE ALL PRIVILEGES ON *.* FROM 'regi_db_user'@'%';
GRANT USAGE ON *.* TO 'regi_db_user'@'%';


USE cash_register;

-- レジのユーザーテーブル
CREATE TABLE IF NOT EXISTS users(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_name TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    salt TEXT NOT NULL
);

-- 商品テーブル
CREATE TABLE IF NOT EXISTS items(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_name TEXT NOT NULL,
    price BIGINT NOT NULL,
    item_image LONGBLOB NOT NULL,
    last_update DATETIME NOT NULL,
    delete_flag BOOLEAN NOT NULL
);

-- 在庫テーブル
CREATE TABLE IF NOT EXISTS stocks(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id BIGINT UNSIGNED NOT NULL,
    quantity BIGINT NOT NULL,
    last_update DATETIME NOT NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- 会計テーブル
CREATE TABLE IF NOT EXISTS accountants(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    total_amount BIGINT NOT NULL,
    total_price BIGINT NOT NULL,
    accountant_user_name TEXT NOT NULL
);

-- 会計詳細テーブル
CREATE TABLE IF NOT EXISTS details(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    accountant_id BIGINT UNSIGNED NOT NULL,
    item_id BIGINT UNSIGNED NOT NULL,
    item_name TEXT NOT NULL,
    item_price BIGINT NOT NULL,
    quantity BIGINT NOT NULL,
    subtotal BIGINT NOT NULL,
    FOREIGN KEY (accountant_id) REFERENCES accountants(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- 取引記録テーブル
CREATE TABLE IF NOT EXISTS transactions(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    accountant_id BIGINT UNSIGNED NOT NULL,
    total_price BIGINT NOT NULL,
    received_price BIGINT NOT NULL,
    returned_price BIGINT NOT NULL,
    FOREIGN KEY (accountant_id) REFERENCES accountants(id) ON DELETE CASCADE
);

-- モバイルオーダーの注文情報
CREATE TABLE IF NOT EXISTS order_orders(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    total_amount BIGINT NOT NULL,
    total_price BIGINT NOT NULL,
    is_received BOOLEAN NOT NULL
);

-- モバイルオーダーの注文詳細
CREATE TABLE IF NOT EXISTS order_details(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    item_id BIGINT UNSIGNED NOT NULL,
    item_name TEXT NOT NULL,
    item_price BIGINT NOT NULL,
    quantity BIGINT NOT NULL,
    subtotal BIGINT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES order_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- 通知テーブル
CREATE TABLE IF NOT EXISTS notifications(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title TEXT NOT NULL,
    message TEXT NOT NULL,
    sent_date DATETIME NOT NULL
);

-- 初期ユーザー作成
INSERT INTO users ( user_name, password_hash, salt ) VALUES(
     "admin",
     "$argon2id$v=19$m=65536,t=4,p=1$bzNPc3dnbFY1bTVzUGJ4Lw$U9FiytBnm3XI/CoObqtalFADEWIIHsEkTKhCJQxk6TY",
     "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
);