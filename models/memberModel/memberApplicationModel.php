<?php
class MemberApplicationModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    private function escape($value)
    {
        return mysqli_real_escape_string($this->conn, $value);
    }

    public function insertApplicant($user_id, $fraternal_counselor_id, $firstname, $lastname, $middlename, $age, $birthdate, $birthplace, $gender, $marital_status, $tin_sss, $nationality)
    {
        $user_id                = mysqli_real_escape_string($this->conn, $user_id);
        $fraternal_counselor_id = mysqli_real_escape_string($this->conn, $fraternal_counselor_id);
        $firstname              = mysqli_real_escape_string($this->conn, $firstname);
        $lastname               = mysqli_real_escape_string($this->conn, $lastname);
        $middlename             = mysqli_real_escape_string($this->conn, $middlename);
        $birthdate              = mysqli_real_escape_string($this->conn, $birthdate);
        $birthplace             = mysqli_real_escape_string($this->conn, $birthplace);
        $age                    = mysqli_real_escape_string($this->conn, $age);
        $gender                 = mysqli_real_escape_string($this->conn, $gender);
        $marital_status         = mysqli_real_escape_string($this->conn, $marital_status);
        $tin_sss                = mysqli_real_escape_string($this->conn, $tin_sss);
        $nationality            = mysqli_real_escape_string($this->conn, $nationality);
        $status                 = mysqli_real_escape_string($this->conn, "Active");
        $application_Status     = mysqli_real_escape_string($this->conn, "Pending");

        $sql = "
            INSERT INTO applicants
            (user_id, fraternal_counselor_id,lastname, firstname, middlename, age, birthdate, birthplace, gender, marital_status, tin_sss, nationality, status, application_status)
            VALUES
            ('$user_id', '$fraternal_counselor_id', '$lastname', '$firstname','$middlename', '$age', '$birthdate', '$birthplace', '$gender', '$marital_status', '$tin_sss', '$nationality', '$status', '$application_Status')
        ";

        if (mysqli_query($this->conn, $sql)) {
            return $this->conn->insert_id;
        } else {
            throw new Exception("Failed to insert applicant: " . $this->conn->error);
        }
    }

    public function insertApplicantContactDetails($applicant_id, $user_id, $street, $barangay, $city_province, $mobile_number, $email_address)
    {
        $applicant_id  = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id       = mysqli_real_escape_string($this->conn, $user_id);
        $street        = mysqli_real_escape_string($this->conn, $street);
        $barangay      = mysqli_real_escape_string($this->conn, $barangay);
        $city_province = mysqli_real_escape_string($this->conn, $city_province);
        $mobile_number = mysqli_real_escape_string($this->conn, $mobile_number);
        $email_address = mysqli_real_escape_string($this->conn, $email_address);

        $sql = "
            INSERT INTO contact_info
            (applicant_id, user_id, street, barangay, city_province, mobile_number, email_address)
            VALUES
            ('$applicant_id', '$user_id', '$street', '$barangay', '$city_province', '$mobile_number', '$email_address')
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

    public function addBeneficiaries($applicant_id, $user_id, $benefit_types, $benefit_names, $benefit_birthdates, $benefit_relationships)
    {
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id      = mysqli_real_escape_string($this->conn, $user_id);

        $count = count($benefit_names);

        for ($i = 0; $i < $count; $i++) {
            $benefit_type         = mysqli_real_escape_string($this->conn, $benefit_types[$i] ?? '');
            $benefit_name         = mysqli_real_escape_string($this->conn, $benefit_names[$i] ?? '');
            $benefit_birthdate    = mysqli_real_escape_string($this->conn, $benefit_birthdates[$i] ?? '');
            $benefit_relationship = mysqli_real_escape_string($this->conn, $benefit_relationships[$i] ?? '');

            $sql = "INSERT INTO beneficiaries (
                        applicant_id,
                        user_id,
                        benefit_type,
                        benefit_name,
                        benefit_birthdate,
                        benefit_relationship
                    ) VALUES (
                        '$applicant_id',
                        '$user_id',
                        '$benefit_type',
                        '$benefit_name',
                        '$benefit_birthdate',
                        '$benefit_relationship'

                    )";

            if (! mysqli_query($this->conn, $sql)) {
                return "Error inserting beneficiary $i: " . mysqli_error($this->conn);
            }
        }

        return true;
    }

    public function insertFamilyBackground($applicant_id, $user_id, $father_lastname, $father_firstname, $father_mi, $mother_lastname, $mother_firstname, $mother_mi, $siblings_living, $siblings_deceased, $children_living, $children_deceased)
    {

        $applicant_id      = $this->escape($applicant_id ?? '');
        $user_id           = $this->escape($user_id ?? '');
        $father_lastname   = $this->escape($father_lastname ?? '');
        $father_firstname  = $this->escape($father_firstname ?? '');
        $father_mi         = $this->escape($father_mi ?? '');
        $mother_lastname   = $this->escape($mother_lastname ?? '');
        $mother_firstname  = $this->escape($mother_firstname ?? '');
        $mother_mi         = $this->escape($mother_mi ?? '');
        $siblings_living   = $this->escape($siblings_living ?? 0);
        $siblings_deceased = $this->escape($siblings_deceased ?? 0);
        $children_living   = $this->escape($children_living ?? 0);
        $children_deceased = $this->escape($children_deceased ?? 0);

        $sql = "INSERT INTO family_background (
                applicant_id, user_id, father_lastname, father_firstname, father_mi,
                mother_lastname, mother_firstname, mother_mi,
                siblings_living, siblings_deceased, children_living, children_deceased
            ) VALUES (
                '$applicant_id','$user_id', '$father_lastname', '$father_firstname', '$father_mi',
                '$mother_lastname', '$mother_firstname', '$mother_mi',
                '$siblings_living', '$siblings_deceased', '$children_living', '$children_deceased'
            )";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function insertMedicalHistory($applicant_id, $user_id, $past_illness, $current_medication)
    {

        $applicant_id       = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id            = mysqli_real_escape_string($this->conn, $user_id);
        $past_illness       = mysqli_real_escape_string($this->conn, $past_illness);
        $current_medication = mysqli_real_escape_string($this->conn, $current_medication);

        if (empty($past_illness) || empty($current_medication)) {
            return "Error: Both Past Illnesses and Current Medications must be provided.";
        }

        $sql = "INSERT INTO medical_history (
                applicant_id, user_id, past_illness, current_medication
            ) VALUES (
                '$applicant_id', '$user_id', '$past_illness', '$current_medication'
            )";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function insertFamilyHealth(
        $applicant_id,
        $user_id,
        $father_living_age,
        $father_health,
        $mother_living_age,
        $mother_health,
        $siblings_living_age,
        $siblings_health,
        $children_living_age,
        $children_health,
        $father_death_age,
        $father_cause,
        $mother_death_age,
        $mother_cause,
        $siblings_death_age,
        $siblings_cause,
        $children_death_age,
        $children_cause
    ) {

        $applicant_id        = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id             = mysqli_real_escape_string($this->conn, $user_id);
        $father_living_age   = mysqli_real_escape_string($this->conn, $father_living_age);
        $father_health       = mysqli_real_escape_string($this->conn, $father_health);
        $mother_living_age   = mysqli_real_escape_string($this->conn, $mother_living_age);
        $mother_health       = mysqli_real_escape_string($this->conn, $mother_health);
        $siblings_living_age = mysqli_real_escape_string($this->conn, $siblings_living_age);
        $siblings_health     = mysqli_real_escape_string($this->conn, $siblings_health);
        $children_living_age = mysqli_real_escape_string($this->conn, $children_living_age);
        $children_health     = mysqli_real_escape_string($this->conn, $children_health);
        $father_death_age    = mysqli_real_escape_string($this->conn, $father_death_age);
        $father_cause        = mysqli_real_escape_string($this->conn, $father_cause);
        $mother_death_age    = mysqli_real_escape_string($this->conn, $mother_death_age);
        $mother_cause        = mysqli_real_escape_string($this->conn, $mother_cause);
        $siblings_death_age  = mysqli_real_escape_string($this->conn, $siblings_death_age);
        $siblings_cause      = mysqli_real_escape_string($this->conn, $siblings_cause);
        $children_death_age  = mysqli_real_escape_string($this->conn, $children_death_age);
        $children_cause      = mysqli_real_escape_string($this->conn, $children_cause);

        $sql = "INSERT INTO family_health (
                    applicant_id, user_id, father_living_age, father_health, mother_living_age, mother_health,
                    siblings_living_age, siblings_health, children_living_age, children_health,
                    father_death_age, father_cause, mother_death_age, mother_cause,
                    siblings_death_age, siblings_cause, children_death_age, children_cause
                ) VALUES (
                    '$applicant_id', '$user_id', '$father_living_age', '$father_health', '$mother_living_age', '$mother_health',
                    '$siblings_living_age', '$siblings_health', '$children_living_age', '$children_health',
                    '$father_death_age', '$father_cause', '$mother_death_age', '$mother_cause',
                    '$siblings_death_age', '$siblings_cause', '$children_death_age', '$children_cause'
                )";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function insertPhysicianDetails($applicant_id, $user_id, $physician_name, $contact_number, $physician_address)
    {

        $applicant_id      = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id           = mysqli_real_escape_string($this->conn, $user_id);
        $physician_name    = mysqli_real_escape_string($this->conn, $physician_name);
        $contact_number    = mysqli_real_escape_string($this->conn, $contact_number);
        $physician_address = mysqli_real_escape_string($this->conn, $physician_address);

        $sql = "INSERT INTO `physician` (`applicant_id`, `user_id`, `physician_name`, `contact_number`, `physician_address`)
            VALUES ('$applicant_id', '$user_id', '$physician_name', '$contact_number', '$physician_address')";

        if (mysqli_query($this->conn, $sql)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function insertHealthQuestions($applicant_id, $user_id, $responses)
    {
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id      = mysqli_real_escape_string($this->conn, $user_id);

        foreach ($responses as $question_code => $response_details) {
            $response = mysqli_real_escape_string($this->conn, $response_details['response']);
            $details  = mysqli_real_escape_string($this->conn, $response_details['details']);

            $sql = "INSERT INTO health_questions (
                    applicant_id, user_id, question_code, response, yes_details
                ) VALUES (
                    '$applicant_id', '$user_id', '$question_code', '$response', '$details'
                )";

            if (! mysqli_query($this->conn, $sql)) {
                return "Error: " . mysqli_error($this->conn);
            }
        }

        return true;
    }

    public function insertPersonalAndMembershipDetails(
        $applicant_id,
        $user_id,
        $height,
        $weight,
        $signature_file,
        $pregnant_question,
        $council_id,
        $first_degree_date,
        $present_degree,
        $good_standing
    ) {

        $applicant_id      = mysqli_real_escape_string($this->conn, $applicant_id);
        $user_id           = mysqli_real_escape_string($this->conn, $user_id);
        $height            = mysqli_real_escape_string($this->conn, $height);
        $weight            = mysqli_real_escape_string($this->conn, $weight);
        $pregnant_question = mysqli_real_escape_string($this->conn, $pregnant_question);
        $council_id        = mysqli_real_escape_string($this->conn, $council_id);
        $first_degree_date = mysqli_real_escape_string($this->conn, $first_degree_date);
        $present_degree    = mysqli_real_escape_string($this->conn, $present_degree);
        $good_standing     = mysqli_real_escape_string($this->conn, $good_standing);

        $sql1 = "INSERT INTO personal_details (
                    applicant_id, user_id, height, weight, signature_file, pregnant_question
                ) VALUES (
                    '$applicant_id', '$user_id', '$height', '$weight', '$signature_file', '$pregnant_question'
                )";

        $sql2 = "INSERT INTO membership (
                    applicant_id, user_id, council_id, first_degree_date, present_degree, good_standing
                ) VALUES (
                    '$applicant_id', '$user_id', '$council_id', '$first_degree_date', '$present_degree', '$good_standing'
                )";

        if (mysqli_query($this->conn, $sql1) && mysqli_query($this->conn, $sql2)) {
            return true;
        } else {
            return "Error: " . mysqli_error($this->conn);
        }
    }

    public function deleteApplicant($applicant_id)
    {
        $applicant_id = mysqli_real_escape_string($this->conn, $applicant_id);

        // Start a transaction to ensure all related data is deleted successfully
        mysqli_begin_transaction($this->conn);

        try {
            // Delete data from all related tables
            $sqlContactInfo      = "DELETE FROM contact_info WHERE applicant_id = '$applicant_id'";
            $sqlEmployment       = "DELETE FROM employment WHERE applicant_id = '$applicant_id'";
            $sqlPlans            = "DELETE FROM plans WHERE applicant_id = '$applicant_id'";
            $sqlBeneficiaries    = "DELETE FROM beneficiaries WHERE applicant_id = '$applicant_id'";
            $sqlFamilyBackground = "DELETE FROM family_background WHERE applicant_id = '$applicant_id'";
            $sqlMedicalHistory   = "DELETE FROM medical_history WHERE applicant_id = '$applicant_id'";
            $sqlFamilyHealth     = "DELETE FROM family_health WHERE applicant_id = '$applicant_id'";
            $sqlPhysician        = "DELETE FROM physician WHERE applicant_id = '$applicant_id'";

            // Execute the delete queries
            mysqli_query($this->conn, $sqlContactInfo);
            mysqli_query($this->conn, $sqlEmployment);
            mysqli_query($this->conn, $sqlPlans);
            mysqli_query($this->conn, $sqlBeneficiaries);
            mysqli_query($this->conn, $sqlFamilyBackground);
            mysqli_query($this->conn, $sqlMedicalHistory);
            mysqli_query($this->conn, $sqlFamilyHealth);
            mysqli_query($this->conn, $sqlPhysician);

            // Finally, delete the applicant record from the main applicants table
            $sqlApplicant = "DELETE FROM applicants WHERE applicant_id = '$applicant_id'";

            if (mysqli_query($this->conn, $sqlApplicant)) {
                // Commit the transaction if all deletes were successful
                mysqli_commit($this->conn);
                return true;
            } else {
                // Rollback the transaction if an error occurs
                mysqli_rollback($this->conn);
                return "Error deleting applicant: " . mysqli_error($this->conn);
            }
        } catch (Exception $e) {
            // Rollback the transaction if any exception occurs
            mysqli_rollback($this->conn);
            return "Error: " . $e->getMessage();
        }
    }

}
