<?php

require_once "./inc/functions.php";
require_once "./inc/headers.php";

$conn = createDbConnection();

$artist_id = 2; // Replace this with the correct artist_id

$sql = "SELECT artists.Name as artist_name, albums.Title as album_title, tracks.Name as track_name 
        FROM artists
        JOIN albums ON artists.ArtistId = albums.ArtistId
        JOIN tracks ON albums.AlbumId = tracks.AlbumId
        ORDER BY artists.ArtistId, albums.AlbumId";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$output = [];

foreach ($results as $result) {
    $artist_name = $result['artist_name'];
    $album_title = $result['album_title'];
    $track_name = $result['track_name'];

    if (!isset($output[$artist_name])) {
        $output[$artist_name] = [
            'artist' => $artist_name,
            'albums' => []
        ];
    }

    if (!isset($output[$artist_name]['album_index'][$album_title])) {
        $output[$artist_name]['album_index'][$album_title] = count($output[$artist_name]['albums']);
        $output[$artist_name]['albums'][] = [
            'title' => $album_title,
            'tracks' => []
        ];
    }

    $album_index = $output[$artist_name]['album_index'][$album_title];
    $output[$artist_name]['albums'][$album_index]['tracks'][] = [
        'name' => $track_name
    ];
}

foreach ($output as &$artist) {
    unset($artist['album_index']);
}

$response = array_values($output);

header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);