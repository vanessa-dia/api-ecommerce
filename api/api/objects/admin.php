<?php
class Admin
{
    private $conn;
    private $table_name = "admins";

    public $id;
    public $name;
    public $email;
    public $address;
    public $phone;
    public $account;
    public $password;
    public $avatar;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->avatar = "avt_02.png";
    }
    function VERIFY()
    {
        $query = "SELECT id, name, address, email, password, phone, created_at, updated_at
            FROM " . $this->table_name . "
            WHERE account=:account AND password=:password";
        $stmt = $this->conn->prepare($query);

        $this->account = htmlspecialchars(strip_tags($this->account));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':account', $this->account);
        $stmt->bindParam(':password', $this->password);
        
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->address = $row['address'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];

            return true;
        }
        return false;
    }
    public function readAll()
    {
        $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            ORDER BY
                name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    function readOne($id)
    {
        $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            WHERE id = $id";

        $stmt = $this->conn->prepare($query);

        // $stmt->bindParam(1, $this->id);

        $stmt->execute();
        return $stmt;
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // $this->name = $row['name'];
        // $this->address = $row['address'];
        // $this->email = $row['email'];
        // $this->Account = $row['account'];
        // $this->password = $row['password'];
        // $this->phone = $row['phone'];
        // $this->avatar = $row['avatar'];
        // $this->created_at = $row['created_at'];
        // $this->updated_at = $row['updated_at'];
    }
    public function readPaging($from_record_num, $records_per_page)
    {
        $query = "SELECT
                id, name, address, email, phone, account, password, avatar, created_at, updated_at
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
