<?php
class Type
{
    private $conn;
    private $table_name = "type";

    public $id;
    public $name;
    public $category;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readAll()
    {
        $query = "SELECT
                    id, name, category
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    public function read($category)
    {
        $query = "SELECT
                t.id, t.name, t.category
            FROM
                " . $this->table_name . " t
                LEFT JOIN
                    category c
                        ON t.category = c.id
            WHERE t.category = $category
            ORDER BY
                name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function read_category($category, $from_record_num, $records_per_page)
    {
        $query = "SELECT
                c.name as cate_name, t.id, t.name, t.category
            FROM
                " . $this->table_name . " t
                LEFT JOIN
                    category c
                        ON t.category = c.id
            WHERE t.category = $category
            ORDER BY t.name DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    function readOne($id)
    {
        $query = "SELECT
                c.name as cate_name, t.id, t.name, t.category
            FROM
                " . $this->table_name . " t
                LEFT JOIN
                    category c
                        ON t.category = c.id
            WHERE
                t.id = $id
            LIMIT
                0,1";

        $stmt = $this->conn->prepare($query);

        // $stmt->bindParam(1, $this->id);

        $stmt->execute();

        return $stmt;
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $this->name = $row['name'];
        // $this->category = $row['category'];
        // $this->cate_name = $row['cate_name'];

    }
    public function readPaging($from_record_num, $records_per_page)
    {
        $query = "SELECT
                c.name as cate_name, t.id, t.name, t.category
            FROM
                " . $this->table_name . " t
                LEFT JOIN
                    category c
                        ON t.category = c.id
            ORDER BY t.category DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }
    function create()
    {
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, category=:category";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->category = htmlspecialchars(strip_tags($this->category));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":category", $this->category);

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
        //$this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        //$stmt->bindParam(':category', $this->category);
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