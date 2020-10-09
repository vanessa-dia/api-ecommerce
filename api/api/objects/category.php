<?php
class Category
{
    private $conn;
    private $table_name = "category";

    public $id;
    public $name;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    function readOne($id)
    {
        $query = "SELECT
                id, name, created_at, updated_at
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

        // $this->name = $row['name'];
        // $this->created_at = $row['created_at'];
        // $this->updated_at = $row['updated_at'];
    }
    public function readPaging($from_record_num, $records_per_page)
    {
        $query = "SELECT
                id, name, created_at, updated_at
            FROM
                " . $this->table_name . "
            ORDER BY created_at DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }
    public function readAll()
    {
        $query = "SELECT
                    id, name, created_at, updated_at
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    public function read()
    {
        $query = "SELECT
                id, name, created_at, updated_at
            FROM
                " . $this->table_name . " 
            ORDER BY
                name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    function create()
    {
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));

        $stmt->bindParam(":name", $this->name);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    function update()
    {
        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name      
            WHERE
                id = :id";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    function delete()
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