<?php
class Transaction
{
    private $conn;
    private $table_name = "transaction";

    public $id;
    public $amount;
    public $user_id;
    public $note;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create()
    {
        $query = "INSERT INTO
                " . $this->table_name . "
            SET 
            amount=:amount, 
            user_id=:user_id, 
            note=:note";

        $stmt = $this->conn->prepare($query);

        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->note = htmlspecialchars(strip_tags($this->note));

        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":note", $this->note);

        return $stmt->execute();
    }

    public function getLastTranByUser($user_id)
    {
        $query = "SELECT id FROM " . $this->table_name . "  WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    public function read_user($user_id, $from_record_num, $records_per_page)
    {
        $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            WHERE user_id = $user_id
            ORDER BY
                created_at DESC
                LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function read()
    {
        $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            ORDER BY
                created_at";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    public function readPaging($from_record_num, $records_per_page)
    {
        $query = "SELECT
                * FROM
                " . $this->table_name . "
            ORDER BY created_at DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function readOne($id)
    {
        $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            WHERE
                id = $id
            LIMIT
                0,1";

        $stmt = $this->conn->prepare($query);

        // $stmt->bindParam(1, $this->id);

        $stmt->execute();
        return $stmt;
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // $this->amount = $row['amount'];
        // $this->user_id = $row['user_id'];
        // $this->note = $row['note'];
        // $this->created_at = $row['created_at'];
        // $this->updated_at = $row['updated_at'];
    }
    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}
?>
