<?php
class TransactionModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getAllTransactions()
    {
        $sql          = "SELECT * FROM transactions";
        $result       = mysqli_query($this->conn, $sql);
        $transactions = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $transactions[] = $row;
        }

        return $transactions;
    }
    
    public function calculatePremium($applicant_id)
    {
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);

        // 1. Get birthdate and face value
        $sql = "SELECT a.birthdate, f.face_value, p.fraternal_benefits_id 
                FROM applicants a 
                JOIN plans p ON a.user_id = p.user_id 
                JOIN fraternal_benefits f ON p.fraternal_benefits_id = f.id
                WHERE a.applicant_id = '$applicant_id'";
        
        $res = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($res);

        if (!$data || empty($data['birthdate'])) return 0;

        // Calculate Real Age
        $birthDate = new DateTime($data['birthdate']);
        $age = (new DateTime())->diff($birthDate)->y;

        $faceValue = (float)$data['face_value'];
        $benefitId = $data['fraternal_benefits_id'];

        $catSql = "SELECT id FROM benefit_rate_categories 
                WHERE ('$faceValue' >= min_face_value AND ('$faceValue' < max_face_value OR max_face_value IS NULL))
                ORDER BY (plan_id = '$benefitId') DESC LIMIT 1";
        
        $catRes = mysqli_query($this->conn, $catSql);
        $category = mysqli_fetch_assoc($catRes);

        if (!$category) return 0;

        $catId = $category['id'];

        // 3. Get the rate for the age
        $rateSql = "SELECT rate FROM benefit_rates 
                    WHERE category_id = '$catId' 
                    AND '$age' BETWEEN min_age AND max_age";
        
        $rateRes = mysqli_query($this->conn, $rateSql);
        $rateData = mysqli_fetch_assoc($rateRes);

        if ($rateData) {
            return ($faceValue / 1000) * (float)$rateData['rate'];
        }

        return 0;
    }

    public function getPlanDetailsByApplicantId($applicant_id)
    {
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);

        $sql = "SELECT `plan_id`, `applicant_id`, `user_id`, `fraternal_benefits_id`, `council_id`, `payment_mode`, `contribution_amount`, `currency` FROM `plans` WHERE `applicant_id` = '$applicant_id'";
        $result = mysqli_query($this->conn, $sql);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            return $row;
        }
        return null;
    }


    public function insertTransactions($applicant_id, $user_id, $plan_id, $payment_date, $amount_paid, $currency, $next_due_date, $payment_timing_status,$remarks)
    {
        $sql = "INSERT INTO transactions (applicant_id, user_id, plan_id, payment_date, amount_paid, currency, next_due_date, payment_timing_status,remarks)
            VALUES ('$applicant_id', '$user_id', '$plan_id', '$payment_date', '$amount_paid', '$currency', '$next_due_date', '$payment_timing_status','$remarks')";
        return mysqli_query($this->conn, $sql);
    }

    public function updateNotebookRemarks($transaction_id, $remarks)
    {
        $sql = "UPDATE transactions SET remarks = ? WHERE transaction_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $remarks, $transaction_id);
            $result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $result;
        }
        
        return false;
    }

    public function nextDueDateNotificationReminder()
    {
        $today = date('Y-m-d');
        $sql   = "SELECT t.*, a.contact_number, a.full_name
                FROM transactions t
                JOIN applicants a ON t.applicant_id = a.applicant_id
                WHERE t.next_due_date = '$today' AND t.status != 'Paid'";
        $result = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $number  = $row['contact_number'];
            $name    = $row['full_name'];
            $dueDate = $row['next_due_date'];

            $message = "Hi $name, your next payment is due today ($dueDate). Please make your payment on time. - KCFAPI";

            $this->sendSMS($number, $message);
        }
    }

    public function getPaymentTransactionsByApplicant($user_id)
    {
        $user_id = mysqli_real_escape_string($this->conn, $user_id);
        $sql     = "SELECT t.*, u.firstname, u.lastname, p.fraternal_benefits_id
                FROM transactions t
                JOIN users u ON t.user_id = u.id
                JOIN plans p ON t.plan_id = p.plan_id
                WHERE t.user_id = '$user_id'
                ORDER BY t.payment_date DESC";

        $result = mysqli_query($this->conn, $sql);

        $transactions = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $transactions[] = $row;
            }
        }

        return $transactions;
    }

    public function fetchApplicantsByKeyword($keyword)
    {
        $keyword = mysqli_real_escape_string($this->conn, $keyword);

        $sql = "SELECT * FROM applicants
            WHERE user_id LIKE '%$keyword%'
               OR firstname LIKE '%$keyword%'
               OR lastname LIKE '%$keyword%'
               OR middlename LIKE '%$keyword%'
               OR CONCAT(firstname, ' ', lastname) LIKE '%$keyword%'
               OR CONCAT(lastname, ', ', firstname) LIKE '%$keyword%'";

        $result = mysqli_query($this->conn, $sql);

        $data = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
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

        file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " | To: $number | Response: $response\n", FILE_APPEND);

        return $response;
    }

    public function updateTransaction($transaction_id, $applicant_id, $user_id, $plan_id, $payment_date, $amount_paid, $currency, $next_due_date, $status)
    {
        $sql = "UPDATE transactions
                SET applicant_id = '$applicant_id', user_id = '$user_id', plan_id = '$plan_id',
                    payment_date = '$payment_date', amount_paid = '$amount_paid', currency = '$currency',
                    next_due_date = '$next_due_date', status = '$status'
                WHERE transaction_id = '$transaction_id'";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteTransaction($transaction_id)
    {
        $sql = "DELETE FROM transactions WHERE transaction_id = '$transaction_id'";
        return mysqli_query($this->conn, $sql);
    }

    public function getTransactionsById($id)
    {
        $id     = mysqli_real_escape_string($this->conn, $id);
        $sql    = "SELECT * FROM transactions WHERE user_id = '$id'";
        $result = mysqli_query($this->conn, $sql);
        
        if (!$result) {
            die("Query failed: " . mysqli_error($this->conn));
        }
        
        $data = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    


    public function sendNotifIfInsuranceNearToEnd()
    {
        // Get all active applicants with their contact info and transaction details
        $sql = "SELECT
                a.applicant_id,
                a.firstname,
                a.lastname,
                c.mobile_number,
                t.next_due_date,
                t.plan_id,
                f.name as plan_name,    
                f.years_of_protection,
                DATEDIFF(t.next_due_date, CURDATE()) as days_remaining
            FROM applicants a
            JOIN contact_info c ON a.applicant_id = c.applicant_id
            JOIN transactions t ON a.applicant_id = t.applicant_id
            JOIN fraternal_benefits f ON t.plan_id = f.id
            WHERE a.application_status = 'Approved'
            AND t.status = 'Paid'
            AND c.mobile_number IS NOT NULL
            AND t.next_due_date IS NOT NULL
            AND DATEDIFF(t.next_due_date, CURDATE()) BETWEEN 1 AND 30
            ORDER BY t.next_due_date ASC";

        $result             = mysqli_query($this->conn, $sql);
        $notifications_sent = 0;

        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $days_remaining = $row['days_remaining'];
                $mobile_number  = $row['mobile_number'];
                $plan_name      = $row['plan_name'];
                $firstname      = $row['firstname'];
                $due_date       = date('F j, Y', strtotime($row['next_due_date']));

                // Customize message based on days remaining
                if ($days_remaining <= 7) {
                    $urgency = "URGENT";
                } elseif ($days_remaining <= 14) {
                    $urgency = "IMPORTANT";
                } else {
                    $urgency = "REMINDER";
                }

                $message = "[$urgency] Hi $firstname! Your $plan_name insurance plan will expire in $days_remaining days (on $due_date). Please renew to avoid coverage lapse. - KCFAPI";

                // Send SMS
                $sms_response = $this->sendSMS($mobile_number, $message);

                // Log the notification
                $log_sql = "INSERT INTO notification_logs
                        (applicant_id, mobile_number, message, days_remaining, sent_at, response)
                        VALUES
                        ('{$row['applicant_id']}', '$mobile_number', '" . mysqli_real_escape_string($this->conn, $message) . "',
                         '$days_remaining', NOW(), '" . mysqli_real_escape_string($this->conn, $sms_response) . "')";
                mysqli_query($this->conn, $log_sql);

                $notifications_sent++;
            }
        }

        return [
            'notifications_sent' => $notifications_sent,
            'message'            => "Sent $notifications_sent insurance expiration notifications",
        ];
    }

// Additional method to get insurance status for a specific applicant
    public function getInsuranceStatus($applicant_id)
    {
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);

        $sql = "SELECT
                a.firstname,
                a.lastname,
                c.mobile_number,
                t.next_due_date,
                f.name as plan_name,
                DATEDIFF(t.next_due_date, CURDATE()) as days_remaining,
                CASE
                    WHEN DATEDIFF(t.next_due_date, CURDATE()) <= 0 THEN 'EXPIRED'
                    WHEN DATEDIFF(t.next_due_date, CURDATE()) <= 7 THEN 'CRITICAL'
                    WHEN DATEDIFF(t.next_due_date, CURDATE()) <= 30 THEN 'NEAR_EXPIRATION'
                    ELSE 'ACTIVE'
                END as status
            FROM applicants a
            JOIN contact_info c ON a.applicant_id = c.applicant_id
            JOIN transactions t ON a.applicant_id = t.applicant_id
            JOIN fraternal_benefits f ON t.plan_id = f.id
            WHERE a.applicant_id = '$applicant_id'
            AND a.application_status = 'Approved'
            AND t.status = 'Paid'
            ORDER BY t.next_due_date DESC
            LIMIT 1";

        $result = mysqli_query($this->conn, $sql);

        if (mysqli_num_rows($result)) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

}
