<?php
    require_once 'db_connection.php';
    require_once 'usefulFunctions.php';
    require_once 'APIS/exchangeCurrencies.php';




    $database = new DbConnection();
    $conn = $database->getConnection();


    session_start();
    if (isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
        $currency = $_SESSION['currency'];
        $user_type = $_SESSION['user_type'];
    }else{
        $currency = 2;
    }



    if (!isset($_GET['activity_id'])){
        header('Location: index.php');
        exit;
    }else{
        $activity_id = $_GET['activity_id'];
        if(isset($user_id)){
            loadActivity($conn, $activity_id, $currency, $user_id, $user_type);
        }else{
            loadActivity($conn, $activity_id, $currency);
        }

    }

    function loadActivity($conn, $activity_id, $currency, $user_id=0, $user_type=null){
        $query = "SELECT * FROM activity_details WHERE activity_id='".$activity_id."'";
        $res = mysqli_query($conn, $query) or die('Error: '.mysqli_error($conn));
        $error = array();
        if(mysqli_num_rows($res) < 0){
            $error[] = 'Attività non trovata nel database';
            echo json_encode($error);
            exit;
        }else{
            $row = mysqli_fetch_array($res);
            $activity = array();
            $activity['id'] = $row['activity_id'];
            $activity['operator'] = $row['fornitore'];
            $activity['type'] = $row['activity_type'];
            $activity['city'] = $row['city'];
            $activity['duration'] = minutesToHours($row['duration']);
            $activity['title'] = $row['title'];
            $activity['short_des'] = $row['short_des'];
            $activity['long_des'] = $row['long_des'];
            $activity['isErasable'] = $row['isErasable'];
            $activity['isBus'] = $row['isBus'];

            $actual_currency = $row['currency_company'];
            $activity["price"] = round(singleCurrencyExchange($conn,$row["price"], $actual_currency ,$currency),2);
            if($row["discount"] < $row["price"]){
                $activity["discount"] = round(singleCurrencyExchange($conn,$row["discount"], $actual_currency ,$currency),2);;
            }
            $activity["currency_symbol"] = getCurrencySymbol($conn, $currency);
            $activity["img"] = str_replace('132.webp', '98.webp', $row["url_img"]);



            mysqli_free_result($res);

            $reviews_info = array();
            $query = "SELECT avg(r.rating) as media FROM activitys a
                        LEFT JOIN reviews r
                        ON a.id = r.activity_id
                        GROUP BY r.activity_id
                        HAVING r.activity_id = '".$activity_id."'";
            $res = mysqli_query($conn,$query) or die('Error:'.mysqli_error($conn));
            $info = array();
            if(mysqli_num_rows($res) > 0){
                $row = mysqli_fetch_assoc($res);
                $info['avg_score'] = round($row['media'],2);
            }else{
                $info['avg_score'] = 0;
            }

            mysqli_free_result($res);

            $query = "SELECT u.nome, u.cognome, r.* FROM activitys a 
                        LEFT JOIN reviews r 
                        ON a.id = r.activity_id 
                        LEFT JOIN users u
                        ON u.id = r.user_id
                        WHERE a.id = '".$activity_id."'";
            $res = mysqli_query($conn, $query) or die('Error: '.mysqli_error($conn));
            $reviews = array();
            $info['reviewed'] = false;
            if (mysqli_num_rows($res) > 0) {
                while( $row = mysqli_fetch_assoc($res)) {
                    $review = array();
                    if( $row['nome'] and $row['cognome']) {
                        $review['username'] = $row['nome']." ".$row['cognome'];
                        $info['num'] = mysqli_num_rows($res);
                    }else{
                        $info['num'] = 0;
                    }
                    $review['score'] = $row['rating'];
                    $review['text'] = $row['review'];
                    $review['id'] = $row['id'];
                    
                    $review['personal'] = false;
                    if ($row['user_id'] == $user_id){
                        $info['reviewed'] = true;
                        $review['personal'] = true;
                    }
                    $reviews[] = $review;
                }
            }else{
                $info['num'] = 0;
            }
            $reviews_info['reviews'] = $reviews;
            $reviews_info['info'] = $info;
            $activity['reviews'] = $reviews_info;

            mysqli_free_result($res);


            $query = "SELECT * from activitys a
                        LEFT JOIN activity_likes al
                        ON al.activity_id = a.id
                        WHERE a.id = '".$activity_id."' and  al.user_id = '".$user_id."'";
            $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error( $conn));
            if (mysqli_num_rows($res) > 0) {
                $activity['liked'] = true;
            }else{
                $activity['liked'] = false;
            }

            mysqli_free_result($res);


            $query = "SELECT * FROM activity_infos where activity_id = '".$activity_id."'";
            $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error( $conn));
            if (mysqli_num_rows($res) > 0) {
                $main_infos = array();
                while( $row = mysqli_fetch_array($res)) {
                    $main_infos[] = $row['main_info'];
                }
            }
            if(isset($main_infos)){
                $activity['infos'] = $main_infos;
            }
            mysqli_free_result($res);

            echo json_encode($activity);
            exit;
        }
    }


?>