
<?php
function minutesToHours($minutes){
    $hours = floor($minutes /60);
    $minutes = $minutes %60;
    if($hours == 0){
        return $minutes." minuti";
    }
    if ($minutes == 0){
        return $hours. ($hours==1?" ora":" ore");
    }
    return $hours.($hours==1?" ora e ":" ore e ").$minutes. " minuti.";
}

function getCurrencySymbol($conn, $currency){
    $query = "SELECT symbol FROM currencys WHERE id='".$currency."'";
    $res2 = mysqli_query($conn, $query) or die("Error:" . mysqli_error($conn));
    return mysqli_fetch_assoc($res2)["symbol"];
}

function arePostFieldsSet($required_fields) {
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            return false;
        }
    }
    return true;
}