<?php
// Inputs
$monthlyPremium = 1000;         // ₱1,000
$paymentMonths = 60;            // 5 years = 60 months
$annualInterestRate = 0.04;     // 4% annual interest
$growthYears = 10;              // 10 years of fund growth after payment

// Allocation Percentages
$insurancePercent = 0.15;
$feesPercent = 0.10;
$savingsPercent = 1 - $insurancePercent - $feesPercent;

// Monthly Allocations
$insuranceAmount = $monthlyPremium * $insurancePercent;
$feesAmount = $monthlyPremium * $feesPercent;
$savingsAmount = $monthlyPremium * $savingsPercent;

// Compound interest for savings growth
$monthlyInterestRate = $annualInterestRate / 12;

// Step 1: Accumulate savings over 5 years
$futureValue_5yrs = $savingsAmount * (pow(1 + $monthlyInterestRate, $paymentMonths) - 1) / $monthlyInterestRate;

// Step 2: Let it grow for another 10 years
$futureValue_15yrs = $futureValue_5yrs * pow(1 + $annualInterestRate, $growthYears);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insurance Allocation Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        table {
            border-collapse: collapse;
            width: 500px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        caption {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<table>
    <caption>Insurance Monthly Allocation Summary</caption>
    <tr>
        <th>Item</th>
        <th>Value</th>
    </tr>
    <tr>
        <td>Monthly Premium</td>
        <td>₱<?= number_format($monthlyPremium, 2) ?></td>
    </tr>
    <tr>
        <td>Insurance Portion (15%)</td>
        <td>₱<?= number_format($insuranceAmount, 2) ?></td>
    </tr>
    <tr>
        <td>Admin Fees (10%)</td>
        <td>₱<?= number_format($feesAmount, 2) ?></td>
    </tr>
    <tr>
        <td>Savings Fund (75%)</td>
        <td>₱<?= number_format($savingsAmount, 2) ?></td>
    </tr>
    <tr>
        <td>Total Contributions Over 5 Years</td>
        <td>₱<?= number_format($savingsAmount * $paymentMonths, 2) ?></td>
    </tr>
    <tr>
        <td>Future Value After 15 Years</td>
        <td><strong>₱<?= number_format($futureValue_15yrs, 2) ?></strong></td>
    </tr>
</table>

</body>
</html>
