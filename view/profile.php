<?php
if (!isset($_SESSION["logged_user"])){
    header("Location:login.php");
}
echo "Hello " .$_SESSION['logged_user']['username'].", you are in the main page";
$user_id = $_SESSION["logged_user"]["id"];
?>
<br>
<a href="index.php"><button>Home</button></a>
<a href="index.php?target=user&action=logout"><button>Logout</button></a>
<br>
<a href="index.php?target=view&action=viewRouter&view=upload"><button>Upload video</button></a>
<br>
<?php
if (isset($user)) {
    echo "<img width=100px src='" . $user["avatar_url"] . "'>";
    echo $user["username"] . "<br>";
    echo "Name: " . $user["name"] . "<br>";
    echo "Registered on: " . $user["registration_date"] . "<br>";
    echo "Videos:";
}
else {
    header("Location:main.php");
}
if (isset($videos)) {
    echo "<table>";
    foreach ($videos as $video) {
        echo "<tr><td>";
        echo $video["title"];
        echo "</td></tr>";
        echo "<tr><td>";
        echo $video["date_uploaded"];
        echo "</td></tr>";
        echo "<tr><td>";
        echo $video["username"];
        echo "</td></tr>";
        echo "<tr><td><a href='index.php?target=video&action=getById&id=" . $video["id"] . "'><img width='200px' src='";
        echo $video["thumbnail_url"];
        echo "'></a></td></tr>";
    }
    echo "</table>";
}
?>