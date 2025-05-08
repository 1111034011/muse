<?php
namespace project\models;

use project\models\Model;
use project\core\Database;
use app\core\Application;
use PDO;
use PDOException;

class PlaylistMusic extends Model
{
    private static $table = 'playlist_music';
    public $playlist_id = '';
    public $music_id = '';

    public function save()
    {
        $db = Database::getConnection();

        $sql = "INSERT INTO playlist_music (Playlist_Id, Music_Id) VALUES (:playlist_id, :music_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':playlist_id', $this->playlist_id);
        $stmt->bindParam(':music_id', $this->music_id);
        return $stmt->execute();
    }

    public function findByMusicname($playlist_id, $music_id)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM playlist_music WHERE Playlist_Id = :playlist_id AND Music_Id = :music_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':playlist_id', $playlist_id);
        $stmt->bindParam(':music_id', $music_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllMusic()
    {
        $db = Database::getConnection();
        $sql = "SELECT m.Music_Id, m.Music_Name, m.Artist, mt.Tag_Name 
                FROM music AS m
                LEFT JOIN music_tag AS mt ON m.Tag_Id = mt.Tag_Id
                ";
        // $sql = "SELECT m.Music_Name, m.Artist, mt.Tag_Name FROM music as m, music_tag as mt WHERE m.Tag_Id = mt.Tag_Id";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}