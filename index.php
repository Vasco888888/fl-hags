<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>


<?php
require_once __DIR__ . '/app/Core/Dbh.php';
require_once __DIR__ . '/app/Models/Service.php';
require_once __DIR__ . '/app/Controllers/ServiceController.php';

ServiceController::index();
?>