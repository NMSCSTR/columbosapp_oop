<?php 
class sendSmsModel {

    private $conn;

    public function __contruct($dbConnection){
        $this->conn = $dbConnection;
    }

    public function getAllSms(){
        
    }


    public function getPhoneNumber($applicant_id){
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);

        $sql = "SELECT * FROM `contact_info` WHERE applicant_id = '$applicant_id' WHERE ";
        $result = mysqli_query($this->conn, $sql);

        if($result && mysqli_num_rows($result) > 0){
            $applicant = [];
            while($row = mysql_fetch_assoc($result)){
                $applicant[] = $row;
            }
            return $applicant;
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

        // Log or inspect the response
        file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " | To: $number | Response: $response\n", FILE_APPEND);

        return $response;
    }

    public function sendSmsToApplicant($applicant_id, $phone_number){
    }

}
?>