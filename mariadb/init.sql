USE cash_register;

-- レジのユーザーテーブル
CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(64) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    salt VARCHAR(128) NOT NULL
);

-- 商品テーブル
CREATE TABLE IF NOT EXISTS items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL UNIQUE,
    price INT NOT NULL,
    item_image LONGBLOB NOT NULL,
    last_update DATETIME NOT NULL,
    delete_flag BOOLEAN NOT NULL
);

-- 在庫テーブル
CREATE TABLE IF NOT EXISTS stocks(
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantity INT NOT NULL,
    last_update DATETIME NOT NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- 会計テーブル
CREATE TABLE IF NOT EXISTS accountants(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    total_amount INT NOT NULL,
    total_price INT NOT NULL,
    accountant_user_name VARCHAR(64) NOT NULL
);

-- 会計詳細テーブル
CREATE TABLE IF NOT EXISTS details(
    id INT AUTO_INCREMENT PRIMARY KEY,
    accountant_id INT NOT NULL,
    item_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    item_price INT NOT NULL,
    quantity INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (accountant_id) REFERENCES accountants(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- 取引記録テーブル
CREATE TABLE IF NOT EXISTS transactions(
    id INT AUTO_INCREMENT PRIMARY KEY,
    accountant_id INT NOT NULL,
    total_price INT NOT NULL,
    received_price INT NOT NULL,
    returned_price INT NOT NULL,
    FOREIGN KEY (accountant_id) REFERENCES accountants(id) ON DELETE CASCADE
);

-- モバイルオーダーの注文情報
CREATE TABLE IF NOT EXISTS order_orders(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    total_amount INT NOT NULL,
    total_price INT NOT NULL,
    is_received BOOLEAN NOT NULL
);

-- モバイルオーダーの注文詳細
CREATE TABLE IF NOT EXISTS order_details(
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    item_id INT NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    item_price INT NOT NULL,
    quantity INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES order_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- 初期ユーザー作成
INSERT INTO users ( user_name, password_hash, salt ) VALUES(
     "admin",
     "$argon2id$v=19$m=65536,t=4,p=1$bzNPc3dnbFY1bTVzUGJ4Lw$U9FiytBnm3XI/CoObqtalFADEWIIHsEkTKhCJQxk6TY",
     "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
);