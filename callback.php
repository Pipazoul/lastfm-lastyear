<?php

//Spotify API Helper
require_once 'vendor/autoload.php';

include 'config.php';


//SPOTIFY CALLBACK

$session = new SpotifyWebAPI\Session(
    $spotify_client_id,
    $spotify_client_secret,
    'http://localhost/lastfm-lastyear/callback.php'
);

$api = new SpotifyWebAPI\SpotifyWebAPI();

if (isset($_GET['code'])) {
    $session->requestAccessToken($_GET['code']);
    $api->setAccessToken($session->getAccessToken());

    $scopes = $session->getScope();
    $scopes = $session->getScope();

    $me = $api->me();

    //var_dump($me->id);

    $playlists = $api->getUserPlaylists($me->id);

    function fetchPlaylistId($playlists){
        foreach ($playlists->items as $playlist) {
            if ($playlist->name == 'Lastfm A year ago') {
               return $playlist->id;
            }
        }
    }

   //LAST FM API FETCH
    $lastfm_username = "pipazoul" ;
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

    $createPlaylist = $api->createUserPlaylist($me->id, [
        'name' => 'A year ago'
    ]);

    $playlists = $api->getUserPlaylists($me->id, [
        'limit' => 5
    ]);
    $playlisSearch = false;
    foreach ($playlists->items as $playlist) {

        if($playlist->name == 'A year ago'){
            echo 'OKI';
            $playlisSearch = true;
        }
        elseif($playlisSearch = true) {
           
        
        }
        else {

        }
        echo '<a href="' . $playlist->external_urls->spotify . '">' . $playlist->name . '</a> <br>';
    }

    
    

   foreach($obj["recenttracks"]["track"] as $track ){

        /*print $track["name"].'<br>';
        print  $track["artist"]["#text"].'<br>';
        print $track["album"]['#text'];*/
        $results = $api->search($track["artist"]["#text"],'artist');
        foreach ($results->artists->items as $artist) {
            echo $artist->name, '<br>';
            echo $artist->id, '<br>';
            /*$api->addUserPlaylistTracks($me->id,fetchPlaylistId($createPlaylist->id) , [
                'spotify:track:'+$artist->id
            ]);*/
    
        }
    

    }


} else {
    $options = [
        'scope' => [
            'user-read-email',
            'user-modify-playback-state',
            'playlist-modify-public',
            'playlist-modify-private'

        ],
    ];

    header('Location: ' . $session->getAuthorizeUrl($options));
    die();
}

/*print_r(
    $api->getTrack('4uLU6hMCjMI75M1A2tKUQC')
);*/

