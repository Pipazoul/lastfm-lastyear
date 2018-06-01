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

 
    $trackId = [];
    $playlistId = '';

   foreach($obj["recenttracks"]["track"] as $track ){

        // Search the name of the track in the Spotify catalog
        $tracks = $api->search($track["name"],'track');
        array_push($trackId, $tracks->tracks->items[0]->id);

    }



     // Gets the 5 first playlist from the user
     $playlists = $api->getUserPlaylists($me->id, [
        'limit' => 5
    ]);
    foreach ($playlists->items as $playlist) {

        if($playlist->name == 'A year ago'){
            echo 'OKI';
            $playlistId = $playlist->id;
        }
        else {
            $newPlaylist = $api->createUserPlaylist($me->id, [
                'name' => 'A year ago'
            ]);
            $playlistId = $newPlaylist->id;
            //var_dump($playlistId->id);
        }
        echo '<a href="' . $playlist->external_urls->spotify . '">' . $playlist->name . '</a> <br>';
    }


    // Adds the song in the users Spotify Playlist
    $replaceSongsInPlaylist = $api->replaceUserPlaylistTracks($me->id,$playlistId, $trackId);

    if($replaceSongsInPlaylist) {
        echo 'true';
        return true;
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


