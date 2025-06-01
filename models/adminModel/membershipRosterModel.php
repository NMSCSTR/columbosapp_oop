<?php

class MembershipRosterModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    /**
     * Get council details with manager and counselor names
     */
    public function getCouncilDetails($council_id) {
        $query = "SELECT c.*, 
                        um.firstname as um_firstname, um.lastname as um_lastname,
                        fc.firstname as fc_firstname, fc.lastname as fc_lastname
                 FROM council c
                 LEFT JOIN users um ON c.unit_manager_id = um.id
                 LEFT JOIN users fc ON c.fraternal_counselor_id = fc.id
                 WHERE c.council_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $council_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get all members of a specific council
     */
    public function getCouncilMembers($council_id) {
        $query = "SELECT * FROM membership_roster WHERE council_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $council_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $members = array();
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
        return $members;
    }

    /**
     * Add a new member to the roster
     */
    public function addMember($data) {
        $query = "INSERT INTO membership_roster (
            member_number, council_id, member_name, street_address, 
            city_state_zip, first_degree, second_degree, third_degree, 
            reentry_date, yrs_svc, birth_date, mbr_type, mbr_cls, assy, exm
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "sisssssssisssss",
            $data['member_number'],
            $data['council_id'],
            $data['member_name'],
            $data['street_address'],
            $data['city_state_zip'],
            $data['first_degree'],
            $data['second_degree'],
            $data['third_degree'],
            $data['reentry_date'],
            $data['yrs_svc'],
            $data['birth_date'],
            $data['mbr_type'],
            $data['mbr_cls'],
            $data['assy'],
            $data['exm']
        );

        return $stmt->execute();
    }

    /**
     * Update an existing member
     */
    public function updateMember($member_number, $data) {
        try {
            $query = "UPDATE membership_roster SET 
                member_name = ?, 
                street_address = ?, 
                city_state_zip = ?, 
                first_degree = ?, 
                second_degree = ?, 
                third_degree = ?, 
                reentry_date = ?, 
                yrs_svc = ?, 
                birth_date = ?, 
                mbr_type = ?, 
                mbr_cls = ?, 
                assy = ?, 
                exm = ? 
                WHERE member_number = ? AND council_id = ?";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }

            // Log the data being bound
            error_log("Binding parameters for member update: " . print_r([
                'member_name' => $data['member_name'],
                'street_address' => $data['street_address'],
                'city_state_zip' => $data['city_state_zip'],
                'first_degree' => $data['first_degree'],
                'second_degree' => $data['second_degree'],
                'third_degree' => $data['third_degree'],
                'reentry_date' => $data['reentry_date'],
                'yrs_svc' => $data['yrs_svc'],
                'birth_date' => $data['birth_date'],
                'mbr_type' => $data['mbr_type'],
                'mbr_cls' => $data['mbr_cls'],
                'assy' => $data['assy'],
                'exm' => $data['exm'],
                'member_number' => $member_number,
                'council_id' => $data['council_id']
            ], true));

            $stmt->bind_param(
                "sssssssissssssi",  // Fixed type string to match parameters
                $data['member_name'],
                $data['street_address'],
                $data['city_state_zip'],
                $data['first_degree'],
                $data['second_degree'],
                $data['third_degree'],
                $data['reentry_date'],
                $data['yrs_svc'],
                $data['birth_date'],
                $data['mbr_type'],
                $data['mbr_cls'],
                $data['assy'],
                $data['exm'],
                $member_number,
                $data['council_id']
            );

            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in updateMember: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a member from the roster
     */
    public function deleteMember($member_number, $council_id) {
        $query = "DELETE FROM membership_roster WHERE member_number = ? AND council_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $member_number, $council_id);
        return $stmt->execute();
    }

    /**
     * Get a single member's details
     */
    public function getMemberDetails($member_number, $council_id) {
        $query = "SELECT * FROM membership_roster WHERE member_number = ? AND council_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $member_number, $council_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
} 