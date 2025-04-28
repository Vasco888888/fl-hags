<?php
require_once '../app/Core/Database.php';
require_once '../app/Models/Service.php';
require_once '../app/Controllers/ServiceController.php';

use App\Controllers\ServiceController;

ServiceController::index();
