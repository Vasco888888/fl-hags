<?php

require_once __DIR__ . '/../Models/Service.php';

class serviceController {
    public function index() {        
        require_once __DIR__ . '/../Models/Service_Media.php';
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Service.php';
        require_once __DIR__ . '/../Models/Review.php';
        require_once __DIR__ . '/../Models/Category.php';

        session_start();
        // Get service_id from GET parameters
        $service_id = $_POST['service_id'] ?? null;
        if (!$service_id) {
            echo "Service not found.";
            exit;
        }

        $serviceModel = new Service();
        $service = $serviceModel->getService($service_id);

        if (!$service) {
            echo "Service not found.";
            exit;
        }

        $categoryModel = new Category();
        $category = $categoryModel->getCategory($service['category_id']);
        $categoryName = $category['name'] ?? 'Unknown';


        $media = Service_Media::getMediaByService($service_id);
        $freelancer_id = $service['freelancer_id'];
        // You may need to add a method to get user by ID if not present
        $freelancerName = User::getNameById($freelancer_id);

        $reviewModel = new Review();
        $allReviews = $reviewModel->getReviews($service_id);

        $_SESSION['service_id'] = $service_id;
        $_SESSION['freelancer_id'] = $freelancer_id;
        $_SESSION['freelancerName'] = $freelancerName;

        include __DIR__ . '/../Views/service_view.php';
    }
}
?>
