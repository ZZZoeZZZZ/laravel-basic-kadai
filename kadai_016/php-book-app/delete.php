<?php
$dsn = 'mysql:host=localhost;dbname=php_book_app;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    // SQL準備
    $sql_delete = 'DELETE FROM books WHERE id = :id';

    // SQL実行
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

    // SQL実行
    $stmt_delete->execute();
    $count = $stmt_delete->rowCount();
    $message = "書籍を{$count}件削除しました。";

    // リダイレクト
    header('Location: list.php?message=' . $message);
    exit;
    
} catch (PDOException $e) {
    echo 'エラーが発生しました。';
    echo $e->getMessage();
}

?>