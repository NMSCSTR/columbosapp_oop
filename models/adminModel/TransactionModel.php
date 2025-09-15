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

    public function insertTransactions($applicant_id, $user_id, $plan_id, $payment_date, $amount_paid, $currency, $next_due_date, $payment_timing_status)
    {
        $sql = "INSERT INTO transactions (applicant_id, user_id, plan_id, payment_date, amount_paid, currency, next_due_date, payment_timing_status)
            VALUES ('$applicant_id', '$user_id', '$plan_id', '$payment_date', '$amount_paid', '$currency', '$next_due_date', '$payment_timing_status')";
        return mysqli_query($this->conn, $sql);
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
        $sql    = "SELECT * FROM transactions WHERE applicant_id = '$id'";
        $result = mysqli_query($this->conn, $sql);
        $data   = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function sendNotifIfInsuranceNearToEnd($id){
        $id = mysqli_real_escape_string($this->conn, $id);

        $sql = "SELECT * FROM applicants WHERE applicant_id ='$id'";
        $result=mysqli_query($this->conn,$sql);
        $data = [];
        if(mysqli_num_rows($result)){
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }
        return $data;
    }

    
}
