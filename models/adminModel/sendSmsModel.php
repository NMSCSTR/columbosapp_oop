<?php
class sendSmsModel
{
    private $conn;

    public function __construct($dbConnection) 
    {
        $this->conn = $dbConnection;
    }

    public function getAllSms()
    {
        // You can implement this later if needed
    }

    public function getPhoneNumber($applicant_id)
    {
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);

        $sql = "SELECT ci.mobile_number
                FROM `contact_info` ci
                INNER JOIN `applicants` a ON ci.applicant_id = a.applicant_id
                WHERE ci.applicant_id = '$applicant_id'
                AND a.application_status = 'Approved'";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['mobile_number'];
        }
        return null;
    }

    private function sendSMS($number, $message)
    {
        $ch         = curl_init();
        $parameters = [
            'apikey'     => '5bf90b2585f02b48d22e01d79503e591',
            'number'     => $number,
            'message'    => $message,
            'sendername' => 'KCFAPI',
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Log the SMS sending attempt
        file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " | To: $number | Response: $response\n", FILE_APPEND);

        return $response;
    }

    private function ensureSmsLogsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `sms_logs` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `applicant_id` INT NULL,
            `phone_number` VARCHAR(20) NULL,
            `subject` VARCHAR(255) NOT NULL,
            `content` TEXT NOT NULL,
            `template` VARCHAR(50) NULL,
            `due_date` DATE NULL,
            `status` VARCHAR(20) NOT NULL,
            `message_id` VARCHAR(255) NULL,
            `provider_response` TEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        mysqli_query($this->conn, $sql);
    }

    private function logSms($applicantId, $phoneNumber, $subject, $content, $template, $dueDate, $status, $messageId, $providerResponse)
    {
        $applicantIdEsc = is_null($applicantId) ? 'NULL' : intval($applicantId);
        $phoneNumberEsc = mysqli_real_escape_string($this->conn, (string)$phoneNumber);
        $subjectEsc = mysqli_real_escape_string($this->conn, (string)$subject);
        $contentEsc = mysqli_real_escape_string($this->conn, (string)$content);
        $templateEsc = is_null($template) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, (string)$template) . "'";
        $dueDateEsc = (empty($dueDate) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, (string)$dueDate) . "'");
        $statusEsc = mysqli_real_escape_string($this->conn, (string)$status);
        $messageIdEsc = is_null($messageId) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, (string)$messageId) . "'";
        $providerResponseEsc = is_null($providerResponse) ? 'NULL' : "'" . mysqli_real_escape_string($this->conn, (string)$providerResponse) . "'";

        $insert = "INSERT INTO `sms_logs` (`applicant_id`, `phone_number`, `subject`, `content`, `template`, `due_date`, `status`, `message_id`, `provider_response`)
                   VALUES ($applicantIdEsc, '$phoneNumberEsc', '$subjectEsc', '$contentEsc', $templateEsc, $dueDateEsc, '$statusEsc', $messageIdEsc, $providerResponseEsc)";
        mysqli_query($this->conn, $insert);
    }

    public function sendSmsToApplicant($applicant_id, $subject, $content, $template = null, $dueDate = null)
    {
        $this->ensureSmsLogsTable();

        // Get the phone number for the approved applicant
        $phoneNumber = $this->getPhoneNumber($applicant_id);

        if ($phoneNumber === null) {
            // Log error if applicant not found or not approved
            file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " | Error: Applicant $applicant_id not found or not approved\n", FILE_APPEND);
            $this->logSms($applicant_id, null, $subject, $content, $template, $dueDate, 'failed', null, 'Applicant not found or not approved');
            return [
                'success' => false,
                'message' => 'Applicant not found or application not approved',
            ];
        }

        // Clean and format the phone number (remove spaces, dashes, etc.)
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Ensure the number has country code format (assuming Philippines: +63)
        if (strlen($phoneNumber) === 10 && substr($phoneNumber, 0, 1) === '9') {
            $phoneNumber = '63' . $phoneNumber;
        } elseif (strlen($phoneNumber) === 11 && substr($phoneNumber, 0, 2) === '09') {
            $phoneNumber = '63' . substr($phoneNumber, 1);
        }

        // Combine subject and content for the SMS message
        $message = $subject . "\n\n" . $content;

        // Send the SMS
        $result = $this->sendSMS($phoneNumber, $message);

        // Parse the response (Semaphore API returns JSON)
        $responseData = json_decode($result, true);

        if (isset($responseData[0]['message_id'])) {
            $messageId = $responseData[0]['message_id'];
            $this->logSms($applicant_id, $phoneNumber, $subject, $content, $template, $dueDate, 'sent', $messageId, $result);
            return [
                'success'      => true,
                'message'      => 'SMS sent successfully',
                'message_id'   => $messageId,
                'phone_number' => $phoneNumber,
            ];
        } else {
            $this->logSms($applicant_id, $phoneNumber, $subject, $content, $template, $dueDate, 'failed', null, $result);
            return [
                'success'      => false,
                'message'      => 'Failed to send SMS',
                'error'        => $result,
                'phone_number' => $phoneNumber,
            ];
        }
    }

    // Optional: Method para send sa multiple applicants
    public function sendBulkSms($applicant_ids, $subject, $content)
    {
        $results = [];
        foreach ($applicant_ids as $applicant_id) {
            $results[$applicant_id] = $this->sendSmsToApplicant($applicant_id, $subject, $content);
        }
        return $results;
    }

    public function updateSms($id, $subject, $content)
    {
        $id      = mysqli_real_escape_string($this->conn, $id);
        $subject = mysqli_real_escape_string($this->conn, $subject);
        $content = mysqli_real_escape_string($this->conn, $content);

    }

}
