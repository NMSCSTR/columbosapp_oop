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
?> 