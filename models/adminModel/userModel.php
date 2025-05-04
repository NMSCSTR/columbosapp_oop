<?php
class UserModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getAllUser()
    {
        $sql    = "SELECT * FROM users WHERE role != 'admin'";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $councils = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $councils[] = $row;
            }
            return $councils;
        }

        return null;
    }

    public function countAllUser()
    {
        $sql    = "SELECT COUNT(*) as total FROM users WHERE role != 'admin'";
        $result = mysqli_query($this->conn, $sql);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            return $row['total'];
        } else {
            return 0;
        }
    }

    public function countUsersByRole($role)
    {
        $sql  = "SELECT COUNT(*) as total FROM users WHERE role = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $role);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            return $row['total'];
        } else {
            return 0;
        }
    }

    public function getUserGrowthPerMonth()
    {
        $sql = "
        SELECT DATE_FORMAT(created_at, '%b') AS month, COUNT(*) AS count
        FROM users
        WHERE YEAR(created_at) = YEAR(CURDATE()) AND role != 'admin'
        GROUP BY MONTH(created_at)
        ORDER BY MONTH(created_at)
    ";

        $result = mysqli_query($this->conn, $sql);
        $data   = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['month']] = $row['count'];
        }

        return $data;
    }

    public function countUsersGroupedByRole()
    {
        $sql    = "SELECT role, COUNT(*) as total FROM users WHERE role != 'admin' GROUP BY role";
        $result = mysqli_query($this->conn, $sql);

        $counts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $counts[$row['role']] = $row['total'];
        }

        return $counts;
    }

    public function createUser($firstname, $lastname, $email, $kcfapicode, $password, $confirmPassword, $role)
    {
        $firstname       = mysqli_real_escape_string($this->conn, $firstname);
        $lastname        = mysqli_real_escape_string($this->conn, $lastname);
        $email           = mysqli_real_escape_string($this->conn, $email);
        $kcfapicode      = mysqli_real_escape_string($this->conn, $kcfapicode);
        $password        = mysqli_real_escape_string($this->conn, $password);
        $confirmPassword = mysqli_real_escape_string($this->conn, $confirmPassword);
        $role            = mysqli_real_escape_string($this->conn, $role);

        if ($password === $confirmPassword) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $createUser = "INSERT INTO users(`firstname`,`lastname`,`username`,`kcfapicode`,`password`,`role`)
                           VALUES('$firstname','$lastname','$email','$kcfapicode','$hashedPassword','$role')";

            $result = mysqli_query($this->conn, $createUser);
            return $result ? true : false;
        }

        return false;
    }

    public function deleteUser($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);

        $deleteQuery = "DELETE FROM users WHERE id = '$id'";
        $result      = mysqli_query($this->conn, $deleteQuery);
        return $result ? true : false;
    }

    public function updateUserStatus($id, $status)
    {
        $id     = mysqli_real_escape_string($this->conn, $id);
        $status = mysqli_real_escape_string($this->conn, $status);

        $sql = "UPDATE users SET status = '$status' WHERE id = $id";
        return mysqli_query($this->conn, $sql);
    }

}
