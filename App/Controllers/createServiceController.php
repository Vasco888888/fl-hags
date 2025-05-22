<?php

require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Service.php';
require_once __DIR__ . '/../Models/Service_Media.php';
require_once __DIR__ . '/../Models/User.php';

class createServiceController {
    public function index() {
        // Load categories for dropdown
        session_start();
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        $categoryOptions = '';
        foreach ($categories as $cat) {
            $categoryOptions .= '<option value="' . htmlspecialchars($cat['category_id']) . '">' . htmlspecialchars($cat['name']) . '</option>';
        }

        // Handle form submission
        $success = false;
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $base_price = floatval($_POST['base_price'] ?? 0);
            $category_id = intval($_POST['category'] ?? 0);

            if ($title && $description && $base_price > 0) {
                $user = new User($_SESSION['username']);
                $freelancer_id = $user->getID();
                $service = new Service($freelancer_id, $category_id, $title, $description, $base_price);
                $service_id = $service->createService();

                // Handle image uploads
                if (!empty($_FILES['images']['name'][0])) {
                    foreach ($_FILES['images']['tmp_name'] as $idx => $tmpName) {
                        if ($_FILES['images']['error'][$idx] === UPLOAD_ERR_OK) {
                            $ext = strtolower(pathinfo($_FILES['images']['name'][$idx], PATHINFO_EXTENSION));
                            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                                $error = "Only JPG and PNG images are allowed.";
                                continue;
                            }
                            $targetDir = '/assets/img/uploads/';
                            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . $targetDir;
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }
                            $target = $targetDir . uniqid('img_') . '.' . $ext;
                            $absTarget = $_SERVER['DOCUMENT_ROOT'] . $target;
                            if (move_uploaded_file($tmpName, $absTarget)) {
                                $media = new Service_Media($service_id, 'image', $target);
                                $media->addMedia();
                            }
                        }
                    }
                }
                $success = true;
                header ("Location: index.php?page=allService");
                exit;
            } else {
                $error = "Please fill all required fields.";
            }
        }

        include __DIR__ . '/../Views/create_service_view.php';
    }
}

?>