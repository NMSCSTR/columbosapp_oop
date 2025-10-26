<?php
$dsn = 'mysql:host=localhost;dbname=insurance_db;charset=utf8mb4';
$user = 'root';
$pass = '';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
$pdo = new PDO($dsn, $user, $pass, $options);

function calculate_insurance_age($dob, $applicationDate = null) {
    $app = $applicationDate ? new DateTime($applicationDate) : new DateTime('today');
    $birth = new DateTime($dob);

    $ageInterval = $app->diff($birth);
    $natural_age = $ageInterval->y;

    $nextBirthday = (clone $birth)->setDate((int)$app->format('Y'), (int)$birth->format('m'), (int)$birth->format('d'));
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $dob = $_POST['dob'];
    $appDate = $_POST['application_date'];

    $calculated = calculate_insurance_age($dob, $appDate);

    $sql = "INSERT INTO applicants (first_name, last_name, dob, application_date, calculated_age)
            VALUES (:first, :last, :dob, :appDate, :age)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':first' => $first,
        ':last' => $last,
        ':dob' => $dob,
        ':appDate' => $appDate,
        ':age' => $calculated
    ]);

    echo "<h3>Application Saved Successfully!</h3>";
    echo "<p><strong>Name:</strong> {$first} {$last}</p>";
    echo "<p><strong>Date of Birth:</strong> {$dob}</p>";
    echo "<p><strong>Application Date:</strong> {$appDate}</p>";
    echo "<p><strong>Calculated Insurance Age (Final Rule):</strong> {$calculated}</p>";
    echo '<p><a href="index.php">Back to Form</a></p>';
}
?>
