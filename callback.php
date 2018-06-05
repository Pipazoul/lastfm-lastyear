<?php

//Spotify API Helper
require_once 'vendor/autoload.php';

include 'config.php';


//Checks if has pseudo cookie
if (isset($_COOKIE["pseudo"])) {
    $pseudo = $_COOKIE["pseudo"];
}
else {
    return 'No pseudo cookie !';
}


//SPOTIFY CALLBACK
$session = new SpotifyWebAPI\Session(
    $spotify_client_id,
    $spotify_client_secret,
    'http://localhost/lastfm-lastyear/callback.php'
);

$api = new SpotifyWebAPI\SpotifyWebAPI();

if (isset($_GET['code'])) {
    // Gets access from spotify
    $session->requestAccessToken($_GET['code']);
    $api->setAccessToken($session->getAccessToken());

    $scopes = $session->getScope();
    $scopes = $session->getScope();

    // Spotify logged user
    $me = $api->me();


    $playlists = $api->getUserPlaylists($me->id);

    function fetchPlaylistId($playlists){
        foreach ($playlists->items as $playlist) {
            if ($playlist->name == 'Lastfm A year ago') {
               return $playlist->id;
            }
        }
    }

   //LAST FM API FETCH
    $lastfm_username = $pseudo ;

    include('inc/date.php');

    $url ='http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user='.$lastfm_username.'&from='.$start.'&extended=0&to='.$stop.'&api_key='.$lastfm_api_key.'&format=json';
    $json = file_get_contents($url);
    $obj = json_decode($json, true);

    $trackId = [];
    $playlistId = '';
    $playlistName = $past_year_start->format('d/m/Y');

    // Search the name of the track in the Spotify catalog
   foreach($obj["recenttracks"]["track"] as $track ){
        $tracks = $api->search($track["name"],'track');
        //var_dump('Lastfm : '. $track["artist"]["#text"]);
        if (!empty($tracks->tracks->items[0]->id)){
            //var_dump( $tracks->tracks->items[0]);
            /*foreach ($tracks->tracks->items as $findArtist){
                $i =0;
                echo '<br>----------------------------<br>';
                foreach ($findArtist->album->artists as $artists){
                    if (strcasecmp($artists->name, $track["artist"]["#text"])) {
                        var_dump($tracks->tracks->items[$i]->name);
                    }
                    $i++;
                }
            }*/
            array_push($trackId, $tracks->tracks->items[0]->id);
        }
    }

    $newPlaylist = $api->createUserPlaylist($me->id, [
        'name' => 'LastFm - '. $playlistName
    ]);
    $playlistId = $newPlaylist->id;
    


    // Adds the song in the users Spotify Playlist
    $replaceSongsInPlaylist = $api->replaceUserPlaylistTracks($me->id,$playlistId, $trackId);

    if($replaceSongsInPlaylist) {
        echo 'true';
        return header('Location: fetch.php?playlist=true');;
    }
    $playlistId = $newPlaylist->id;
    


} else {
    // Defines what kind of information/access are required from Spotify
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


