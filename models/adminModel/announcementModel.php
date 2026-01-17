<?php
class announcementModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    /* =======================
       GET ALL ANNOUNCEMENTS
    ======================== */
    public function getAllAnnouncement()
    {
        $sql = "SELECT * FROM announcements ORDER BY date_posted DESC";
        $result = mysqli_query($this->conn, $sql);

        $announcements = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $announcements[] = $row;
            }
        }

        return $announcements;
    }

    /* =======================
       GET SELECTABLE USERS
    ======================== */
    public function getSelectableUsers()
    {
        $sql = "SELECT id, firstname, lastname, phone_number
                FROM users
                WHERE role NOT IN ('admin', 'member', 'family-member')
                  AND status = 'approved'
                  AND phone_number IS NOT NULL
                ORDER BY firstname ASC";

        $result = mysqli_query($this->conn, $sql);

        $users = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }

        return $users;
    }

    /* =======================
       GET USERS BY IDS
    ======================== */
    private function getUsersByIds(array $ids)
    {
        if (empty($ids)) return [];

        $ids = array_map('intval', $ids);
        $idList = implode(',', $ids);

        $sql = "SELECT phone_number FROM users
                WHERE id IN ($idList)
                  AND phone_number IS NOT NULL";

        $result = mysqli_query($this->conn, $sql);
        $users = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }

        return $users;
    }

    /* =======================
       SEND SMS
    ======================== */
    private function sendSMS($number, $message)
    {
        $ch = curl_init();

        $parameters = [
            'apikey'     => '5bf90b2585f02b48d22e01d79503e591',
            'number'     => $number,
            'message'    => $message,
            'sendername' => 'KCFAPI',
        ];

        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        file_put_contents(
            'sms_log.txt',
            date('Y-m-d H:i:s') . " | To: $number | Response: $response\n",
            FILE_APPEND
        );

        return $response;
    }

    public function getAnnouncementDetails($announcementId)
    {
        $id = (int) $announcementId;
        $sql = "SELECT * FROM announcements WHERE id = $id";
        $result = mysqli_query($this->conn, $sql);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            return $row;
        }

        return null;
    }


    /* =======================
       INSERT ANNOUNCEMENT
    ======================== */
    public function insertAnnouncement($subject, $content, array $recipientIds)
    {
        if (empty($recipientIds)) return false;

        $subject = mysqli_real_escape_string($this->conn, $subject);
        $content = mysqli_real_escape_string($this->conn, $content);

        // Start transaction
        mysqli_begin_transaction($this->conn);

        try {
            $query = "INSERT INTO announcements (subject, content) VALUES ('$subject', '$content')";
            $result = mysqli_query($this->conn, $query);

            if (! $result) {
                throw new Exception("Failed to insert announcement: " . mysqli_error($this->conn));
            }

            $announcementId = mysqli_insert_id($this->conn);

            // Send SMS to selected users
            $message = "$subject: $content";
            $users   = $this->getUsersByIds($recipientIds);

            foreach ($users as $user) {
                $this->sendSMS($user['phone_number'], $message);
            }

            mysqli_commit($this->conn);
            return $announcementId;

        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log($e->getMessage());
            return false;
        }
    }

    /* =======================
       DELETE ANNOUNCEMENT
    ======================== */
    public function deleteAnnouncement($id)
    {
        $id = (int) $id;
        $sql = "DELETE FROM announcements WHERE id = $id";
        return mysqli_query($this->conn, $sql);
    }
}
