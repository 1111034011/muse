<?php
namespace project\models;

use PDO;
use project\models\Model;
use project\core\Database;

// TODO: Rename db table member_att to sub_member
// TODO: Rename db column Member_Add_Id to Sub_Member_Id
// TODO: Rename db column Music_Preference to Preferences with json type
class SubUser extends Model
{
    private static $table = 'sub_member';
    public $sub_member_id = '';
    public $member_id = '';
    public $username = '';
    public $pin_num = '';
    public $preferences = '';
    public $is_adult = false;
    public $is_owner = false;

    public function save()
    {
        $db = Database::getConnection();

        $sql = "INSERT INTO sub_member (Member_Id, Username, Pin_Num, Is_Adult, Is_Owner) VALUES (:member_id, :username, :pin_num, :is_adult, :is_owner)";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':member_id', $this->member_id);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':pin_num', $this->pin_num);
        $stmt->bindParam(':is_adult', $this->is_adult, PDO::PARAM_BOOL);
        $stmt->bindParam(':is_owner', $this->is_owner, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    //user information edit
    public function update()
    {
        $db = Database::getConnection();

        $fields = [];
        $params = [':sub_member_id' => $this->sub_member_id];

        $fieldMappings = [
            'member_id' => 'Member_Id',
            'username' => 'Username',
            'pin_num' => 'Pin_Num',
            'preferences' => 'Preferences',
        ];

        foreach ($fieldMappings as $property => $dbField) {
            if (!empty($this->$property)) {
                $paramName = ':' . $property;
                $fields[] = "$dbField = $paramName";
                $params[$paramName] = $this->$property;
            }
        }

        if (empty($fields)) {
            return true;
        }

        $sql = "UPDATE sub_member SET " . implode(', ', $fields) . " WHERE Sub_Member_Id = :sub_member_id";
        $stmt = $db->prepare($sql);

        return $stmt->execute($params);
    }

    public function findByMemberId(string $member_id)
    {
        $db = Database::getConnection();

        $sql = "SELECT Sub_Member_Id, Member_Id, Username, Is_Owner, Is_Adult FROM sub_member WHERE Member_Id = :member_id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':member_id', $member_id);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($item) {
            return [
                'sub_member_id' => $item['Sub_Member_Id'],
                'member_id' => $item['Member_Id'],
                'username' => $item['Username'],
                'is_owner' => $item['Is_Owner'],
                'is_adult' => $item['Is_Adult'],
                // 'preferences' => !empty($item['Preferences']) ? json_decode($item['Preferences'], true) : null
            ]; 
        }, $rows);
    }

    public function findById(string $sub_member_id, string $member_id)
    {
        $db = Database::getConnection();

        $sql = "SELECT Sub_Member_Id, Member_Id, Username, Preferences FROM sub_member WHERE Sub_Member_Id = :sub_member_id";
        if ($member_id) {
            $sql .= " AND Member_Id = :member_id";
        }

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':sub_member_id', $sub_member_id);
        if ($member_id) {
            $stmt->bindParam(':member_id', $member_id);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $sub_user = new SubUser();
        $sub_user->sub_member_id = $row['Sub_Member_Id'];
        $sub_user->member_id = $row['Member_Id'];
        $sub_user->username = $row['Username'];
        $sub_user->preferences = !empty($row['Preferences']) ? json_decode($row['Preferences'], true) : null;

        return $sub_user;
    }

    public function delete(string $sub_member_id, string $member_id)
    {
        $db = Database::getConnection();

        $sql = "DELETE FROM sub_member WHERE Sub_Member_Id = :sub_member_id AND Member_Id = :member_id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':sub_member_id', $sub_member_id);
        $stmt->bindParam(':member_id', $member_id);

        return $stmt->execute();
    }

    public function findByUsername(string $username, string $member_id)
    {
        $db = Database::getConnection();

        $sql = "SELECT * FROM sub_member WHERE Username = :username AND Member_Id = :member_id";
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':member_id', $member_id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $sub_user = new SubUser();
        $sub_user->sub_member_id = $row['Sub_Member_Id'];
        $sub_user->member_id = $row['Member_Id'];
        $sub_user->username = $row['Username'];
        $sub_user->pin_num = $row['Pin_Num'];
        $sub_user->preferences = !empty($row['Preferences']) ? json_decode($row['Preferences'], true) : null;
        $sub_user->is_adult = $row['Is_Adult'] === 1;
        $sub_user->is_owner = $row['Is_Owner'] === 1;

        return $sub_user;
    }

    public function countSubUsers(string $member_id)
    {
        $db = Database::getConnection();
        $sql = "SELECT COUNT(*) as count FROM sub_member WHERE Member_Id = :member_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':member_id', $member_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }


    public function updateIsAdult($sub_member_id, $is_adult)
    {
        $db = Database::getConnection();
        $sql = "UPDATE sub_member SET Is_Adult = :is_adult WHERE Sub_Member_Id = :sub_member_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':is_adult', $is_adult, PDO::PARAM_BOOL);
        $stmt->bindParam(':sub_member_id', $sub_member_id);
        return $stmt->execute();
    }
}

