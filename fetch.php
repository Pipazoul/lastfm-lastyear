<?php

include('header.html');

//Contains API Key for Last.fm and Spotify
include('config.php');

function fetchTracks($lastfm_api_key, $lastfm_username) {

    // Creates a the last year date
    include('inc/date.php');

    $url ='http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user='.$lastfm_username.'&from='.$start.'&extended=0&to='.$stop.'&api_key='.$lastfm_api_key.'&format=json';


    $json = file_get_contents($url);

    $obj = json_decode($json, true);

    ?>
     <a href="callback.php"><button class="callback uk-button uk-button-default uk-align-center">Generate a Spotify playlist</button></a>
    <?php
    
    // If no last fm tracks found
    if($obj["recenttracks"]["track"] == NULL) {
        print 'No track found for this date :( or incorrect pseudo';
    }
    else{
        print '<div id="tracks">';
        print '<h2>'.$past_year_start->format('d/m/Y').'</h2>';
        foreach($obj["recenttracks"]["track"] as $track ){
            
        print '<div class="track uk-card uk-card-default uk-card-body uk-width-1-2@m uk-animation-slide-top"><div class="uk-card-title">';
        // Convert lastfm date timestamp to hh-mm date
        $trackDate =  new DateTime();
        $trackDate -> setTimestamp($track["date"]["uts"]);
        print $trackDate -> format('H:i');
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

// Checks if pseudo and years have been sent trought index.php
if(isset($_POST["pseudo"]) && isset($_POST["years"])){

    setcookie("pseudo", $_POST["pseudo"]);

    $lastfm_username = $_POST["pseudo"];
    fetchTracks($lastfm_api_key ,$lastfm_username);

} 
// Checks if the cookies have been set
elseif(isset($_COOKIE["pseudo"]) && isset($_COOKIE["years"])){


    $lastfm_username = $_COOKIE["pseudo"];

    if (!empty($_GET["playlist"])) {
        if ($_GET["playlist"] == 'true'){
            echo 'Playlist succesfully generated';
        }
    }

    fetchTracks($lastfm_api_key ,$lastfm_username);
}

else {
    print 'Please enter a username';
}

?>
    
</body>

</html>
