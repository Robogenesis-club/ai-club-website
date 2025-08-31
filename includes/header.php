<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'RoboGenesis'; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
    <link rel="stylesheet" href="css/style.css" />

    <style>
        section { display: block; padding: 60px 0; }
    </style>
</head>
<body>