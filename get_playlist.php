<?php

require_once "./inc/functions.php";
require_once "./inc/headers.php";

$conn = createDbConnection();

$playlist_id = 1;

$sql = "SELECT tracks.name as track_name, tracks.composer
        FROM playlist_track
        JOIN tracks ON tracks.TrackId = playlist_track.TrackId
        WHERE playlist_track.PlaylistId = :playlist_id";
        
$stmt = $conn->prepare($sql);
$stmt->bindParam(':playlist_id', $playlist_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $result) {
    echo "Track: " . $result['track_name'] . "<br>";
    echo "Composer: " . $result['composer'] . "<br>";
}