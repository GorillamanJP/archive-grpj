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
    item_image LONGBLOB NOT NULL
);

-- 在庫テーブル
CREATE TABLE IF NOT EXISTS stocks(
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL UNIQUE,
    quantity INT NOT NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

-- 会計テーブル
CREATE TABLE IF NOT EXISTS accountants(
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME NOT NULL,
    total_amount INT NOT NULL
);

-- 会計詳細テーブル
CREATE TABLE IF NOT EXISTS details(
    id INT AUTO_INCREMENT PRIMARY KEY,
    accountant_id INT NOT NULL UNIQUE,
    item_id INT NOT NULL UNIQUE,
    quantity INT NOT NULL,
    item_price INT NOT NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    FOREIGN KEY (accountant_id) REFERENCES accountants(id) ON DELETE CASCADE
);