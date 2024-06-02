<?php
    require_once 'db_connection.php';
    $database = new DbConnection();
    $conn = $database->getConnection();

    if(isset($_GET['getActivityTypes']) && $_GET['getActivityTypes']== 1){
        $query = "SELECT id, activity FROM activity_types";
        $res = mysqli_query($conn, $query) or die("Error: " .mysqli_error($conn));
        $types = array();
        while ($row = mysqli_fetch_assoc($res)){
            $types[] = $row;
        }
        echo json_encode($types);
        exit;
    }



    if(isset($_GET['getImages']) && $_GET['getImages']== 1){
        $query = "SELECT id, img_description FROM images";
        $res = mysqli_query($conn, $query) or die("Error: " .mysqli_error($conn));
        $images = array();
        while ($row = mysqli_fetch_assoc($res)){
            $images[] = $row;
        }
        echo json_encode($images);
        exit;
    }

    if(isset($_GET['getSections']) && $_GET['getSections']== 1){
        $query = 'SELECT * FROM sections';
        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        $sections = array();
        while ($row = mysqli_fetch_assoc($res)) {
            $sections[] = $row;
        }
        echo json_encode($sections);
        exit;
    }

    if(isset($_GET['getCurrencies']) && $_GET['getCurrencies']== 1){
        $query = "SELECT id,code, name, symbol  FROM currencys";
        $res = mysqli_query($conn, $query) or die("Error: " .mysqli_error($conn));
        $currencies = array();
        while ($row = mysqli_fetch_assoc($res)){
            $currencies[] = $row;
        }
        echo json_encode($currencies);
        exit;
    }
?>