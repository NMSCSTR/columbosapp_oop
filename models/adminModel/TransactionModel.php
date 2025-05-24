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
        $sql = "SELECT * FROM transactions";
        $result = mysqli_query($this->conn, $sql);
        $transactions = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $transactions[] = $row;
        }

        return $transactions;
    }

    public function insertTransactions($applicant_id, $user_id, $plan_id, $payment_date, $amount_paid, $currency, $next_due_date, $status)
    {
        $sql = "INSERT INTO transactions (applicant_id, user_id, plan_id, payment_date, amount_paid, currency, next_due_date, status)
                VALUES ('$applicant_id', '$user_id', '$plan_id', '$payment_date', '$amount_paid', '$currency', '$next_due_date', '$status')";
        return mysqli_query($this->conn, $sql);
    }

    public function nextDueDateNotificationReminder()
    {
        $today = date('Y-m-d');
        $sql = "SELECT t.*, a.contact_number, a.full_name
                FROM transactions t
                JOIN applicants a ON t.applicant_id = a.applicant_id
                WHERE t.next_due_date = '$today' AND t.status != 'Paid'";
        $result = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $number = $row['contact_number'];
            $name = $row['full_name'];
            $dueDate = $row['next_due_date'];

            $message = "Hi $name, your subscription is due today ($dueDate). Please make your payment to continue the service. - SalnPlatfrm";

            $this->sendSMS($number, $message);
        }
    }

    private function sendSMS($number, $message)
    {
        $ch = curl_init();
        $parameters = array(
            'apikey' => '188409cd13edfb2bc206f4183a7624bb',
            'number' => $number,
            'message' => $message,
            'sendername' => 'SalnPlatfrm'
        );

        curl_setopt($ch, CURLOPT_URL,'https://semaphore.co/api/v4/messages');
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
}