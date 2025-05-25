<?php
class adminControlController {
    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/Admin.php';
        require_once __DIR__ . '/../Models/User.php';
        require_once __DIR__ . '/../Models/Service.php';
        require_once __DIR__ . '/../Models/Category.php';

        // Only allow admins
        if (!isset($_SESSION['user_id']) || !(new Admin())->isAdmin($_SESSION['user_id'])) {
            header("Location: index.php?page=main");
            exit;
        }

        // Handle ban user
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ban_user_id'])) {
            $banUserId = intval($_POST['ban_user_id']);
            $admin = new Admin($_SESSION['user_id']);
            $admin->banUser($banUserId);
            header("Location: index.php?page=adminControl");
            exit;
        }

        // Handle delete service
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_service_id'])) {
            $deleteServiceId = intval($_POST['delete_service_id']);
            $service = new Service();
            $service->deleteService($deleteServiceId);
            header("Location: index.php?page=adminControl");
            exit;
        }

        // Handle elevate user
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['elevate_user_id'])) {
            $elevateUserId = intval($_POST['elevate_user_id']);
            $admin = new Admin();
            $admin->elevateUser($elevateUserId);
            header("Location: index.php?page=adminControl");
            exit;
        }


        // Handle add category
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
            $categoryName = trim($_POST['category_name']);
            if ($categoryName !== '') {
                $admin = new Admin();
                $admin->addCategory($categoryName);
            }
            header("Location: index.php?page=adminControl");
            exit;
        }


        // Fetch all users and services for the admin panel
        $allUsers = Admin::getAllUsers();

        $allServices = Admin::getAllServices();
        $adminIds = array_map(
            function($row) { return $row['id']; },
            (new Admin())->getAllAdmins()
        );


        // Fetch all categories and build a lookup array
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        $categoryNames = [];
        foreach ($categories as $cat) {
            $categoryNames[$cat['category_id']] = $cat['name'];
        }
        include __DIR__ . '/../Views/admin_control_page.php';
    }
}
?>