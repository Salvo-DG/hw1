<?php
require_once 'db_connection.php';
$database = new DbConnection();
$conn = $database->getConnection();

session_start();

if (isset($_SESSION["id"])){
    $id = $_SESSION["id"];
}else{
    header("Location: login.php");
    exit;
}


if (isset($_GET["activity_id"])){
    $activity_id = mysqli_escape_string($conn, $_GET['activity_id']);
    $query = "call delete_activity(".$activity_id.")";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    header("Location: home_partner.php");

}

if (isset($_GET["review"])){
    $activity_id = $_GET["review"];
    $query = "SELECT * FROM reviews WHERE activity_id = '".$activity_id."' and user_id='".$id."'";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    if (mysqli_num_rows($res) > 0){
        $review_id = mysqli_fetch_assoc($res)['id'];
        $query = "DELETE FROM reviews WHERE id = '".$review_id."'";
        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    }else{
        $error[] = "Nessuna recensione da eliminare";
    }
    exit;
}



exit;