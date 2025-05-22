<?php
namespace project\models;

use project\models\Model;
use project\core\Database;
use app\core\Application;
use PDO;
use PDOException;

class Music extends Model
{
    private static $table = 'music';
    public $music_id = '';
    public $music_name = '';
    public $artist = '';
    public $music_url = '';
    public $tag_id = '';
    public $is_adult = '';

    public function musicinsert()
    {
        $db = Database::getConnection();

        $sql = "INSERT INTO music (Music_Name, Artist, Tag_Id, Is_Adult, Music_Url) VALUES (:music_name, :artist, :tag_id, :is_adult, :music_url)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':music_name', $this->music_name);
        $stmt->bindParam(':artist', $this->artist);
        $stmt->bindParam(':tag_id', $this->tag_id);
        $stmt->bindParam(':is_adult', $this->is_adult);
        $stmt->bindParam(':music_url', $this->music_url);

        return $stmt->execute();
    }


    public function getAllMusic($options = [])
    {
        $is_adult = $options['is_adult'] ?? null;

        $db = Database::getConnection();

        $hasAdultFilter = $is_adult === false;

        $sql = "SELECT m.Music_Id, m.Music_Name, m.Artist, m.Is_Adult, 
                    CASE m.Is_Adult 
                        WHEN 1 THEN '一般' 
                        WHEN 0 THEN '兒童' 
                        ELSE '未知' 
                    END AS Is_Adult_Text,
                    mt.Tag_Name
            FROM music AS m
            LEFT JOIN music_tag AS mt ON m.Tag_Id = mt.Tag_Id";

        if ($hasAdultFilter) {
            $sql .= " WHERE m.Is_Adult = 0";
        }

        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function countAllMusic()
    {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) as count FROM music";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        // return $result['count'];
    }

    public function updateMusic($music_id, $music_name, $artist, $music_url, $tag_id, $is_adult)
    {
        $db = Database::getConnection();
        $sql = "UPDATE music 
            SET Music_Name = :music_name, 
                Artist = :artist, 
                Music_Url = :music_url, 
                Tag_Id = :tag_id,
                Is_Adult = :is_adult
            WHERE Music_Id = :music_id";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':music_name', $music_name);
        $stmt->bindParam(':artist', $artist);
        $stmt->bindParam(':music_url', $music_url);
        $stmt->bindParam(':tag_id', $tag_id);
        $stmt->bindParam(':is_adult', $is_adult);
        $stmt->bindParam(':music_id', $music_id);

        return $stmt->execute();
    }

    public function findById(string $music_id)
    {
        $db = Database::getConnection();

        $sql = "SELECT Music_Id, Music_Name, Artist, Music_Url, Tag_Id FROM music WHERE Music_Id = :music_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':music_id', $music_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $music = new Music();
        $music->music_id = $row['Music_Id'];
        $music->music_name = $row['Music_Name'];
        $music->artist = $row['Artist'];
        $music->music_url = $row['Music_Url'];
        $music->tag_id = $row['Tag_Id'];

        return $music;
    }

    public function delete(string $music_id)
    {
        $db = Database::getConnection();

        $sql = "DELETE FROM music WHERE Music_Id = :music_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':music_id', $music_id);

        return $stmt->execute();
    }

    public function tagExists($tag_id)
    {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) FROM music_tag WHERE Tag_Id = :tag_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':tag_id', $tag_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getMusicById($music_id)
    {
        $db = Database::getConnection();
        $sql = "SELECT Music_Id, Music_Name, Artist, Music_Url, Tag_Id FROM music WHERE Music_Id = :music_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':music_id', $music_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteMusic($music_id)
    {
        $db = Database::getConnection();
        $sql = "DELETE FROM music WHERE Music_Id = :music_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':music_id', $music_id);
        return $stmt->execute();
    }

    public function addlist()
    {
        $db = Database::getConnection();

        $sql = "INSERT INTO Playlist_music (Playlist_Id, Music_Id) VALUES (:playlist_id, :music_id)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':playlist_id', $this->music_name);
        $stmt->bindParam(':music_id', $this->artist);

        return $stmt->execute();
    }
}

class MusicTag extends Model
{
    private static $table = 'music_tag';
    public $tag_id = '';
    public $tag_name = '';

    public function getAllTags()
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM music_tag";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}