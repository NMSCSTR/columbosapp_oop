<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insurance Applicant Age Calculator (Final Rule)</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
    form { background: white; padding: 20px; border-radius: 10px; width: 400px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    label { display: block; margin-top: 10px; }
    input, button { padding: 8px; width: 100%; margin-top: 5px; }
    button { background: #007bff; color: white; border: none; cursor: pointer; margin-top: 15px; }
    button:hover { background: #0056b3; }
    .preview { margin-top: 15px; font-weight: bold; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Insurance Applicant Form (Final Rule)</h2>
  <form action="process.php" method="POST">
    <label>First Name:</label>
    <input type="text" name="first_name" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" required>

    <label>Date of Birth:</label>
    <input type="date" id="dob" name="dob" required>

    <label>Application Date:</label>
    <input type="date" id="appDate" name="application_date" value="<?php echo date('Y-m-d'); ?>" required>

    <div class="preview">
      Calculated Insurance Age: <span id="agePreview">—</span>
    </div>

    <button type="submit">Submit Application</button>
  </form>

  <script>
  function calculateInsuranceAgeJS(dobStr, appDateStr) {
    if (!dobStr) return null;
    const dob = new Date(dobStr + 'T00:00:00');
    const appDate = appDateStr ? new Date(appDateStr + 'T00:00:00') : new Date();

    let naturalAge = appDate.getFullYear() - dob.getFullYear();
    const appMonthDay = (appDate.getMonth()+1)*100 + appDate.getDate();
    const dobMonthDay = (dob.getMonth()+1)*100 + dob.getDate();
    if (appMonthDay < dobMonthDay) naturalAge -= 1;

    let nextBirthday = new Date(dob);
    nextBirthday.setFullYear(appDate.getFullYear());
    if (nextBirthday < appDate) {
      nextBirthday.setFullYear(nextBirthday.getFullYear() + 1);
    }

    const sixMonthsAfter = new Date(appDate);
    sixMonthsAfter.setMonth(sixMonthsAfter.getMonth() + 6);

    // Final Rule: if birthday is more than 6 months away, add +1
    if (nextBirthday > sixMonthsAfter) {
      return naturalAge + 1;
    } else {
      return naturalAge;
    }
  }

  function updatePreview() {
    const dob = document.getElementById('dob').value;
    const appDate = document.getElementById('appDate').value || null;
    const preview = document.getElementById('agePreview');
    const calc = calculateInsuranceAgeJS(dob, appDate);
    preview.textContent = calc === null ? '—' : calc;
  }

  document.getElementById('dob').addEventListener('change', updatePreview);
  document.getElementById('appDate').addEventListener('change', updatePreview);
  </script>
</body>
</html>
