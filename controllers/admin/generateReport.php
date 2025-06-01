<?php
require_once '../../middleware/auth.php';
authorize(['admin']);
include '../../includes/db.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/userModel.php';
include '../../models/memberModel/memberApplicationModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $report_type = $_POST['report_type'];

    // Initialize models
    $councilModel = new CouncilModel($conn);
    $userModel = new UserModel($conn);
    $applicationModel = new MemberApplicationModel($conn);
    $fraternalBenefitsModel = new fraternalBenefitsModel($conn);

    try {
        $response = [];

        switch ($report_type) {
            case 'applications':
                // Get applications within date range
                $sql = "SELECT 
                    a.*,
                    CONCAT(a.firstname, ' ', a.lastname) AS applicant_name,
                    fb.type AS plan_type,
                    fb.name AS plan_name,
                    p.council_id
                FROM applicants a
                LEFT JOIN plans p ON a.applicant_id = p.applicant_id
                LEFT JOIN fraternal_benefits fb ON p.fraternal_benefits_id = fb.id
                WHERE DATE(a.created_at) BETWEEN ? AND ?
                ORDER BY a.created_at DESC";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                $applications = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $applications[] = $row;
                }

                $response = [
                    'success' => true,
                    'data' => $applications,
                    'type' => 'applications'
                ];
                break;

            case 'councils':
                // Get council performance within date range
                $sql = "SELECT 
                    c.*,
                    COUNT(a.applicant_id) as total_applications,
                    SUM(CASE WHEN a.application_status = 'Approved' THEN 1 ELSE 0 END) as approved_applications
                FROM council c
                LEFT JOIN plans p ON c.council_id = p.council_id
                LEFT JOIN applicants a ON p.applicant_id = a.applicant_id
                WHERE DATE(a.created_at) BETWEEN ? AND ?
                GROUP BY c.council_id";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                $councils = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $row['success_rate'] = $row['total_applications'] > 0 
                        ? round(($row['approved_applications'] / $row['total_applications']) * 100) 
                        : 0;
                    $councils[] = $row;
                }

                $response = [
                    'success' => true,
                    'data' => $councils,
                    'type' => 'councils'
                ];
                break;

            case 'plans':
                // Get plan distribution within date range
                $sql = "SELECT 
                    fb.type,
                    fb.name,
                    COUNT(a.applicant_id) as total_applications,
                    SUM(CASE WHEN a.application_status = 'Approved' THEN 1 ELSE 0 END) as approved_applications
                FROM fraternal_benefits fb
                LEFT JOIN plans p ON fb.id = p.fraternal_benefits_id
                LEFT JOIN applicants a ON p.applicant_id = a.applicant_id
                WHERE DATE(a.created_at) BETWEEN ? AND ?
                GROUP BY fb.id";

                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                $plans = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $row['success_rate'] = $row['total_applications'] > 0 
                        ? round(($row['approved_applications'] / $row['total_applications']) * 100) 
                        : 0;
                    $plans[] = $row;
                }

                $response = [
                    'success' => true,
                    'data' => $plans,
                    'type' => 'plans'
                ];
                break;

            default:
                throw new Exception("Invalid report type");
        }

        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
} else {
    header('Location: ../../views/admin/reports.php');
    exit();
}
?> 