<?php

require_once "./inc/functions.php";
require_once "./inc/headers.php";

$conn = createDbConnection();

$artist_id = 1;

try {

    $conn->beginTransaction();

    // Delete invoice_items
    $sql = "DELETE FROM invoice_items
            WHERE TrackId IN (
                SELECT TrackId FROM tracks
                JOIN albums ON tracks.AlbumId = albums.AlbumId
                WHERE albums.ArtistId = :artist_id
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt->execute();

     // Delete tracks from playlist_track
    $sql = "DELETE FROM playlist_track
     WHERE TrackId IN (
         SELECT TrackId FROM tracks
         JOIN albums ON tracks.AlbumId = albums.AlbumId
         WHERE albums.ArtistId = :artist_id
     )";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt->execute();


    // Delete tracks
    $sql = "DELETE FROM tracks
            WHERE AlbumId IN (
                SELECT AlbumId FROM albums
                WHERE ArtistId = :artist_id
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt->execute();

    // Delete albums
    $sql = "DELETE FROM albums WHERE ArtistId = :artist_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt->execute();

    // Delete artist
    $sql = "DELETE FROM artists WHERE ArtistId = :artist_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $stmt->execute();

    $conn->commit();

    echo "Artist and related data deleted successfully.";

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}