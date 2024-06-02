<?php




if(isset($_POST['newCurrency'])){
    require_once '..\db_connection.php';
    $database = new DbConnection();
    $conn = $database->getConnection();
    session_start();
    if(isset($_SESSION['currency'])){
        $base_currency = $_SESSION['currency'];
    }else{
        $base_currency = 2;
    }

    $_SESSION['currency'] = $_POST['newCurrency'];
    $currency = $_POST['newCurrency'];
    $prices = $_POST['prices'];
    currencyExchange($conn, $prices, $base_currency, $currency);
    exit;
}





function currencyExchange($conn, $prices, $base_currency=2, $currency) {

    //Connessione al db: serve per evitare di fare richieste alla REST API se già i tassi di cambio giornalieri sono stati aggiornati.
    $pricesExchanged = array();
    $newPrices = array();
    
    $query = "SELECT id, code, usd_exchange, symbol FROM currencys WHERE id in ('".$base_currency."', '".$currency."')";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    while($row = mysqli_fetch_assoc($res)){
        if ($row["id"] == $base_currency){
            $usd_exchangeRate = $row["usd_exchange"];
        }
        if($row['id']== $currency){
            $usd_finalExchange = $row["usd_exchange"];
            $pricesExchanged['newCurrency'] = $row['symbol'];
        }
    }
    foreach(json_decode($prices) as $key => $price){
        $priceExchanged = round(($price/$usd_exchangeRate)*$usd_finalExchange,2);;
        $newPrices[] = $priceExchanged;

    }
    $pricesExchanged['prices'] = $newPrices;
    echo json_encode($pricesExchanged);
    return;


}

function updateRates($conn, $currency=2){
    $current_date = date("Y-m-d");
    $query = "SELECT update_at FROM currencys ORDER BY update_at DESC LIMIT 1";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    $update_date = date('Y-m-d', strtotime(mysqli_fetch_assoc($res)["update_at"]));
    if ($update_date < $current_date) {
        $query = "SELECT * FROM currencys";
        $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
        
        $dati = array();
        $dati["base_currency"] = "USD";
        $dati["currencies"] = "";
        while($row=mysqli_fetch_assoc($res)){
            $dati["currencies"] = $dati["currencies"] .($dati["currencies"]==="" ? "":","). $row["code"];
        }
        $apiKey = "fca_live_ZbnDjwAEFQZadFEFpK18nmAbooeEoIR8JUzfhf9l";
        $headers = array(
            "apikey: ".$apiKey."",
            );
        
        $dati = http_build_query($dati);
        $url = "https://api.freecurrencyapi.com/v1/latest?";
        $curl = curl_init();
    
        curl_setopt($curl, CURLOPT_URL, $url."".$dati);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resp = json_decode(curl_exec($curl),true);
        curl_close($curl);
        if (!empty($resp['data'])){
            $exchangeRates = $resp["data"];
            foreach($exchangeRates as $cur => $rates){
                $query = "UPDATE currencys SET usd_exchange='".$rates."' WHERE code = '".$cur."'";
                $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
                }
            return true;
            }
        }
        return false;
        
    }


function singleCurrencyExchange($conn, $price, $base_currency= 2, $currency) {
    $query = "SELECT id, code, usd_exchange, symbol FROM currencys WHERE id in ('".$base_currency."', '".$currency."')";
    $res = mysqli_query($conn, $query) or die("Error: ".mysqli_error($conn));
    while($row = mysqli_fetch_assoc($res)){
        if ($row["id"] == $base_currency){
            $usd_exchangeRate = $row["usd_exchange"];
        }
        if($row['id']== $currency){
            $usd_finalExchange = $row["usd_exchange"];
        }
    }
        $priceExchanged = round(($price/$usd_exchangeRate)*$usd_finalExchange,2);
    return $priceExchanged;
}