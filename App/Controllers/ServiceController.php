<?php

require_once __DIR__ . '/../Models/Service.php';

class ServiceController {
    public function index() {
        $serv = new Service();
        $services = $serv->getAllServices(1);
        include __DIR__ . '/../Views/service_list.php';
    }
}
?>
