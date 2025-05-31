<?php
namespace project\models;

use project\models\Model;
use project\core\Database;
use app\core\Application;
use PDO;
use PDOException;

class MusicReport extends Model
{
    private static $table = 'music_report';
    public $report_id = '';
    public $music_id = '';
    public $created_at = '';

    public function reportInsert()
    {
        $db = Database::getConnection();

        $sql = "INSERT INTO music_report (Music_Id) VALUES (:music_id)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':music_id', $this->music_id);

        return $stmt->execute();
    }

    public function getAllReportedMusic()
    {
        $db = Database::getConnection();
        $sql = "SELECT mr.Report_Id, m.Music_Id, m.Music_Name, m.Is_Adult, mr.Created_At
            FROM music_report mr
            JOIN music m ON mr.Music_Id = m.Music_Id";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateIsAdult($music_id, $is_adult)
    {
        $db = Database::getConnection();
        $sql = "UPDATE music SET Is_Adult = :is_adult WHERE Music_Id = :music_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':is_adult', $is_adult);
        $stmt->bindParam(':music_id', $music_id);
        return $stmt->execute();
    }

    public function deleteReport($report_Id)
    {
        $db = Database::getConnection();
        $sql = "DELETE FROM music_report WHERE Report_Id = :report_Id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':report_Id', $report_Id);
        return $stmt->execute();
    }

    public function getReportById($report_Id)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM music_report WHERE Report_Id = :report_Id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':report_Id', $report_Id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}