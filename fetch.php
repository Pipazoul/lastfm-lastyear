<?php include('header.html') ?>
    
<?php

$api_key = "75c3f03ec3f14b621c12854fcfcd82d6";

if(isset($_POST["pseudo"])){
    $lastfm_username = $_POST["pseudo"] ;
    $substract_year = 1;

    $past_year_start = new DateTime();
    $past_year_start->getTimestamp();
    $past_year_start->sub(new DateInterval('P'.$substract_year.'Y'));
    $past_year_start->setTime(00, 00);
    $start = $past_year_start->getTimestamp();

    $past_year_stop = new DateTime();
    $past_year_stop->getTimestamp();
    $past_year_stop->sub(new DateInterval('P'.$substract_year.'Y'));
    $past_year_stop->setTime(23, 59);
    $stop = $past_year_stop->getTimestamp();


    $url ='http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user='.$lastfm_username.'&from='.$start.'&extended=0&to='.$stop.'&api_key='.$api_key.'&format=json';

    //print "<br><br>URL :".$url."<br><br>";

    $json = file_get_contents($url);

    $obj = json_decode($json, true);
    
    //var_dump($obj["recenttracks"]);
    
    if($obj["recenttracks"]["track"] == NULL) {
        print 'No track found for this date :(';
    }
    else{
        
        foreach($obj["recenttracks"]["track"] as $track ){
        print '<img src="'.$track["image"][3]["#text"].'"><br>';
        print $track["date"]["#text"].' - ';
        print $track["artist"]["#text"].' - ';
        print $track["name"].' - ';
        print $track["album"]['#text'].'<br>';
        }  
    }

 
}
else{
    print 'Please enter a username';
}

    
    

//ini_set("allow_url_fopen", 1);
