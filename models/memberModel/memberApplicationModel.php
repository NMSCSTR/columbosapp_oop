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
            'sendername' => 'name'
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

    // This method calculates the future value based on the payment mode
    public function calculatePlanBaseOnPaymentMode($P, $r, $t, $paymentMode)
    {
        switch ($paymentMode) {
            case 'monthly':
                $n = 12;  
                break;
            case 'quarterly':
                $n = 4;  
                break;
            case 'semiannual':
                $n = 2;  
                break;
            case 'annual':
                $n = 1;  
                break;
            default:
                return "Invalid Payment Mode"; 
        }

        // Future Value formula: FV = P * [(1 + r/n)^(nt) - 1] / (r/n)
        $FV = $P * ((pow((1 + $r/$n), $n * $t) - 1) / ($r/$n));

        return round($FV, 2);
    }

    public function calculateOfEachMemberAllocation($P, $allocationPercent)
    {
        
        return $P * ($allocationPercent / 100); 
    }

    
    public function calculateAllTotalMemberAllocation($members, $P, $allocationPercent)
    {
        $totalAllocation = 0;
        foreach ($members as $member) {
            $totalAllocation += $this->calculateOfEachMemberAllocation($P, $allocationPercent);
        }
        return $totalAllocation;
    }

    public function updateAApplication()
    {
    }

    public function deleteApplication($id)
    {

    }

}
