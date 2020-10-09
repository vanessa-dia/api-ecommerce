<?php
class Product
{

    private $conn;
    private $table_name = "product";

    public $id;
    public $name;
    public $soluong;
    public $gia;
    public $avatar;
    public $category;
    public $type;
    public $type_name;
    public $content;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    function read()
    {
        // select all query
        $query = "SELECT
                c.name as cate_name, t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category,p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
                LEFT JOIN
                    category c
                        ON p.category = c.id
            ORDER BY
                p.created_at DESC";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
    public function read_type($type, $from_record_num, $records_per_page)
    {
        $query = "SELECT
                c.name as cate_name, t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
                LEFT JOIN
                    category c
                        ON p.category = c.id
            WHERE p.type = $type
            ORDER BY p.name DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }
    public function getGiaById($id)
    {
        $query = "SELECT gia FROM " . $this->table_name . " WHERE id = $id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['gia'];
    }
    public function read_category($category, $from_record_num, $records_per_page)
    {

        //select all data
        $query = "SELECT
                c.name as cate_name, t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    category c
                        ON p.category = c.id
                LEFT JOIN 
                    type t
                        ON p.type = t.id
            WHERE p.category = $category
            ORDER BY p.name DESC
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
                name=:name,
                soluong=:soluong,
                gia=:gia,
                category=:category,
                type=:type,
                avatar=:avatar,
                content=:content,
                created_at=:created_at,
                updated_at=:updated_at";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->soluong = htmlspecialchars(strip_tags($this->soluong));
        $this->gia = htmlspecialchars(strip_tags($this->gia));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":soluong", $this->soluong);
        $stmt->bindParam(":gia", $this->gia);
        $stmt->bindParam(":avatar", $this->avatar);
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":created_at", $this->created_at);
        $stmt->bindParam(":updated_at", $this->updated_at);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    function readOne($id)
    {
        $query = "SELECT
                c.name as cate_name, t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
                LEFT JOIN
                    category c
                        ON p.category = c.id
            WHERE
                p.id = $id
            LIMIT
                0,1";

        $stmt = $this->conn->prepare($query);

        // $stmt->bindParam(1, $this->id);

        $stmt->execute();
        return $stmt;
        //$row = $stmt->fetch(PDO::FETCH_ASSOC);

        //$this->name = $row['name'];
        //$this->soluong = $row['soluong'];
        //$this->gia = $row['gia'];
        // $this->avatar = $row['avatar'];
        // $this->category = $row['category'];
        // $this->cate_name = $row['cate_name'];
        // $this->type = $row['type'];
        // $this->type_name = $row['type_name'];
        // $this->content = $row['content'];
        // $this->created_at = $row['created_at'];
        // $this->updated_at = $row['updated_at'];
    }
    function update()
    {
        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                gia = :gia,
                soluong = :soluong,
                content = :content
                
            WHERE
                id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->gia = htmlspecialchars(strip_tags($this->gia));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        //$this->soluong = htmlspecialchars(strip_tags($this->soluong));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':gia', $this->gia);
        //$stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':soluong', $this->soluong);
        $stmt->bindParam(':content', $this->content);
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
    function search($keywords)
    {
        $query = "SELECT
                t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
            WHERE
                p.name LIKE ? or p.type LIKE ?
            ORDER BY
                p.created_at DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);

        $stmt->execute();
        return $stmt;
    }
    public function readPaging($from_record_num, $records_per_page)
    {
        $query = "SELECT
                c.name as cate_name, t.name as type_name, p.id, p.name, p.soluong, p.gia, p.avatar, p.category, p.type, p.content, p.created_at, p.updated_at
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    type t
                        ON p.type = t.id
                LEFT JOIN
                    category c
                        ON p.category = c.id
            ORDER BY p.created_at DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
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
