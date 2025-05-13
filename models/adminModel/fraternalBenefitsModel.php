<?php
class fraternalBenefitsModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getAllFraternalBenefits()
    {
        $sql    = "SELECT * FROM `fraternal_benefits`";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $fraternalBenefits = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $fraternalBenefits[] = $row;
            }
            return $fraternalBenefits;
        }

        return null;

    }

    public function getFraternalBenefitById($id)
    {
        $sql  = "SELECT * FROM `fraternal_benefits` WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function insertFraternalBenefits($type, $name, $about,$face_value, $years_to_maturity, $years_of_protection, $benefits, $contribution_period, $image)
    {
        $type                = mysqli_real_escape_string($this->conn, $type);
        $name                = mysqli_real_escape_string($this->conn, $name);
        $about               = mysqli_real_escape_string($this->conn, $about);
        $face_value          = mysqli_real_escape_string($this->conn, $face_value);
        $years_to_maturity   = mysqli_real_escape_string($this->conn, $years_to_maturity);
        $years_of_protection = mysqli_real_escape_string($this->conn, $years_of_protection);
        $benefits            = mysqli_real_escape_string($this->conn, $benefits);
        $contribution_period = mysqli_real_escape_string($this->conn, $contribution_period);
        $image               = mysqli_real_escape_string($this->conn, $image);

        $insertFraternalBenefits = "INSERT INTO `fraternal_benefits`(`type`, `name`, `about`, `face_value`, `years_to_maturity`, `years_of_protection`, `benefits`, `contribution_period`, `image`) VALUES ('$type','$name','$about', '$face_value', '$years_to_maturity', '$years_of_protection', '$benefits','$contribution_period','$image')";

        $result = mysqli_query($this->conn, $insertFraternalBenefits);
        return $result ? true : false;
    }

    public function updateFraternalBenefits($id, $type, $name, $about, $face_value, $years_to_maturity, $years_of_protection, $benefits, $contribution_period, $image_path)
    {

        $id                  = mysqli_real_escape_string($this->conn, $id);
        $type                = mysqli_real_escape_string($this->conn, $type);
        $name                = mysqli_real_escape_string($this->conn, $name);
        $about               = mysqli_real_escape_string($this->conn, $about);
        $face_value          = mysqli_real_escape_string($this->conn, $face_value);
        $years_to_maturity   = mysqli_real_escape_string($this->conn, $years_to_maturity);
        $years_of_protection = mysqli_real_escape_string($this->conn, $years_of_protection);
        $benefits            = mysqli_real_escape_string($this->conn, $benefits);
        $contribution_period = mysqli_real_escape_string($this->conn, $contribution_period);
        $image_path          = mysqli_real_escape_string($this->conn, $image_path);

        $updateQuery = "UPDATE `fraternal_benefits` SET `type`='$type', `name`='$name', `about`='$about', `face_value`='$face_value',`years_to_maturity`='$years_to_maturity',`years_of_protection`='$years_of_protection', `benefits`='$benefits', `contribution_period`='$contribution_period', `image`='$image_path' WHERE `id`='$id'";

        $result = mysqli_query($this->conn, $updateQuery);
        return $result ? true : false;
    }

    public function deleteFraternalBenefits($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);

        $deleteQuery = "DELETE FROM fraternal_benefits WHERE id = '$id'";
        $result      = mysqli_query($this->conn, $deleteQuery);
        return $result ? true : false;
    }

}
