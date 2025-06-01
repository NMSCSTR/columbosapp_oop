<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../models/adminModel/membershipRosterModel.php';

$rosterModel = new MembershipRosterModel($conn);

// Handle Add Member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    try {
        // Validate required fields
        $required_fields = ['member_number', 'council_id', 'member_name', 'street_address', 
                          'city_state_zip', 'first_degree', 'yrs_svc', 'birth_date', 'mbr_type'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required.");
            }
        }

        // Validate member number format (assuming it should be numeric)
        if (!is_numeric($_POST['member_number'])) {
            throw new Exception("Member number must be numeric.");
        }

        // Validate dates
        $dates_to_validate = ['first_degree', 'second_degree', 'third_degree', 'birth_date'];
        foreach ($dates_to_validate as $date_field) {
            if (!empty($_POST[$date_field])) {
                $date = date_create_from_format('Y-m-d', $_POST[$date_field]);
                if (!$date) {
                    throw new Exception("Invalid date format for $date_field.");
                }
                // Ensure dates are not in the future
                if ($date > date_create()) {
                    throw new Exception("$date_field cannot be in the future.");
                }
            }
        }

        // Validate years of service is numeric and non-negative
        if (!is_numeric($_POST['yrs_svc']) || $_POST['yrs_svc'] < 0) {
            throw new Exception("Years of service must be a non-negative number.");
        }

        // Check if member number already exists for this council
        if ($rosterModel->getMemberDetails($_POST['member_number'], $_POST['council_id'])) {
            throw new Exception("Member number already exists in this council.");
        }

        // Prepare data for insertion
        $data = array(
            'member_number' => trim($_POST['member_number']),
            'council_id' => $_POST['council_id'],
            'member_name' => trim($_POST['member_name']),
            'street_address' => trim($_POST['street_address']),
            'city_state_zip' => trim($_POST['city_state_zip']),
            'first_degree' => $_POST['first_degree'],
            'second_degree' => !empty($_POST['second_degree']) ? $_POST['second_degree'] : null,
            'third_degree' => !empty($_POST['third_degree']) ? $_POST['third_degree'] : null,
            'reentry_date' => !empty($_POST['reentry_date']) ? $_POST['reentry_date'] : null,
            'yrs_svc' => $_POST['yrs_svc'],
            'birth_date' => $_POST['birth_date'],
            'mbr_type' => trim($_POST['mbr_type']),
            'mbr_cls' => !empty($_POST['mbr_cls']) ? trim($_POST['mbr_cls']) : null,
            'assy' => !empty($_POST['assy']) ? trim($_POST['assy']) : null,
            'exm' => !empty($_POST['exm']) ? trim($_POST['exm']) : null
        );

        // Attempt to add the member
        if ($rosterModel->addMember($data)) {
            $_SESSION['success'] = "Member {$data['member_name']} (#{$data['member_number']}) has been added successfully!";
            
            // Log the action
            error_log(date('Y-m-d H:i:s') . " - New member added: {$data['member_name']} (#{$data['member_number']}) to council #{$data['council_id']}\n", 3, "../../logs/member_actions.log");
        } else {
            throw new Exception("Database error: Failed to add member.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        error_log(date('Y-m-d H:i:s') . " - Error adding member: " . $e->getMessage() . "\n", 3, "../../logs/error.log");
    }

    header("Location: ../../views/admin/membership_roster.php?council_id=" . $_POST['council_id']);
    exit();
}

// Handle Update Member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    header('Content-Type: application/json');
    try {
        // Validate required fields
        $required_fields = ['member_number', 'council_id', 'member_name', 'street_address', 
                          'city_state_zip', 'first_degree', 'yrs_svc', 'birth_date', 'mbr_type'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required.");
            }
        }

        // Validate member number format
        if (!is_numeric($_POST['member_number'])) {
            throw new Exception("Member number must be numeric.");
        }

        // Validate dates
        $dates_to_validate = ['first_degree', 'second_degree', 'third_degree', 'birth_date'];
        foreach ($dates_to_validate as $date_field) {
            if (!empty($_POST[$date_field])) {
                $date = date_create_from_format('Y-m-d', $_POST[$date_field]);
                if (!$date) {
                    throw new Exception("Invalid date format for $date_field.");
                }
                // Ensure dates are not in the future
                if ($date > date_create()) {
                    throw new Exception("$date_field cannot be in the future.");
                }
            }
        }

        // Validate years of service is numeric and non-negative
        if (!is_numeric($_POST['yrs_svc']) || $_POST['yrs_svc'] < 0) {
            throw new Exception("Years of service must be a non-negative number.");
        }

        $member_number = $_POST['member_number'];
        $data = array(
            'council_id' => $_POST['council_id'],
            'member_name' => trim($_POST['member_name']),
            'street_address' => trim($_POST['street_address']),
            'city_state_zip' => trim($_POST['city_state_zip']),
            'first_degree' => $_POST['first_degree'],
            'second_degree' => !empty($_POST['second_degree']) ? $_POST['second_degree'] : null,
            'third_degree' => !empty($_POST['third_degree']) ? $_POST['third_degree'] : null,
            'reentry_date' => !empty($_POST['reentry_date']) ? $_POST['reentry_date'] : null,
            'yrs_svc' => $_POST['yrs_svc'],
            'birth_date' => $_POST['birth_date'],
            'mbr_type' => trim($_POST['mbr_type']),
            'mbr_cls' => !empty($_POST['mbr_cls']) ? trim($_POST['mbr_cls']) : null,
            'assy' => !empty($_POST['assy']) ? trim($_POST['assy']) : null,
            'exm' => !empty($_POST['exm']) ? trim($_POST['exm']) : null
        );

        // Log the update attempt
        error_log("Attempting to update member: " . print_r([
            'member_number' => $member_number,
            'data' => $data
        ], true));

        if ($rosterModel->updateMember($member_number, $data)) {
            $response = [
                'status' => 'success',
                'message' => "Member {$data['member_name']} (#{$member_number}) has been updated successfully!"
            ];
            error_log("Member update successful: #$member_number");
        } else {
            throw new Exception("Database error: Failed to update member.");
        }
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
        error_log("Error updating member: " . $e->getMessage());
    }

    echo json_encode($response);
    exit();
}

// Handle Delete Member
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    try {
        $member_number = $_GET['member_number'];
        $council_id = $_GET['council_id'];

        if ($rosterModel->deleteMember($member_number, $council_id)) {
            $_SESSION['success'] = "Member deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete member.";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: ../../views/admin/membership_roster.php?council_id=" . $_GET['council_id']);
    exit();
}

// Handle Get Member Details (for AJAX requests)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_details') {
    header('Content-Type: application/json');
    try {
        $member_number = $_GET['member_number'];
        $council_id = $_GET['council_id'];
        
        $member = $rosterModel->getMemberDetails($member_number, $council_id);
        if ($member) {
            echo json_encode(['status' => 'success', 'data' => $member]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Member not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
} 