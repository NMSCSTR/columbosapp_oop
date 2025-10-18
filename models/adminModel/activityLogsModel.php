<?php
class activityLogsModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

public function getAllLogs()
        {
            $sql = "SELECT 
                        al.log_id, 
                        al.admin_id, 
                        al.action_type, 
                        al.entity_type, 
                        al.entity_id, 
                        al.action_details, 
                        al.timestamp, 
                        al.old_value, 
                        al.new_value,
                        u.firstname,
                        u.lastname
                    FROM activity_logs al
                    LEFT JOIN users u ON al.admin_id = u.id /* Join to fetch the user's name */
                    ORDER BY al.timestamp DESC";
            
            $result = mysqli_query($this->conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $logs = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $logs[] = $row;
                }
                return $logs;
            }

            return [];
        }

    public function logActivity(
        $userId,
        $actionType,
        $entityType,
        $entityId,
        $actionDetails,
        $oldValue = null,
        $newValue = null
    ) {

        $cleanUserId      = (int) $userId;
        $cleanActionType  = mysqli_real_escape_string($this->conn, $actionType);
        $cleanEntityType  = mysqli_real_escape_string($this->conn, $entityType);
        $cleanEntityId    = is_numeric($entityId) ? (int) $entityId : 'NULL';
        $cleanActionDetails = mysqli_real_escape_string($this->conn, $actionDetails);


        $cleanOldValue    = is_null($oldValue) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, $oldValue) . "'";
        $cleanNewValue    = is_null($newValue) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, $newValue) . "'";


        $sql = "INSERT INTO activity_logs 
                (admin_id, action_type, entity_type, entity_id, action_details, old_value, new_value) 
                VALUES 
                (
                    $cleanUserId, 
                    '$cleanActionType', 
                    '$cleanEntityType', 
                    $cleanEntityId, 
                    '$cleanActionDetails', 
                    $cleanOldValue, 
                    $cleanNewValue
                )";

    
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            error_log("Activity Log Error: " . mysqli_error($this->conn));
            return false;
        }

        return true;
    }
}

