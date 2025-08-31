<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $name = trim($_POST['name'] ?? '');
    $bennett_id = trim($_POST['bennett_id'] ?? '');  // Fixed hyphen to underscore for consistency
    $contact = trim($_POST['contact'] ?? '');
    
    if (empty($name) || empty($bennett_id) || empty($contact)) {
        header('Location: application.php?success=false&error=Please fill in all required fields');
        exit;
    }

    // Prepare data for SheetDB API
    $formData = [
        'data' => [
            'Full Name' => $name,
            'Bennett ID' => $bennett_id,
            'Contact Number' => $contact,
            'Personal Email' => trim($_POST['email'] ?? ''),
            'Accommodation Type' => trim($_POST['accommodation'] ?? ''),
            'Project Interest' => trim($_POST['project'] ?? ''),
            'Project Type' => trim($_POST['project_type'] ?? ''),  // Fixed hyphen to underscore
            'Submission Date' => date('c')
        ]
    ];

    // Send data to SheetDB API using cURL
    $ch = curl_init('https://sheetdb.io/api/v1/1c7tjutzi63sk');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($formData));
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 201 || $httpCode === 200) {
        header('Location: application.php?success=true');
        exit;
    } else {
        $error = json_decode($response, true)['error'] ?? 'Failed to submit form';
        header('Location: application.php?success=false&error=' . urlencode($error));
        exit;
    }
} else {
    header('Location: application.php?success=false&error=Invalid request method');
    exit;
}