<?php 
class FormsModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function viewAllForms() {
        $sql = "SELECT * FROM files ORDER BY uploaded_on DESC";
        return mysqli_query($this->conn, $sql);
    }

    public function addForms($filename, $description, $file_located, $file_type, $uploaded_by, $uploaded_on) {
        $sql = "INSERT INTO files (filename, description, uploaded_on, uploaded_by, file_located, file_type) 
                VALUES ('$filename', '$description', '$uploaded_on', '$uploaded_by', '$file_located', '$file_type')";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteForms($id) {
        $sql = "DELETE FROM files WHERE id = $id";
        return mysqli_query($this->conn, $sql);
    }

    public function getFormById($id) {
        $sql = "SELECT * FROM files WHERE id = $id LIMIT 1";
        return mysqli_query($this->conn, $sql);
    }

    public function updateForms($id, $filename, $description, $file_type) {
        $sql = "UPDATE files 
                SET filename='$filename', description='$description', file_type='$file_type'
                WHERE id=$id";
        return mysqli_query($this->conn, $sql);
    }
}
?>
