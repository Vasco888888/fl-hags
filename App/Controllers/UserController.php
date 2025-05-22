<?php
class userController {

    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Demand.php';
        require_once __DIR__ . '/../Models/Service.php';

        // Get user object
        $user = new User($_SESSION['username']);

        // Get orders (demands) for this user (as client)
        $orders = (new Demand())->getDemandsByClient($user->getID());

        // Get services for this user (as freelancer)
        $services = (new Service())->getFreelancerServices($user->getID());

        // Pass variables to the view
        include __DIR__ . '/../Views/user_view.php';
    }
}
?>