<?php
$dsn = 'mysql:host=localhost;dbname=php_book_app;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    // 並び替え設定
    if(isset($_GET['order'])) {
        $order = $_GET['order'];
    } else {
        $order = NULL;
    }

    // 検索設定
    if(isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
    } else {
        $keyword = NULL;
    }

    // 全ての書籍を取得する
    if($order === 'desc') {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY book_code DESC';
    } else {
        $sql_select = 'SELECT * FROM books WHERE book_name LIKE :keyword ORDER BY book_code ASC';
    }

    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(':keyword', "%{$keyword}%", PDO::PARAM_STR);
    $stmt_select->execute();
    $books = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'エラーが発生しました: ' . $e->getMessage();
    exit;
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
        <article class="books">
            <h1>書籍一覧</h1>
            <?php 
            if(isset($_GET['message'])) {
                echo "<p class='success'>{$_GET['message']}</p>";
            }
            ?>
            <div class="books-ui">
                <div>
                    <a href="list.php?order=desc&keyword=<?= $keyword ?>">
                        <img src="img/desc.png" alt="降順" class="sort-img">
                    </a>
                    <a href="list.php?order=asc&keyword=<?= $keyword ?>">
                        <img src="img/asc.png" alt="昇順" class="sort-img">
                    </a>
                    <form action="list.php" method="get" class="search-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" class="search-box" name="keyword" placeholder="書籍名で検索"
                            value="<?= $keyword ?>">
                    </form>
                </div>

                <a href="create.php" class="btn">書籍登録</a>
            </div>

            <table class="books-table">
                <tr>
                    <th>書籍コード</th>
                    <th>書籍名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>ジャンルコード</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                <?php
                 foreach ($books as $book) {
                    $table_row = "
                    <tr>
                    <td>{$book['book_code']}</td>
                    <td>{$book['book_name']}</td>
                    <td>{$book['price']}</td>
                    <td>{$book['stock_quantity']}</td>
                    <td>{$book['genre_code']}</td>
                    <td><a href='edit.php?id={$book['id']}'><img src='img/edit.png' alt='編集' class='edit-icon'></a></td>
                    <td><a href='delete.php?id={$book['id']}'><img src='img/delete.png' alt='削除' class='delete-icon'></a></td>
                    </tr>
                    ";
                    echo $table_row;
                 }
                 ?>
            </table>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 2025 書籍管理アプリ All rights reserved.</p>
    </footer>

</body>

</html>