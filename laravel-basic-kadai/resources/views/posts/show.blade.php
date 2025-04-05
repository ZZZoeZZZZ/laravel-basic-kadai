<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel基礎</title>
</head>

<body>
    <table>
        <tr>
            <th>ID</th>
            <td>{{ $post->id }}</td>
        </tr>
        <tr>
            <th>タイトル</th>
            <td>{{ $post->title }}</td>
        </tr>
        <tr>
            <th>本文</th>
            <td>{{ $post->content }}</td>
        </tr>
        <tr>
            <th>作成日時</th>
            <td>{{ $post->created_at }}</td>
        </tr>
        <tr>
            <th>更新日時</th>
            <td>{{ $post->updated_at }}</td>
        </tr>
    </table>
</body>

</html>