<?php
class CouncilModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getAllCouncil()
    {
        $sql    = "SELECT * FROM council";
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

    public function getAllCouncilByFc($fcid){
        $fcid = mysqli_real_escape_string($this->conn, $fcid);
        $sql = "SELECT * FROM council WHERE fraternal_counselor_id = '$fcid'";
        $result = mysqli_query($this->conn, $sql);

        if($result && mysqli_num_rows($result) > 0){
            $councils = [];
            while($row = mysqli_fetch_assoc($result)){
                $councils[] = $row;
            }
            return $councils;
        }
    }

    public function getAllCouncilByUM($umid){
        $umid = mysqli_real_escape_string($this->conn, $umid);
        $sql = "SELECT * FROM council WHERE unit_manager_id = '$umid'";
        $result = mysqli_query($this->conn, $sql);

        if($result && mysqli_num_rows($result) > 0){
            $councils = [];
            while($row = mysqli_fetch_assoc($result)){
                $councils[] = $row;
            }
            return $councils;
        }

    }

    public function getCouncilById($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);
        $sql = "SELECT * FROM council WHERE council_id = '$id' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
    
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
    
        return null;
    }
    

    public function getUserNameById($id, $role)
    {
        $id   = mysqli_real_escape_string($this->conn, $id);
        $role = mysqli_real_escape_string($this->conn, $role);

        $sql    = "SELECT firstname, lastname FROM users WHERE id = '$id' AND role = '$role'";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            return $user['firstname'] . ' ' . $user['lastname'];
        }

        return null;
    }

    public function insertCouncil($council_number, $council_name, $unit_manager_id, $fraternal_counselor_id, $date_established, $date_created)
    {
        $council_number         = mysqli_real_escape_string($this->conn, $council_number);
        $council_name           = mysqli_real_escape_string($this->conn, $council_name);
        $unit_manager_id        = mysqli_real_escape_string($this->conn, $unit_manager_id);
        $fraternal_counselor_id = mysqli_real_escape_string($this->conn, $fraternal_counselor_id);
        $date_established       = mysqli_real_escape_string($this->conn, $date_established);
        $date_created           = mysqli_real_escape_string($this->conn, $date_created);

        $insertQuery = "INSERT INTO `council`(`council_number`, `council_name`, `unit_manager_id`, `fraternal_counselor_id`, `date_established`, `date_created`)
                        VALUES ('$council_number', '$council_name', '$unit_manager_id', '$fraternal_counselor_id', '$date_established', '$date_created')";

        $result = mysqli_query($this->conn, $insertQuery);
        return $result ? true : false;
    }

    public function updateCouncil($council_id, $council_number, $council_name, $unit_manager_id, $fraternal_counselor_id, $date_established)
    {
        $council_id             = mysqli_real_escape_string($this->conn, $council_id);
        $council_number         = mysqli_real_escape_string($this->conn, $council_number);
        $council_name           = mysqli_real_escape_string($this->conn, $council_name);
        $unit_manager_id        = mysqli_real_escape_string($this->conn, $unit_manager_id);
        $fraternal_counselor_id = mysqli_real_escape_string($this->conn, $fraternal_counselor_id);
        $date_established       = mysqli_real_escape_string($this->conn, $date_established);

        $updateQuery = "UPDATE council
                    SET council_number = '$council_number',
                        council_name = '$council_name',
                        unit_manager_id = '$unit_manager_id',
                        fraternal_counselor_id = '$fraternal_counselor_id',
                        date_established = '$date_established'
                    WHERE council_id = '$council_id'";

        $result = mysqli_query($this->conn, $updateQuery);
        return $result ? true : false;
    }

    public function deleteCouncil($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);

        $deleteQuery = "DELETE FROM council WHERE council_id = '$id'";
        $result      = mysqli_query($this->conn, $deleteQuery);
        return $result ? true : false;
    }

}
