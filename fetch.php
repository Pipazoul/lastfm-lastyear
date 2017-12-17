<?php

include('header.html');

//Contains API Key for Last.fm and Spotify
include('config.php');

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


    $url ='http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user='.$lastfm_username.'&from='.$start.'&extended=0&to='.$stop.'&api_key='.$lastfm_api_key.'&format=json';


    $json = file_get_contents($url);

    $obj = json_decode($json, true);
    
    //var_dump($obj["recenttracks"]);
    
    if($obj["recenttracks"]["track"] == NULL) {
        print 'No track found for this date :( or incorrect pseudo';
    }
    else{
        print '<div id="tracks">';
        print '<h2>'.$past_year_start->format('d/m/Y').'</h2>';
        foreach($obj["recenttracks"]["track"] as $track ){
            
            
        
        $lastfm_date = new DateTime();
        $lastfm_date->setTimestamp($track["date"]["uts"]);  
        print '<div class="track uk-card uk-card-default uk-card-body uk-width-1-2@m uk-animation-slide-top"><div class="uk-card-title">';
        print $lastfm_date->format('H:i');
        print '</div>';
        print '<img src="'.$track["image"][2]["#text"].'">';
        print $track["name"].'<br>';
        print  $track["artist"]["#text"].'<br>';
        print $track["album"]['#text'];
        print '</div><br>';
        }
        print '</div>';
    }

 
}
else{
    print 'Please enter a username';
}

?>
    
</body>

</html>
