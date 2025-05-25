<?php
class orderController {
    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/Demand.php';
        require_once __DIR__ . '/../Models/Service.php';
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Service_Media.php';
        require_once __DIR__ . '/../Models/Review.php';

        $order_id = $_SESSION['order_id'] ?? null;
        $user_id = $_SESSION['user_id'] ?? null;

        if (!$order_id || !$user_id) {
            header('Location: index.php?page=user');
            exit;
        }

        $demandModel = new Demand();
        $order = $demandModel->getDemandById($order_id);

        if (!$order || $order['client_id'] != $user_id) {
            header('Location: index.php?page=user');
            exit;
        }

        $serviceModel = new Service();
        $service = $serviceModel->getService($order['service_id']);

        if (!$service) {
            header('Location: index.php?page=user');
            exit;
        }

        $freelancerName = User::getNameById($service['freelancer_id']);
        $mediaModel = new Service_Media();
        $images = $mediaModel->getMediaByService($service['service_id']);
        $reviewModel = new Review();
        error_log("Controller: user_id=$user_id, service_id={$service['service_id']}");
        $existingReview = $reviewModel->getUserReviews($user_id, $service['service_id']);
        $canReview = ($order['completed'] == 1);

        // --- Handle POST (review submit/delete) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Delete review
            if (isset($_POST['delete_review'])) {
                $reviewModel->deleteReview($service['service_id'], $user_id);
                header("Location: index.php?page=order");
                exit;
            }

            // Submit review
            if (isset($_POST['rating']) && isset($_POST['comment'])) {
                $rating = intval($_POST['rating']);
                $comment = trim($_POST['comment']);


                if ($canReview && !is_array($existingReview) && $rating >= 1 && $rating <= 5) {
                    $review = new Review($service['service_id'], $user_id, $rating, $comment);
                    $result = $review->createReview();
                    if ($result === -1) {
                        $_SESSION['review_error'] = "You have already reviewed this service.";
                    }
                }
                header("Location: index.php?page=order");
                exit;
            }
        }

        // --- Render view ---
        include __DIR__ . '/../Views/order_view.php';
    }
}
?>

