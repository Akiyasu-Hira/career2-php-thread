<?php

/**
 * 職業実践2 - 掲示板アプリ
 */

session_start();

function setToken()
{
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

function checkToken()
{
    if (empty($_SESSION['token'])) {
        echo "Sessionが空です";
        exit;
    }

    if (($_SESSION['token']) !== $_POST['token']) {
        echo "不正な投稿です。";
        exit;
    }

    $_SESSION['token'] = null;
}

if (empty($_SESSION['token'])) {
    setToken();
}
?>

<html>
<head>
    <title>掲示板App</title>
    <meta name="viewport" content="width=device-width" inital-scale=1>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

<h1 style="color: red;">掲示板App</h1>

<h2>投稿フォーム</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="text" name="personal_name" placeholder="名前" required><br><br>
    <textarea name="contents" rows="8" cols="40" placeholder="内容" required>
</textarea><br><br>
    <input type="submit" name="submitButton" value="投稿する">
</form>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="hidden" name="method" value="DELETE">
    <input type="submit" name="submitButton" value="投稿を削除する"></input>
</form>

<h2>スレッド</h2>

<?php

date_default_timezone_set( "Asia/Tokyo" );
const THREAD_FILE = 'thread.txt';

require_once './Thread.php';
$thread = new Thread('掲示板App');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["method"]) && $_POST["method"] === "DELETE") {
        $thread->delete();
    } else {
        $thread->post($_POST['personal_name'], $_POST['contents']);
    }

    //ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
}

echo $thread->getList();

?>

</body>
</html>