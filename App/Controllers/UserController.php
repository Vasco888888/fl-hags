<?php
class userController {

    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Demand.php';
        require_once __DIR__ . '/../Models/Service.php';
        require_once __DIR__ . '/../Models/Admin.php';

        // Get user object
        $user = new User($_SESSION['username']);
        $userid = $user->getID();
        // Check if user is admin
        $isAdmin = (new Admin())->isAdmin($userid);

        // Get orders (demands) for this user (as client)
        $orders = (new Demand())->getDemandsCompletedByClient($userid);

        // Get services for this user (as freelancer)
        $services = (new Service())->getFreelancerServices($userid);

        // Pass variables to the view
        include __DIR__ . '/../Views/user_view.php';
    }
}
?>