<?php
class ApplicationModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getApplications()
    {
        
    }

    public function getAllApplicationById($id)
    {

    }

    private function sendSMS($number, $message)
    {
        $ch = curl_init();
        $parameters = array(
            'apikey' => 'key',
            'number' => $number,
            'message' => $message,
            'sendername' => 'naame'
        );
    
        curl_setopt($ch, CURLOPT_URL,'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Log or inspect the response
        file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " | To: $number | Response: $response\n", FILE_APPEND);
    
        return $response;
    }
    

    public function insertApplication()
    {
       
    }

    public function updateAApplication()
    {
    }

    public function deleteApplication($id)
    {

    }

}
