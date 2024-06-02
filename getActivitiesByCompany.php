<?php

    require_once 'db_connection.php';
    require_once 'usefulFunctions.php';



    session_start();

    $company_id = $_SESSION["id"];

    $database = new DbConnection();
    $conn = $database->getConnection();

    $query = "SELECT id,activity_type, price, discount, duration, title FROM activitys WHERE company_id = '".$company_id."'";
    $res = mysqli_query($conn, $query) or die("Error: " .mysqli_error($conn));

    $activities = array();

    while ($row = mysqli_fetch_assoc($res)){
        $activity = array();

        $activity["id"] = $row["id"];

        $query = "SELECT activity FROM activity_types WHERE id='".$row["activity_type"]."'";
        $res1 = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        $activity["activity_type"] = mysqli_fetch_assoc($res1)["activity"];

        $query = "SELECT img_path FROM activity_images WHERE id_activity='".$row["id"]."'";
        $res1 = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));

        $query = "SELECT img_path FROM images WHERE id='".mysqli_fetch_assoc($res1)["img_path"]."'";
        $res1 = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        $activity["img_url"] = mysqli_fetch_assoc($res1)["img_path"];

        $activity["price"] = $row["price"];
        if($row["discount"] < $row["price"]){
            $activity["discount"] = $row["discount"];
        }

        $activity["title"] = $row["title"];
        $activity["duration"] = minutesToHours($row["duration"]);
        $activities[] = $activity;
    }

    echo json_encode($activities);