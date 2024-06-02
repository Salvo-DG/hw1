<?php
require_once 'db_connection.php';
$database = new DbConnection();
$conn = $database->getConnection();

session_start();
if (isset($_SESSION['id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'C') {
    $user_id = $_SESSION['id'];
    if(isset($_GET['activity'])) {
        $activity_id = $_GET['activity'];
        if(isset($_GET['action'])) {
            $action = ($_GET['action']=='Like'?1:0);
            if($action== 1) {
                addLike($conn, $user_id, $activity_id);
                exit;
            }else{
                deleteLike($conn, $user_id, $activity_id);
                exit;
            }
        }
    }
}else{
    echo 'Errore: utente non loggato o non di tipo customer';
    exit;
}


function addLike($conn, $user_id, $activity_id) {
    $query = "SELECT * FROM activity_likes WHERE activity_id = '".$activity_id."' and user_id = '".$user_id."'";
    $res = mysqli_query($conn, $query) or die('Error: '.mysqli_error($conn));
    if(mysqli_num_rows($res) > 0) {
        echo 'Errore: Like già presente';
    }else{
        $query = "INSERT INTO activity_likes(user_id, activity_id) VALUES ('".$user_id."', '".$activity_id."')";
        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        echo 'Like aggiunto';
    }
}

function deleteLike($conn, $user_id, $activity_id) {
    $query = "SELECT * FROM activity_likes WHERE activity_id = '".$activity_id."' and user_id = '".$user_id."'";
    $res = mysqli_query($conn, $query) or die('Error: '.mysqli_error($conn));
    if(mysqli_num_rows($res) == 0) {
        echo 'Errore: Like non presente';
    }else{
        $query = "DELETE FROM activity_likes WHERE user_id = '".$user_id."' and activity_id = '".$activity_id."' ";
        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        echo 'Like rimosso';
    }
}