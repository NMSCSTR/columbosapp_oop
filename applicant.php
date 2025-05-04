<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "columbos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the applicants table
$sql = "SELECT * FROM applicants";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Data</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-semibold text-center mb-8">Complete Applicants Data</h1>

        <!-- Applicants Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600">
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">Lastname</th>
                        <th class="px-4 py-2 border">Firstname</th>
                        <th class="px-4 py-2 border">Middlename</th>
                        <th class="px-4 py-2 border">Birthdate</th>
                        <th class="px-4 py-2 border">Gender</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Application Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr class='bg-white'>";
                            echo "<td class='px-4 py-2 border'>" . $row["applicant_id"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["lastname"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["firstname"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["middlename"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["birthdate"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["gender"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["status"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $row["application_status"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='px-4 py-2 text-center'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Contact Info Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600">
                        <th class="px-4 py-2 border">Applicant ID</th>
                        <th class="px-4 py-2 border">Street</th>
                        <th class="px-4 py-2 border">Barangay</th>
                        <th class="px-4 py-2 border">City/Province</th>
                        <th class="px-4 py-2 border">Mobile</th>
                        <th class="px-4 py-2 border">Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data from contact_info table
                    $contact_sql = "SELECT * FROM contact_info";
                    $contact_result = $conn->query($contact_sql);
                    if ($contact_result->num_rows > 0) {
                        while($contact_row = $contact_result->fetch_assoc()) {
                            echo "<tr class='bg-white'>";
                            echo "<td class='px-4 py-2 border'>" . $contact_row["applicant_id"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $contact_row["street"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $contact_row["barangay"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $contact_row["city_prov"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $contact_row["mobile"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $contact_row["email"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='px-4 py-2 text-center'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Employment Table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600">
                        <th class="px-4 py-2 border">Applicant ID</th>
                        <th class="px-4 py-2 border">Occupation</th>
                        <th class="px-4 py-2 border">Employment Status</th>
                        <th class="px-4 py-2 border">Duties</th>
                        <th class="px-4 py-2 border">Employer</th>
                        <th class="px-4 py-2 border">Work</th>
                        <th class="px-4 py-2 border">Nature of Business</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data from employment table
                    $employment_sql = "SELECT * FROM employment";
                    $employment_result = $conn->query($employment_sql);
                    if ($employment_result->num_rows > 0) {
                        while($employment_row = $employment_result->fetch_assoc()) {
                            echo "<tr class='bg-white'>";
                            echo "<td class='px-4 py-2 border'>" . $employment_row["applicant_id"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $employment_row["occupation"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $employment_row["employment_status"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $employment_row["duties"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $employment_row["employer"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $employment_row["work"] . "</td>";
                            echo "<td class='px-4 py-2 border'>" . $employment_row["nature_business"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='px-4 py-2 text-center'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- More tables can follow for the other categories like 'plans', 'beneficiaries', 'family_background', etc. -->

        <!-- Plans Table -->
<div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600">
                <th class="px-4 py-2 border">Applicant ID</th>
                <th class="px-4 py-2 border">Plan Name</th>
                <th class="px-4 py-2 border">Plan Code</th>
                <th class="px-4 py-2 border">Face Value</th>
                <th class="px-4 py-2 border">Years Contribute</th>
                <th class="px-4 py-2 border">Years Protect</th>
                <th class="px-4 py-2 border">Years Mature</th>
                <th class="px-4 py-2 border">Payment Mode</th>
                <th class="px-4 py-2 border">Currency</th>
                <th class="px-4 py-2 border">Contribution Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $plan_sql = "SELECT * FROM plans";
            $plan_result = $conn->query($plan_sql);
            if ($plan_result->num_rows > 0) {
                while($plan_row = $plan_result->fetch_assoc()) {
                    echo "<tr class='bg-white'>";
                    foreach ($plan_row as $value) {
                        echo "<td class='px-4 py-2 border'>{$value}</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10' class='px-4 py-2 text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


<!-- Beneficiaries Table -->
<div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600">
                <th class="px-4 py-2 border">Applicant ID</th>
                <th class="px-4 py-2 border">Benefit Name 1</th>
                <th class="px-4 py-2 border">DOB 1</th>
                <th class="px-4 py-2 border">Relationship 1</th>
                <th class="px-4 py-2 border">Benefit Name 2</th>
                <th class="px-4 py-2 border">DOB 2</th>
                <th class="px-4 py-2 border">Relationship 2</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ben_sql = "SELECT * FROM beneficiaries";
            $ben_result = $conn->query($ben_sql);
            if ($ben_result->num_rows > 0) {
                while($ben_row = $ben_result->fetch_assoc()) {
                    echo "<tr class='bg-white'>";
                    foreach ($ben_row as $value) {
                        echo "<td class='px-4 py-2 border'>{$value}</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='px-4 py-2 text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>



<!-- Family Background Table -->
<div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600">
                <th class="px-4 py-2 border">Applicant ID</th>
                <th class="px-4 py-2 border">Father</th>
                <th class="px-4 py-2 border">Mother</th>
                <th class="px-4 py-2 border">Siblings Living</th>
                <th class="px-4 py-2 border">Siblings Deceased</th>
                <th class="px-4 py-2 border">Children Living</th>
                <th class="px-4 py-2 border">Children Deceased</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $fam_sql = "SELECT * FROM family_background";
            $fam_result = $conn->query($fam_sql);
            if ($fam_result->num_rows > 0) {
                while($fam_row = $fam_result->fetch_assoc()) {
                    echo "<tr class='bg-white'>";
                    foreach ($fam_row as $value) {
                        echo "<td class='px-4 py-2 border'>{$value}</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='px-4 py-2 text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


<!-- Family Health Table -->
<div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600">
                <th class="px-4 py-2 border">Applicant ID</th>
                <th class="px-4 py-2 border">Father Age/Health</th>
                <th class="px-4 py-2 border">Mother Age/Health</th>
                <th class="px-4 py-2 border">Siblings Age/Health</th>
                <th class="px-4 py-2 border">Children Age/Health</th>
                <th class="px-4 py-2 border">Father Death Age/Cause</th>
                <th class="px-4 py-2 border">Mother Death Age/Cause</th>
                <th class="px-4 py-2 border">Siblings Death Age/Cause</th>
                <th class="px-4 py-2 border">Children Death Age/Cause</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $fh_sql = "SELECT * FROM family_health";
            $fh_result = $conn->query($fh_sql);
            if ($fh_result->num_rows > 0) {
                while($fh_row = $fh_result->fetch_assoc()) {
                    echo "<tr class='bg-white'>";
                    foreach ($fh_row as $value) {
                        echo "<td class='px-4 py-2 border'>{$value}</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='px-4 py-2 text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>




<!-- Physician Table -->
<div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600">
                <th class="px-4 py-2 border">Applicant ID</th>
                <th class="px-4 py-2 border">Physician Name</th>
                <th class="px-4 py-2 border">Physician Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $phys_sql = "SELECT * FROM physician";
            $phys_result = $conn->query($phys_sql);
            if ($phys_result->num_rows > 0) {
                while($phys_row = $phys_result->fetch_assoc()) {
                    echo "<tr class='bg-white'>";
                    foreach ($phys_row as $value) {
                        echo "<td class='px-4 py-2 border'>{$value}</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='px-4 py-2 text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


<!-- Health Questions Table -->
<div class="overflow-x-auto bg-white shadow-md rounded-lg mb-8">
    <table class="min-w-full table-auto">
        <thead>
            <tr class="bg-gray-200 text-gray-600">
                <th class="px-4 py-2 border">Applicant ID</th>
                <th class="px-4 py-2 border">P3 Q3</th>
                <th class="px-4 py-2 border">P3 Q4</th>
                <th class="px-4 py-2 border">P3 Q5</th>
                <th class="px-4 py-2 border">P3 Q6A</th>
                <th class="px-4 py-2 border">P3 Q6B</th>
                <th class="px-4 py-2 border">P3 Q7</th>
                <th class="px-4 py-2 border">P3 Q8</th>
                <th class="px-4 py-2 border">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $hsql = "SELECT * FROM health_questions";
            $hres = $conn->query($hsql);
            if ($hres->num_rows > 0) {
                while($row = $hres->fetch_assoc()) {
                    echo "<tr class='bg-white'>";
                    foreach ($row as $val) {
                        echo "<td class='px-4 py-2 border'>{$val}</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9' class='px-4 py-2 text-center'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
