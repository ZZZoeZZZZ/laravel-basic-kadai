<?php
$dsn = 'mysql:host=localhost;dbname=php_book_app;charset=utf8mb4';
$user = 'root';
$password = '';

if(isset($_POST['submit'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        // SQL準備
        $sql_update = '
        UPDATE books
        SET
            book_code = :book_code,
            book_name = :book_name,
            price = :price,
            stock_quantity = :stock_quantity,
            genre_code = :genre_code
        WHERE id = :id
        ';

        $stmt_update = $pdo->prepare($sql_update);

        $stmt_update->bindParam(':book_code', $_POST['book_code'], PDO::PARAM_INT);
        $stmt_update->bindParam(':book_name', $_POST['book_name'], PDO::PARAM_STR);
        $stmt_update->bindParam(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt_update->bindParam(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
        $stmt_update->bindParam(':genre_code', $_POST['genre_code'], PDO::PARAM_INT);
        $stmt_update->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

        // SQL実行
        $stmt_update->execute();
        $count = $stmt_update->rowCount();
        $message = "書籍を{$count}件更新しました。";

        // リダイレクト
        header('Location: list.php?message=' . $message);
        exit;

    } catch (PDOException $e) {
        echo 'エラーが発生しました。';
        echo $e->getMessage();
    }
}

if(isset($_GET['id'])) {

try {
    $pdo = new PDO($dsn, $user, $password);
    
    // データベースから該当の書籍を取得
    $sql_select_book = 'SELECT * FROM books WHERE id = :id';
    $stmt_select_book = $pdo->prepare($sql_select_book);
    $stmt_select_book->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    $stmt_select_book->execute();
    $book = $stmt_select_book->fetch(PDO::FETCH_ASSOC);
    if($book === false) {
        exit('データが見つかりません。');
    }

    // ジャンルコードを取得
    $sql_select_genre_code = 'SELECT genre_code FROM genres';
    $stmt_select_genre_code = $pdo->query($sql_select_genre_code);
    $genre_codes = $stmt_select_genre_code->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    exit('データベース接続に失敗しました。' . $e->getMessage());
}
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍管理</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Google Fontsの読み込み -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">書籍管理アプリ</a>
        </nav>
    </header>
    <main>
        <article class="registration">
            <h1>書籍編集</h1>
            <div class="back">
                <a href="list.php" class="btn">&lt;戻る</a>
            </div>
            <form action="edit.php?id=<?= $_GET['id'] ?>" method="post" class="registration-form">
                <div>
                    <label for="book_code">書籍コード</label>
                    <input type="number" name="book_code" id="book_code" value="<?php echo $book['book_code']; ?>"
                        min="0" max="100000000" required>

                    <label for="book_name">書籍名</label>
                    <input type="text" name="book_name" id="book_name" value="<?php echo $book['book_name']; ?>"
                        maxlength="50" required>

                    <label for="price">値段</label>
                    <input type="number" name="price" id="price" value="<?php echo $book['price']; ?>" min="0"
                        max="1000000" required>

                    <label for="stock_quantity">在庫数</label>
                    <input type="number" name="stock_quantity" id="stock_quantity"
                        value="<?php echo $book['stock_quantity']; ?>" min="0" max="1000000" required>

                    <label for="genre_code">ジャンルコード</label>
                    <select name="genre_code" id="genre_code" required>
                        <option disabled selected value>選択してください</option>
                        <?php
                        foreach ($genre_codes as $genre_code) {
                            if($genre_code === $book['genre_code']) {
                                echo '<option value="' . $genre_code . '" selected>' . $genre_code . '</option>';
                            } else {
                                echo '<option value="' . $genre_code . '">' . $genre_code . '</option>';
                            }
                        }
                        ?>
                    </select>

                </div>
                <button type="submit" class="submit-btn" name="submit" value="edit">更新</button>
            </form>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 2025 書籍管理アプリ All rights reserved.</p>
    </footer>

</body>

</html>