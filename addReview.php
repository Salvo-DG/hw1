<?php

    require_once 'db_connection.php';
    require_once 'usefulFunctions.php';
    $database = new DbConnection();
    $conn = $database->getConnection();
    session_start();

    if(!isset($_SESSION["id"])) {
        header("Location: index.php");
    }else{
        $id = $_SESSION["id"];
    }

    $required_fields = ['activity_id', 'score', 'reviewText'];
    if(arePostFieldsSet($required_fields)) {
        $activity_id = mysqli_escape_string($conn, $_POST['activity_id']);
        $score = mysqli_escape_string($conn, $_POST['score']);
        $text = mysqli_escape_string($conn, $_POST['reviewText']);

        addReview($conn, $activity_id, $id, $score, $text);
        header('Location: activity.php?activity_id='. $activity_id);
        exit;

    }

    function addReview($conn, $activity_id, $id, $score, $text) {
        $query = "SELECT * from reviews WHERE activity_id = '".$activity_id."' and user_id = '".$id."'";
        $res = mysqli_query($conn, $query) or die('Error: '.mysqli_error($conn));
        if(mysqli_num_rows($res) > 0) {
            $errore[] = 'Hai già recensito questa attività';
        }else{

            if(is_numeric($score) and $score >= 0 and $score <=5)   {
                if(strlen($text) != 0 && strlen($text) <= 990) {
                    $query = "INSERT INTO reviews (activity_id, user_id, rating, review) VALUES ('".$activity_id."', '".$id."', '".$score."', '".$text."')";
                    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                    echo 'recensione aggiunta';

                }
            }else{
                $errore[] = 'I campi inseriti non sono validi';
            }
            
        }
        if (isset($errore)){
            echo json_encode($errore);
        }

    }





?>