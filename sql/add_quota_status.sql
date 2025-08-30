-- Add status field to qouta table
ALTER TABLE `qouta` ADD COLUMN `status` ENUM('on-progress', 'completed', 'expired') NOT NULL DEFAULT 'on-progress' AFTER `duration`;

-- Update existing records to have appropriate status based on duration and current_amount
UPDATE `qouta` 
SET `status` = CASE 
    WHEN `duration` < CURDATE() THEN 'expired'
    WHEN `current_amount` >= `qouta` THEN 'completed'
    ELSE 'on-progress'
END;

-- Add index for better performance
CREATE INDEX `idx_quota_status` ON `qouta` (`status`);
CREATE INDEX `idx_quota_user_status` ON `qouta` (`user_id`, `status`); 