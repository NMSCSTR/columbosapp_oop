<?php
class MemberApplicationModel
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
        $ch         = curl_init();
        $parameters = [
            'apikey'     => 'key',
            'number'     => $number,
            'message'    => $message,
            'sendername' => 'name',
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

    public function insertApplicant($user_id, $frateral_counselor_id, $firstname, $lastname, $middlename, $birthdate, $age, $gender, $marital_status, $tin_sss, $nationality)
    {
        $user_id               = mysqli_real_escape_string($this->conn, $user_id);
        $frateral_counselor_id = mysqli_real_escape_string($this->conn, $frateral_counselor_id);
        $firstname             = mysqli_real_escape_string($this->conn, $firstname);
        $lastname              = mysqli_real_escape_string($this->conn, $lastname);
        $middlename            = mysqli_real_escape_string($this->conn, $middlename);
        $birthdate             = mysqli_real_escape_string($this->conn, $birthdate);
        $age                   = mysqli_real_escape_string($this->conn, $age);
        $gender                = mysqli_real_escape_string($this->conn, $gender);
        $marital_status        = mysqli_real_escape_string($this->conn, $marital_status);
        $tin_sss               = mysqli_real_escape_string($this->conn, $tin_sss);
        $nationality           = mysqli_real_escape_string($this->conn, $nationality);
        $status                = mysqli_real_escape_string($this->conn, "Active");
        $application_Status    = mysqli_real_escape_string($this->conn, "Pending");

        $sql = "
            INSERT INTO applicants
            (user_id, frateral_counselor_id, firstname, lastname, middlename, age, birthdate, birthplace, gender, marital_status, tin_sss, nationality, status, application_status)
            VALUES
            ('$user_id', '$frateral_counselor_id', '$firstname', '$lastname', '$middlename', '$birthdate', '$age', '$gender', '$marital_status', '$tin_sss', '$nationality', '$status', '$application_Status')
        ";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function insertApplicantContactDetails($applicant_id, $street, $barangay, $city_province, $mobile_number, $email_address)
    {
        $applicant_id  = mysqli_real_escape_string($this->conn, $applicant_id);
        $street        = mysqli_real_escape_string($this->conn, $street);
        $barangay      = mysqli_real_escape_string($this->conn, $barangay);
        $city_province = mysqli_real_escape_string($this->conn, $city_province);
        $mobile_number = mysqli_real_escape_string($this->conn, $mobile_number);
        $email_address = mysqli_real_escape_string($this->conn, $email_address);

        $sql = "
            INSERT INTO contact_info
            (applicant_id, street, barangay, city_province, mobile_number, email_address)
            VALUES
            ('$applicant_id', '$street', '$barangay', '$city_province', '$mobile_number', '$email_address')
        ";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function insertEmploymentDetails($applicant_id, $user_id, $occupation, $employment_status, $duties, $employer, $work, $nature_business, $employer_mobile_number, $employer_email_address, $monthly_income)
    {
        $applicant_id           = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id                = mysqli_real_escape_string($this->conn, $user_id);
        $occupation             = mysqli_real_escape_string($this->conn, $occupation);
        $employment_status      = mysqli_real_escape_string($this->conn, $employment_status);
        $duties                 = mysqli_real_escape_string($this->conn, $duties);
        $employer               = mysqli_real_escape_string($this->conn, $employer);
        $work                   = mysqli_real_escape_string($this->conn, $work);
        $nature_business        = mysqli_real_escape_string($this->conn, $nature_business);
        $employer_mobile_number = mysqli_real_escape_string($this->conn, $employer_mobile_number);
        $employer_email_address = mysqli_real_escape_string($this->conn, $employer_email_address);
        $monthly_income         = mysqli_real_escape_string($this->conn, $monthly_income);

        $sql = "INSERT INTO employment (applicant_id, user_id, occupation, employment_status, duties, employer, work, nature_business, employer_mobile_number, employer_email_address, monthly_income) VALUES ('$applicant_id', '$user_id', '$occupation', '$employment_status', '$duties', '$employer', '$work', '$nature_business', '$employer_mobile_number', '$employer_email_address', '$monthly_income')";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function insertPlanInformation($applicant_id, $user_id, $fraternal_benefits_id, $council_id, $payment_mode, $contribution_amount, $currency)
    {
        $applicant_id          = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id               = mysqli_real_escape_string($this->conn, $user_id);
        $fraternal_benefits_id = mysqli_real_escape_string($this->conn, $fraternal_benefits_id);
        $council_id            = mysqli_real_escape_string($this->conn, $council_id);
        $payment_mode          = mysqli_real_escape_string($this->conn, $payment_mode);
        $contribution_amount   = mysqli_real_escape_string($this->conn, $contribution_amount);
        $currency              = mysqli_real_escape_string($this->conn, $currency);

        $sql = "INSERT INTO plans (
                    applicant_id, user_id, fraternal_benefits_id, council_id, payment_mode, contribution_amount, currency
                ) VALUES (
                    '$applicant_id', '$user_id', '$fraternal_benefits_id', '$council_id', '$payment_mode', '$contribution_amount', '$currency'
                )";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function addBeneficiaries()
    {

    }

    public function updateAApplication()
    {
    }

    public function deleteApplication($id)
    {

    }

}
