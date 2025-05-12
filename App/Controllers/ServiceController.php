<?php

require_once __DIR__ . '/../Models/Service.php';

class ServiceController {
    public static function index() {
        $serv = new Service();
        $services = $serv->all();
        include __DIR__ . '/../Views/service_list.php';
    }
}
?>
