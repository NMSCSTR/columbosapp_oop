<?php
class setQoutaModel
{

    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function setQouta($user_id, $quota, $current_amount, $duration)
    {
        $user_id        = mysqli_real_escape_string($this->conn, $user_id);
        $quota          = mysqli_real_escape_string($this->conn, $quota);
        $current_amount = mysqli_real_escape_string($this->conn, $current_amount);
        $duration       = mysqli_real_escape_string($this->conn, $duration);

        // Determine initial status
        $status = 'on-progress';
        if ($current_amount >= $quota) {
            $status = 'completed';
        }

        $query  = "INSERT INTO qouta(user_id, qouta, current_amount, duration, status) VALUES('$user_id', '$quota', '$current_amount', '$duration', '$status')";
        $result = mysqli_query($this->conn, $query);

        return $result ? true : false;
    }

    public function hasActiveQuota($user_id)
    {
        $user_id = mysqli_real_escape_string($this->conn, $user_id);
        $query   = "SELECT * FROM qouta WHERE user_id = '$user_id' AND status = 'on-progress' AND duration >= CURDATE() ORDER BY date_created DESC LIMIT 1";
        $result  = mysqli_query($this->conn, $query);

        return mysqli_fetch_assoc($result);
    }

    public function updateQuotaStatus()
    {
        // Update expired quotas
        $query = "UPDATE qouta SET status = 'expired' WHERE duration < CURDATE() AND status = 'on-progress'";
        mysqli_query($this->conn, $query);

        // Update completed quotas
        $query = "UPDATE qouta SET status = 'completed' WHERE current_amount >= qouta AND status = 'on-progress'";
        mysqli_query($this->conn, $query);
    }

    public function checkExistingQuota($user_id)
    {
        $user_id = mysqli_real_escape_string($this->conn, $user_id);
        $query   = "SELECT * FROM qouta WHERE user_id = '$user_id' ORDER BY date_created DESC LIMIT 1";
        $result  = mysqli_query($this->conn, $query);

        return mysqli_fetch_assoc($result);
    }

    public function fetchQouta($qouta_id)
    {
        $qouta_id = mysqli_real_escape_string($this->conn, $qouta_id);
        $query    = "SELECT * FROM qouta WHERE id = '$qouta_id'";
        $result   = mysqli_query($this->conn, $query);

        return mysqli_fetch_assoc($result);
    }
    
    public function fetchApplicantByUnitManager($user_id)
    {
        $user_id = mysqli_real_escape_string($this->conn, $user_id);

        $query  = "SELECT * FROM applicants WHERE unit_manager = '$user_id'";
        $result = mysqli_query($this->conn, $query);

        return mysqli_fetch_assoc($result);
    }

    public function fetchTotalAllocationsInApplicantsByUnitManager($user_id)
    {
        $user_id = mysqli_real_escape_string($this->conn, $user_id);

        $query = "SELECT
                a.fraternal_counselor_id,
                COUNT(a.applicant_id) as total_applicants,
                COALESCE(SUM(fb.face_value), 0) as total_face_value
              FROM applicants a
              LEFT JOIN plans p ON a.applicant_id = p.applicant_id
              LEFT JOIN fraternal_benefits fb ON p.fraternal_benefits_id = fb.id
              WHERE a.fraternal_counselor_id = '$user_id'
              GROUP BY a.fraternal_counselor_id";

        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function calculateAllApplicantsFaceValueByFraternalCounselor($user_id)
    {
        $user_id = mysqli_real_escape_string($this->conn, $user_id);

        $query = "SELECT
                COALESCE(SUM(fb.face_value), 0) as total_face_value
              FROM applicants a
              LEFT JOIN plans p ON a.applicant_id = p.applicant_id
              LEFT JOIN fraternal_benefits fb ON p.fraternal_benefits_id = fb.id
              WHERE a.fraternal_counselor_id = '$user_id'";

        $result = mysqli_query($this->conn, $query);
        $row    = mysqli_fetch_assoc($result);
        return $row['total_face_value'];
    }

    public function getAllFraternalCounselorWithQuota()
    {
        // First update quota statuses
        $this->updateQuotaStatus();

        $query = "SELECT
                    u.id,
                    u.firstname,
                    u.lastname,
                    u.email,
                    u.phone_number,
                    u.status,
                    q.id as quota_id,
                    q.qouta,
                    q.current_amount,
                    q.duration,
                    q.status as quota_status,
                    q.date_created,
                    CASE
                        WHEN q.qouta > 0 THEN ROUND((q.current_amount / q.qouta) * 100, 2)
                        ELSE 0
                    END as progress_percentage
                  FROM users u
                  LEFT JOIN qouta q ON u.id = q.user_id
                  WHERE u.role = 'fraternal-counselor'
                  ORDER BY u.firstname, u.lastname";

        $result = mysqli_query($this->conn, $query);
        $data   = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

}
