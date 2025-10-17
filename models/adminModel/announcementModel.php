<?php
class announcementModel
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function getAllAnnouncement()
    {
        $sql    = "SELECT * FROM announcements ORDER BY date_posted DESC";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $announcements = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $announcements[] = $row;
            }
            return $announcements;
        }

        return null;
    }

    public function getAnnouncementDetails($announcementId)
    {
        $cleanId = (int)$announcementId;
        $sql = "SELECT subject, content FROM announcements WHERE id = $cleanId";
        $result = mysqli_query($this->conn, $sql);
        
        if ($result && $row = mysqli_fetch_assoc($result)) {
            return $row;
        }
        return null;
    }

    public function getAllUserPhoneNumber()
    {
        $sql    = "SELECT * FROM users WHERE role NOT IN ('admin', 'member', 'family-member') AND status = 'approved'";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $users = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
            return $users;
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

    public function insertAnnouncement($subject, $content)
    {
        $subject = mysqli_real_escape_string($this->conn, $subject);
        $content = mysqli_real_escape_string($this->conn, $content);

        $query  = "INSERT INTO `announcements`(`subject`, `content`) VALUES ('$subject','$content')";
        $result = mysqli_query($this->conn, $query);

        if ($result) {
            $message = "$subject: $content";

            $users = $this->getAllUserPhoneNumber();
            if ($users) {
                foreach ($users as $user) {
                    $this->sendSMS($user['phone_number'], $message);
                }
            }

            return true;
        }

        return false;
    }

    public function updateAnnouncement($id, $subject, $content)
    {
        $id      = mysqli_real_escape_string($this->conn, $id);
        $subject = mysqli_real_escape_string($this->conn, $subject);
        $content = mysqli_real_escape_string($this->conn, $content);

        $query  = "UPDATE `announcements` SET `subject`='$subject',`content`='$content' WHERE id = '$id'";
        $result = mysqli_query($this->conn, $query);
        return $result ? true : false;
    }

    public function deleteAnnouncement($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);

        $deleteQuery = "DELETE FROM announcements WHERE id = '$id'";
        $result      = mysqli_query($this->conn, $deleteQuery);
        return $result ? true : false;
    }

}
