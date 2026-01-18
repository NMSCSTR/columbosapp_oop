<?php
class fraternalBenefitsModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // 1. Get all categories for a specific plan
    public function getRateCategoriesByPlan($planId)
    {
        $sql = "SELECT * FROM benefit_rate_categories WHERE plan_id = ? ORDER BY is_adb ASC, min_face_value ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $planId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // 2. Get all rates for a specific plan (joined with categories)
    public function getRatesByPlan($planId)
    {
        $sql = "SELECT r.*, c.name as category_name, c.is_adb 
                FROM benefit_rates r
                JOIN benefit_rate_categories c ON r.category_id = c.id
                WHERE c.plan_id = ?
                ORDER BY r.min_age ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $planId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // 3. Delete Category
    public function deleteRateCategory($id) {
        $sql = "DELETE FROM benefit_rate_categories WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt);
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

    // ---------------------------------------------------------
    // NEW METHODS ADDED BELOW TO FIX THE FATAL ERROR
    // ---------------------------------------------------------

    // Create a Column (e.g., "50K - <100K")
    public function addRateCategory($planId, $name, $minFace, $maxFace, $isAdb = 0)
    {
        // Handle NULL for maxFace (infinity)
        $maxFaceVal = ($maxFace === '' || $maxFace === null) ? 'NULL' : "'$maxFace'";
        
        $planId = mysqli_real_escape_string($this->conn, $planId);
        $name = mysqli_real_escape_string($this->conn, $name);
        $minFace = mysqli_real_escape_string($this->conn, $minFace);
        // maxFaceVal is already handled above
        $isAdb = mysqli_real_escape_string($this->conn, $isAdb);

        $sql = "INSERT INTO `benefit_rate_categories` (`plan_id`, `name`, `min_face_value`, `max_face_value`, `is_adb`) 
                VALUES ('$planId', '$name', '$minFace', $maxFaceVal, '$isAdb')";
        
        if(mysqli_query($this->conn, $sql)){
             return mysqli_insert_id($this->conn);
        }
        return false;
    }

    // Add a Rate Row (e.g., Age 1-30, Rate 4.57)
    public function addRate($categoryId, $minAge, $maxAge, $rate)
    {
        $categoryId = mysqli_real_escape_string($this->conn, $categoryId);
        $minAge = mysqli_real_escape_string($this->conn, $minAge);
        $maxAge = mysqli_real_escape_string($this->conn, $maxAge);
        $rate = mysqli_real_escape_string($this->conn, $rate);

        $sql = "INSERT INTO `benefit_rates` (`category_id`, `min_age`, `max_age`, `rate`) 
                VALUES ('$categoryId', '$minAge', '$maxAge', '$rate')";
        return mysqli_query($this->conn, $sql);
    }

}
?>