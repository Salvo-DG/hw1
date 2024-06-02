<?php

    
    
    
    header('Content-Type: application/json');
    
    spotify();
    
    function spotify() {
        $client_id =     "5681de9803d84b51be93150901a39d86";
        $client_secret = "b3a970be4ebe4e49a97f4371bbdcd8bb";
    

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token' );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');       
        $headers = array(
            "Authorization: Basic ".base64_encode($client_id.':'.$client_secret)
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $token=json_decode(curl_exec($ch), true);
        curl_close($ch);    
    

        $query = urlencode('description,images(url, height),name,followers(total),tracks(total,items(track(artists(name),name,duration_ms,uri,album(name,images))))');
        $playlist_id = urlencode($_GET['playlist_id']);
        $url = 'https://api.spotify.com/v1/playlists/'.$playlist_id.'?fields='.$query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $header = array('Authorization: Bearer '.$token['access_token']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res= curl_exec( $ch );
        curl_close($ch);

        echo $res;



    }





?>