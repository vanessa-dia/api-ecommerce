<?php
class Order
{
    private $conn;
    private $table_name = "orders";

    public $id;
    public $transaction_id;
    public $product_id;
    public $soluong;
    public $gia;
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
            transaction_id=:transaction_id, 
            product_id=:product_id, 
            soluong=:soluong,
            gia=:gia";

        $stmt = $this->conn->prepare($query);

        $this->transaction_id = htmlspecialchars(strip_tags($this->transaction_id));
        $this->product_id = htmlspecialchars(strip_tags($this->product_id));
        $this->soluong = htmlspecialchars(strip_tags($this->soluong));
        $this->gia = htmlspecialchars(strip_tags($this->gia));

        $stmt->bindParam(":transaction_id", $this->transaction_id);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->bindParam(":soluong", $this->soluong);
        $stmt->bindParam(":gia", $this->gia);

        return $stmt->execute();
    }
    function readOne()
    {
        $query = "SELECT
                *
            FROM
                " . $this->table_name . " o
                LEFT JOIN
                    type t
                        ON o.transaction_id = o.id
            WHERE
                o.id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->transaction_id = $row['transaction_id'];
        $this->product_id = $row['product_id'];
        $this->soluong = $row['soluong'];
        $this->gia = $row['gia'];
        $this->created_at = $row['created_at'];
    }
    public function read_detail($trans, $from_record_num, $records_per_page)
    {
        $query = "SELECT
                p.name as product, o.id, o.transaction_id, o.product_id, o.soluong, o.gia, o.created_at, p.name as product
            FROM
                " . $this->table_name . " o
                LEFT JOIN
                    transaction tr
                        ON o.transaction_id = tr.id
                LEFT JOIN
                    product p
                        ON o.product_id = p.id
            WHERE o.transaction_id = $trans
            ORDER BY p.name DESC
            LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        //$result = $stmt->fetch(PDO::FETCH_ASSOC);
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