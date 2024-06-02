
<?php
require_once 'db_connection.php';
require_once 'APIS/exchangeCurrencies.php';
require_once 'usefulFunctions.php';

$database = new DbConnection();
$conn = $database->getConnection();
session_start();

if(isset($_GET['section'])){
    $section = $_GET['section'];
}

if(isset( $_SESSION['currency'])){
    $currency = $_SESSION['currency'];
}

if(isset($_GET['searchText'])){
    $searchText = $_GET['searchText'];
}

if (isset($_GET['getFavorites'])){
    $getLiked = true;
}



function getActivities($conn, $section = 1,$type=1, $currency=2, $id=0, $searchText=""){
    // Da aggiungere due informazioni (se ha messo like e se ha scritto la recensione quando id è settato)
    switch($type){
        case 1:
            $query = "SELECT * FROM activity_with_avg_reviews WHERE section='".$section."' LIMIT 12";
            break;
        case 2:
            $query = "SELECT a.*
            FROM activity_with_avg_reviews a
            LEFT JOIN activity_descriptions d
            ON a.id = d.activity_id
            WHERE a.title LIKE CONCAT('%', '".$searchText."', '%') or d.short_des LIKE CONCAT('%', '".$searchText."', '%') or a.city LIKE CONCAT('%', '".$searchText."', '%')
            LIMIT 12";
            break;
        case 3:
            $query = "SELECT a.*
            FROM activity_with_avg_reviews a
            LEFT JOIN activity_likes l
            ON a.id = l.activity_id
            WHERE l.user_id = '".$id."'";
            break;
        default:
            echo 'Richiesta non valida';
            exit;
    }
    $res= mysqli_query($conn, $query) or die(mysqli_error($conn));
    $activities = array();
    while ($row = mysqli_fetch_assoc($res)) {
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

        $query = "SELECT * FROM users_currency_symbol where user_id='".$row['company_id']."'";
        $res1 = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        $actual_currency = mysqli_fetch_assoc($res1)["currency_id"];
        $updateResponse = updateRates($conn);
        $activity["price"] = round(singleCurrencyExchange($conn,$row["price"],$actual_currency ,$currency),2);
        if($row["discount"] < $row["price"]){
            $activity["discount"] = round(singleCurrencyExchange($conn,$row["discount"], $actual_currency ,$currency),2);;
        }
        $activity["currency_symbol"] = getCurrencySymbol($conn, $currency);
        $activity["title"] = $row["title"];
        $activity["duration"] = minutesToHours($row["duration"]);
        $activity["avg_rating"] = round($row["avg_rating"], 1);
        if ($id > 0){
            $query = "SELECT * FROM activity_likes WHERE activity_id = '".$activity['id']."' and user_id = '".$id."'";
            $res1 = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
            if (mysqli_num_rows($res1) > 0) {
                $activity['liked'] = true;
            }
            $query = "SELECT * FROM reviews WHERE activity_id = '".$activity['id']."' and user_id = '".$id."'";
            $res1 = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
            if (mysqli_num_rows($res1) > 0) {
                $activity['reviewed'] = true;
            }
        }


        $activities[] = $activity;
    }
    echo json_encode($activities);
    exit;
}

    if (isset($_SESSION['id']) && isset($_SESSION['user_type'])) {
        $user_id = $_SESSION['id'];
        $type = 1;
        if (isset($section)) {
            $type = 1;
            getActivities($conn, $section, $type, $currency, $user_id);
            exit;

        }
        elseif (isset($searchText)) {
            $type = 2;
            getActivities($conn, 1, $type, $currency, $user_id, $searchText);

            exit;

        }
        elseif (isset($getLiked)) {
            $type = 3;
            getActivities($conn, 1, $type, $currency, $user_id);

            exit;
        }
    
    }else{
            $type = 1;
            if(isset($section)){
                if(isset($currency)){
                    getActivities($conn, $section, $type, $currency);
                    exit;
                }else{
                    getActivities($conn, $section, $type);
                    exit;
                }

            }elseif(isset($searchText)){
                $type = 2;
                if(isset($currency)){
                    getActivities($conn, 1, $type, $currency, 0, $searchText);
                    exit;
                }else{
                    getActivities($conn, 1, $type, 2, 0, $searchText);
                    exit;
                }
            }
            else{
                exit;
            }
        }