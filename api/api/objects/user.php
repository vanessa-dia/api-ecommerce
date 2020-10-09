<?php
class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $address;
    public $phone;
    public $Account;
    public $password;
    public $avatar;
    public $created_at;
    public $updated_at;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->avatar = "avt_01.png";
    }

    function create()
    {
        $query = "INSERT INTO " . $this->table_name . "
            SET
                name = :name,
                email = :email,
                address = :address,
                phone = :phone,
                Account = :Account,
                password = :password,
                avatar =:avatar,
                created_at=:created_at,
                updated_at=:updated_at";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->Account = htmlspecialchars(strip_tags($this->Account));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->avatar = htmlspecialchars(strip_tags($this->avatar));
        $this->created_at = htmlspecialchars(strip_tags($this->created_at));
        $this->updated_at = htmlspecialchars(strip_tags($this->updated_at));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':Account', $this->Account);
        $stmt->bindParam(':avatar', $this->avatar);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':updated_at', $this->updated_at);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);

        if ($stmt->execute()) {
            return true;
        }
        return false;
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
        // $this->email = $row['email'];
        // $this->address = $row['address'];
        // $this->phone = $row['phone'];
        // $this->Account = $row['Account'];
        // $this->password = $row['password'];
        // $this->avatar = $row['avatar'];
        // $this->created_at = $row['created_at'];
        // $this->updated_at = $row['updated_at'];
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
    function accExists()
    {
        $query = "SELECT id, name, email, address, phone, password
            FROM " . $this->table_name . "
            WHERE Account = ?
            LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->Account = htmlspecialchars(strip_tags($this->Account));

        $stmt->bindParam(1, $this->Account);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->address = $row['address'];
            $this->phone = $row['phone'];
            $this->password = $row['password'];

            return true;
        }

        return false;
    }

    public function update()
    {
        // $password_set = !empty($this->password) ? ", password = :password" : "";

        $query = "UPDATE " . $this->table_name . "
            SET
                name = :name,
                email = :email,
                address = :address,
                phone = :phone,
                password=:password
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        // $this->Account = htmlspecialchars(strip_tags($this->Account));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone', $this->phone);
        // $stmt->bindParam(':Account', $this->Account);
        $stmt->bindParam(':password', $this->password);

        if (!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

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