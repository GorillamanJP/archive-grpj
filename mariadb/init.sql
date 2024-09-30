USE cash_register;

-- レジのユーザーテーブル
CREATE TABLE IF NOT EXISTS register_user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(64) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    salt VARCHAR(128) NOT NULL
);

-- 商品テーブル
CREATE TABLE IF NOT EXISTS items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    itemname VARCHAR(255) NOT NULL,
    price INT NOT NULL
);