<?php
// Database migration script to add status field to quota table
include 'includes/config.php';
include 'includes/db.php';

echo "Starting quota table migration...\n";

// Add status field to qouta table
$sql1 = "ALTER TABLE `qouta` ADD COLUMN `status` ENUM('on-progress', 'completed', 'expired') NOT NULL DEFAULT 'on-progress' AFTER `duration`";

if (mysqli_query($conn, $sql1)) {
    echo "✓ Added status field to qouta table\n";
} else {
    echo "✗ Error adding status field: " . mysqli_error($conn) . "\n";
}

// Update existing records to have appropriate status
$sql2 = "UPDATE `qouta` 
SET `status` = CASE 
    WHEN `duration` < CURDATE() THEN 'expired'
    WHEN `current_amount` >= `qouta` THEN 'completed'
    ELSE 'on-progress'
END";

if (mysqli_query($conn, $sql2)) {
    echo "✓ Updated existing quota records with appropriate status\n";
} else {
    echo "✗ Error updating existing records: " . mysqli_error($conn) . "\n";
}

// Add indexes for better performance
$sql3 = "CREATE INDEX `idx_quota_status` ON `qouta` (`status`)";
if (mysqli_query($conn, $sql3)) {
    echo "✓ Added index for quota status\n";
} else {
    echo "✗ Error adding status index: " . mysqli_error($conn) . "\n";
}

$sql4 = "CREATE INDEX `idx_quota_user_status` ON `qouta` (`user_id`, `status`)";
if (mysqli_query($conn, $sql4)) {
    echo "✓ Added index for user_id and status\n";
} else {
    echo "✗ Error adding user_status index: " . mysqli_error($conn) . "\n";
}

echo "Migration completed!\n";
mysqli_close($conn);




// Inside userModel.php
public function getUserStatus($userId)
{
    $cleanId = (int) $userId;
    // NOTE: This uses basic security; prepared statements are recommended.
    $sql = "SELECT status FROM users WHERE id = $cleanId";
    $result = mysqli_query($this->conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['status'];
    }
    return null; // User not found
}

// Inside userModel.php
public function updateUserStatus($userId, $newStatus)
{
    $cleanId = (int) $userId;
    $cleanStatus = mysqli_real_escape_string($this->conn, $newStatus);
    
    // NOTE: This uses basic security; prepared statements are recommended.
    $sql = "UPDATE users SET status = '$cleanStatus' WHERE id = $cleanId";
    
    return mysqli_query($this->conn, $sql); // Returns true on success, false on failure
}

$logModel = new activityLogsModel($db_conn);

$admin_user_id = 5;
$new_council_id = 78;

$logModel->logActivity(
    $admin_user_id,
    'COUNCIL_ADD',
    'council',
    $new_council_id,
    'Added new council: "Council #0078 - The Golden Circle".',
    null, // Old Value (set to null)
    'Council #0078 created' // New Value (set to a descriptive string)
);


$logModel = new activityLogsModel($db_conn);

$admin_user_id = 5;
$new_announcement_id = 45;

$logModel->logActivity(
    $admin_user_id,
    'ANNOUNCEMENT_POST',
    'announcements',
    $new_announcement_id,
    'Posted a new announcement: "Office Closure Notice".'
    // old_value and new_value are optional, so we omit them here
);


class activityLogsModel
{
    private $conn;

    /**
     * Constructor to initialize the database connection.
     * * @param mysqli $dbConnection The database connection object.
     */
    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    /**
     * Fetches all activity logs.
     * (Retained your original function, though ORDER BY should be 'timestamp').
     */
    public function getAllLogs()
    {
        // Changed 'date_posted' to 'timestamp' as per the new table schema
        $sql    = "SELECT * FROM activity_logs ORDER BY timestamp DESC"; 
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $logs = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $logs[] = $row;
            }
            return $logs;
        }

        return null;
    }

    // ------------------------------------------------------------------------
    // NEW REUSABLE FUNCTION TO LOG ACTIVITY
    // ------------------------------------------------------------------------

    /**
     * Records an activity log entry into the 'activity_logs' table.
     * NOTE: This implementation uses basic mysqli_real_escape_string for security 
     * due to the requested style, but prepared statements are highly recommended.
     *
     * @param int    $userId         The ID of the user (admin) who performed the action.
     * @param string $actionType     A broad category of the action (e.g., 'USER_STATUS_CHANGE').
     * @param string $entityType     The table/object affected (e.g., 'users', 'applicants').
     * @param int|null $entityId     The primary key of the affected record, or null.
     * @param string $actionDetails  A human-readable description of the action.
     * @param string|null $oldValue  Optional: JSON or text of the value before the change.
     * @param string|null $newValue  Optional: JSON or text of the value after the change.
     * * @return bool True on successful log creation, false otherwise.
     */
    public function logActivity(
        $userId,
        $actionType,
        $entityType,
        $entityId,
        $actionDetails,
        $oldValue = null,
        $newValue = null
    ) {
        // 1. Data Sanitization (CRITICAL without prepared statements)
        $cleanUserId      = (int) $userId;
        $cleanActionType  = mysqli_real_escape_string($this->conn, $actionType);
        $cleanEntityType  = mysqli_real_escape_string($this->conn, $entityType);
        $cleanEntityId    = is_numeric($entityId) ? (int) $entityId : 'NULL';
        $cleanActionDetails = mysqli_real_escape_string($this->conn, $actionDetails);

        // Escape and handle optional values
        $cleanOldValue    = is_null($oldValue) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, $oldValue) . "'";
        $cleanNewValue    = is_null($newValue) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, $newValue) . "'";

        // 2. Build the SQL Query
        $sql = "INSERT INTO activity_logs 
                (user_id, action_type, entity_type, entity_id, action_details, old_value, new_value) 
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

        // 3. Execute the Query
        $result = mysqli_query($this->conn, $sql);

        // 4. Check Result
        if (!$result) {
            // Log the error (optional but recommended for debugging)
            // error_log("Activity Log Error: " . mysqli_error($this->conn));
            return false;
        }

        return true;
    }
}




class activityLogsModel
{
    private $conn;

    /**
     * Constructor to initialize the database connection.
     * * @param mysqli $dbConnection The database connection object.
     */
    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    /**
     * Fetches all activity logs.
     * (Retained your original function, though ORDER BY should be 'timestamp').
     */
    public function getAllLogs()
    {
        // Changed 'date_posted' to 'timestamp' as per the new table schema
        $sql    = "SELECT * FROM activity_logs ORDER BY timestamp DESC"; 
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $logs = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $logs[] = $row;
            }
            return $logs;
        }

        return null;
    }

    // ------------------------------------------------------------------------
    // NEW REUSABLE FUNCTION TO LOG ACTIVITY
    // ------------------------------------------------------------------------

    /**
     * Records an activity log entry into the 'activity_logs' table.
     * NOTE: This implementation uses basic mysqli_real_escape_string for security 
     * due to the requested style, but prepared statements are highly recommended.
     *
     * @param int    $userId         The ID of the user (admin) who performed the action.
     * @param string $actionType     A broad category of the action (e.g., 'USER_STATUS_CHANGE').
     * @param string $entityType     The table/object affected (e.g., 'users', 'applicants').
     * @param int|null $entityId     The primary key of the affected record, or null.
     * @param string $actionDetails  A human-readable description of the action.
     * @param string|null $oldValue  Optional: JSON or text of the value before the change.
     * @param string|null $newValue  Optional: JSON or text of the value after the change.
     * * @return bool True on successful log creation, false otherwise.
     */
    public function logActivity(
        $userId,
        $actionType,
        $entityType,
        $entityId,
        $actionDetails,
        $oldValue = null,
        $newValue = null
    ) {
        // 1. Data Sanitization (CRITICAL without prepared statements)
        $cleanUserId      = (int) $userId;
        $cleanActionType  = mysqli_real_escape_string($this->conn, $actionType);
        $cleanEntityType  = mysqli_real_escape_string($this->conn, $entityType);
        $cleanEntityId    = is_numeric($entityId) ? (int) $entityId : 'NULL';
        $cleanActionDetails = mysqli_real_escape_string($this->conn, $actionDetails);

        // Escape and handle optional values
        $cleanOldValue    = is_null($oldValue) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, $oldValue) . "'";
        $cleanNewValue    = is_null($newValue) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, $newValue) . "'";

        // 2. Build the SQL Query
        $sql = "INSERT INTO activity_logs 
                (user_id, action_type, entity_type, entity_id, action_details, old_value, new_value) 
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

        // 3. Execute the Query
        $result = mysqli_query($this->conn, $sql);

        // 4. Check Result
        if (!$result) {
            // Log the error (optional but recommended for debugging)
            // error_log("Activity Log Error: " . mysqli_error($this->conn));
            return false;
        }

        return true;
    }
}