<?php
namespace project\models;

use PDO;
use project\models\Model;
use project\core\Database;

class MusicRanking extends Model
{
    private static $table = 'music_ranking';
    public $music_id = '';
    public $play_count = '';

    public function playcount($music_id)
    {
        $db = Database::getConnection();
        $sql = "SELECT Play_Count FROM music_ranking WHERE Music_Id = :music_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':music_id', $this->music_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $sql = "UPDATE music_ranking SET Play_Count = Play_Count + 1 WHERE Music_Id = :music_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':music_id', $this->music_id);
            return $stmt->execute();    
        } else {
            // $this->music_id = $music_id;
            $this->play_count = 1;
            // dd($this->music_id);
            return $this->save();
        }
    }

    public function save()
    {
        $db = Database::getConnection();

        $sql = "INSERT INTO music_ranking (Music_Id, Play_Count) VALUES (:music_id, :play_count)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':music_id', $this->music_id);
        $stmt->bindParam(':play_count', $this->play_count);
        // dd($this->music_id);
        return $stmt->execute();
    }
}