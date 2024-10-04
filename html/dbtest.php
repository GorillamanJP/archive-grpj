<?php
$user_name = "root";
$password = getenv("DB_PASSWORD");
$db_name = getenv("DB_DATABASE");
$dsn = "mysql:host=mariadb;dbname=".$db_name;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データベーステスト</title>
</head>

<body>
    <h1>データベースの動作確認ができます</h1>
    <h2>データベース接続</h2>
    <?php
    $pdo = null;
    try {
        $pdo = new PDO($dsn, $user_name, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        ?>
        <p>データベースに接続できました。</p>
        <?php
    } catch (PDOException $e) {
        ?>
        <p>データベースに接続できませんでした。</p>
        <pre><?= $e->getMessage() ?></pre>
        <?php
    }
    ?>
    <h2>テーブル作成</h2>
    <?php
    try {
        $sql = "CREATE TABLE IF NOT EXISTS test (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                email VARCHAR(50)
            )";

        $pdo->exec($sql);
        ?>
        <p>テーブルの作成に成功しました。</p>
        <?php
    } catch (PDOException $e) {
        ?>
        <p>テーブルの作成に失敗しました。</p>
        <pre><?= $e->getMessage(); ?></pre>
        <?php
    }
    ?>
    <h2>データ挿入</h2>
    <?php
    try {
        $sql = "INSERT INTO test (name, email) VALUES 
            ('John Doe', 'john@example.com'),
            ('Jane Smith', 'jane@example.com'),
            ('Alice Johnson', 'alice@example.com'),
            ('Bob Brown', 'bob@example.com')";
        $pdo->exec($sql);
        ?>
        <p>データの挿入に成功しました。</p>
        <?php
    } catch (PDOException $e) {
        ?>
        <p>データの挿入に失敗しました。</p>
        <pre><?= $e->getMessage() ?></pre>
        <?php
    }
    ?>
    <h2>データ読み取り</h2>
    <table>
        <?php
        try {
            $pdo = new PDO($dsn, $user_name, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT id, name, email FROM test";
            $stmt = $pdo->query($sql);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><?= $row["id"] ?></td>
                    <td><?= $row["name"] ?></td>
                    <td><?= $row["email"] ?></td>
                </tr>
                <?php
            }
        } catch (PDOException $e) {
            ?>
            <p>データの読み込みに失敗しました。</p>
            <pre>
                        <?= $e->getMessage() ?>
                    </pre>
            <?php
        }
        ?>
    </table>
    <h2>データ削除</h2>
    <?php
    try{
    $sql = "DELETE FROM test";
    $pdo->exec($sql);
    ?>
    <p>テーブルの削除に成功しました。</p>
    <?php
} catch (PDOException $e) {
    ?>
    <p>テーブルの削除に失敗しました。</p>
    <pre><?= $e->getMessage() ?></pre>
    <?php
}
    ?>
</body>

</html>