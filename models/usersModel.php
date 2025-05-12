<?php
class UserModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getUserByEmail($email)
    {
        $email  = mysqli_real_escape_string($this->conn, $email);
        $sql    = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function getUserByEmailIgnoreStatus($email)
    {
        $email  = mysqli_real_escape_string($this->conn, $email);
        $sql    = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function getUserById($id)
    {
        $id     = mysqli_real_escape_string($this->conn, $id);
        $sql    = "SELECT * FROM users WHERE id = '$id' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }
    public function getUserWhereRoleFraternalCounselor()
    {
        $sql    = "SELECT * FROM `users` WHERE `role` = 'fraternal-counselor'";
        $result = mysqli_query($this->conn, $sql);

        $users = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }

        return $users;
    }

    public function createUser($firstname, $lastname, $kcfapicode, $email, $phone_number, $password, $confirmPassword, $role)
    {
        $firstname       = mysqli_real_escape_string($this->conn, $firstname);
        $lastname        = mysqli_real_escape_string($this->conn, $lastname);
        $kcfapicode      = mysqli_real_escape_string($this->conn, $kcfapicode);
        $email           = mysqli_real_escape_string($this->conn, $email);
        $phone_number    = mysqli_real_escape_string($this->conn, $phone_number);
        $password        = mysqli_real_escape_string($this->conn, $password);
        $confirmPassword = mysqli_real_escape_string($this->conn, $confirmPassword);
        $role            = mysqli_real_escape_string($this->conn, $role);
        $status          = 'pending';

        if ($password === $confirmPassword) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $createUser = "INSERT INTO users(`firstname`, `lastname`, `kcfapicode`, `email`,`phone_number`, `password`, `role`, `status`)
                           VALUES('$firstname', '$lastname', '$kcfapicode', '$email','$phone_number', '$hashedPassword', '$role', '$status')";

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

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'views/login.php');
        exit;
    }

}
