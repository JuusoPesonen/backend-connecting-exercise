<?php

require_once "./inc/functions.php";
require_once "./inc/headers.php";

$conn = createDbConnection();

// Define artist, album and tracks data
$_POST['artist_name'] = "AC/DC";
$_POST['album_title'] = "Back in Black";
$_POST['tracks'] = array("Hells Bells", "Shoot to Thrill", "Back in Black");

// Check if all required data are present
if (!isset($_POST['artist_name']) || !isset($_POST['album_title']) || !isset($_POST['tracks'])) {
    echo "Error: Missing required data. Make sure to provide artist_name, album_title and tracks in the POST request.";
    exit;
}

// Extract artist, album and tracks data from the POST request
$artist_name = $_POST['artist_name'];
$album_title = $_POST['album_title'];
$tracks = $_POST['tracks'];

try {
    $conn->beginTransaction();

    // Insert new artist data to the database
    $stmt1 = $conn->prepare("INSERT INTO artists (Name) VALUES (:name)");
    $stmt1->bindParam(':name', $artist_name, PDO::PARAM_STR);
    $stmt1->execute();
    $artist_id = $conn->lastInsertId();

    // Insert new album data to the database
    $stmt2 = $conn->prepare("INSERT INTO albums (Title, ArtistId) VALUES (:title, :artist_id)");
    $stmt2->bindParam(':title', $album_title, PDO::PARAM_STR);
    $stmt2->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt2->execute();
    $album_id = $conn->lastInsertId();

    // Insert new tracks data to the database
    $stmt3 = $conn->prepare("INSERT INTO tracks (Name, AlbumId, MediaTypeId) VALUES (:name, :album_id, :media_type_id)");

    foreach ($tracks as $track_name) {
        $media_type_id = 1;
        $stmt3->bindParam(':name', $track_name, PDO::PARAM_STR);
        $stmt3->bindParam(':album_id', $album_id, PDO::PARAM_INT);
        $stmt3->bindParam(':media_type_id', $media_type_id, PDO::PARAM_INT);
        $stmt3->execute();
    }

    $conn->commit();
    echo "Artist, album and tracks added successfully";
} catch (Exception $e) {
    $conn->rollBack();
    echo "Error adding artist, album and tracks: " . $e->getMessage();
}