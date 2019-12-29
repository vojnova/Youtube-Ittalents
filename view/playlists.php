<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
if (isset($_GET["id"])){
    $video_id = $_GET["id"];
}
$user_id = $_SESSION["logged_user"]["id"];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php
require_once "header.php";
require_once "navigation.php";

if(isset($playlists)){
    foreach ($playlists as $playlist) {
        echo "<b>" . $playlist["title"]. "</b>" . "<br>";
        echo $playlist["date_created"]. "<br>";
        echo "<hr>";
    }
}
?>
</body>
</html>