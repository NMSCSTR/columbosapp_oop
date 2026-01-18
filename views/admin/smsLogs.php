<?php

function retrieveSMS($limit = 1000)
{
    $ch = curl_init();

    $params = [
        'apikey' => 'myapikey',
        'limit'  => $limit
    ];

    $url = 'https://semaphore.co/api/v4/messages?' . http_build_query($params);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$smsLogs = retrieveSMS(200); 



function getSemaphoreAccount()
{
    $ch = curl_init();

    $params = [
        'apikey' => 'myapikey'
    ];

    $url = 'https://api.semaphore.co/api/v4/account?' . http_build_query($params);

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPGET => true
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
$accountInfo = getSemaphoreAccount();

?>

<!DOCTYPE html>
<html>
<head>
    <title>SMS Logs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .sent { color: green; font-weight: bold; }
        .failed { color: red; font-weight: bold; }
        .pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>

<h2>Semaphore SMS Logs</h2>
<div style="margin-bottom:15px; padding:10px; border:1px solid #ddd; width:fit-content;">
    <strong>Account:</strong> <?= htmlspecialchars($accountInfo['account_name'] ?? 'N/A') ?><br>
    <strong>Status:</strong> <?= htmlspecialchars($accountInfo['status'] ?? 'N/A') ?><br>
    <strong>Credit Balance:</strong>
    <span style="color:green; font-weight:bold;">
        <?= htmlspecialchars($accountInfo['credit_balance'] ?? '0') ?>
    </span>
</div>


<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Mobile Number</th>
            <th>Message</th>
            <th>Status</th>
            <th>Sender</th>
            <th>Date Sent</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($smsLogs)) : ?>
            <?php foreach ($smsLogs as $index => $sms) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($sms['number'] ?? $sms['recipient'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($sms['message']) ?></td>
                    <td class="<?= strtolower($sms['status']) ?>">
                        <?= htmlspecialchars($sms['status']) ?>
                    </td>
                    <td><?= htmlspecialchars($sms['sendername'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($sms['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6" style="text-align:center;">No SMS logs found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
