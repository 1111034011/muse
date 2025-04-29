<?php
namespace project\models;

use project\models\Model;
use project\core\Database;
use app\core\Application;
use PDO;
use PDOException;

class Playlist extends Model
{
    private static $table = 'playlist';
    public $playlist_id = '';
    public $name = '';
    public $member_id = '';

    public function findByPlaylistname($name, $member_id)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM playlist WHERE Name = :name AND Member_Id = :member_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':member_id', $member_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByMemberId($member_id)
    {
        $db = Database::getConnection();

        $sql = "SELECT * FROM Playlist WHERE Member_Id = :member_id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':member_id', $member_id);
        $stmt->execute();
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(function($item) {
            return [
                'member_id' => $item['Member_Id'],
                'playlist_id' => $item['Playlist_Id'],
                'name' => $item['Name'],    
            ];
        }, $rows);
    }

    public function save()
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO playlist (Member_Id, Name) VALUES (:member_id, :name)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':member_id', $this->member_id);
        $stmt->bindParam(':name', $this->name);
        return $stmt->execute();
    }
}

