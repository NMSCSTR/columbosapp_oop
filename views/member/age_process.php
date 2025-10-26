<?php
// --- Database connection ---
$servername = "localhost";
$username = "root";
$password = "";
$database = "insurance_db";

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// --- Function to calculate insurance age ---
function calculate_insurance_age($dob, $applicationDate = null) {
    $app = $applicationDate ? new DateTime($applicationDate) : new DateTime('today');
    $birth = new DateTime($dob);

    $ageInterval = $app->diff($birth);
    $natural_age = $ageInterval->y;

    $nextBirthday = (clone $birth)->setDate(
        (int)$app->format('Y'),
        (int)$birth->format('m'),
        (int)$birth->format('d')
    );

    if ($nextBirthday < $app) {
        $nextBirthday->modify('+1 year');
    }

    $sixMonthsAfter = (clone $app)->modify('+6 months');

    // Final Rule: if next birthday is more than 6 months away, add +1
    if ($nextBirthday > $sixMonthsAfter) {
        return $natural_age + 1;
    } else {
        return $natural_age;
    }
}

// --- Handle form submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escape inputs for safety
    $first = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last = mysqli_real_escape_string($conn, $_POST['last_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $appDate = mysqli_real_escape_string($conn, $_POST['application_date']);

    $calculated = calculate_insurance_age($dob, $appDate);

    // Build and execute SQL (not prepared)
    $sql = "INSERT INTO applicants (first_name, last_name, dob, application_date, calculated_age)
            VALUES ('$first', '$last', '$dob', '$appDate', '$calculated')";

    if (mysqli_query($conn, $sql)) {
        echo "<h3>Application Saved Successfully!</h3>";
        echo "<p><strong>Name:</strong> {$first} {$last}</p>";
        echo "<p><strong>Date of Birth:</strong> {$dob}</p>";
        echo "<p><strong>Application Date:</strong> {$appDate}</p>";
        echo "<p><strong>Calculated Insurance Age (Final Rule):</strong> {$calculated}</p>";
        echo '<p><a href="index.php">Back to Form</a></p>';
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- Close connection ---
mysqli_close($conn);
?>
