<?php

class allServiceController {

    public function index() {
        session_start();
        require_once __DIR__ . '/../Models/Service.php';
        require_once __DIR__ . '/../Models/Category.php';
        require_once __DIR__ . '/../Models/Service_Media.php';
        require_once __DIR__ . '/../Models/Review.php';

        $username = $_SESSION['username'] ?? 'Guest';

        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get filter/search params with defaults
            $selectedCategory = $_POST['category'] ?? 'all';
            $minPrice = $_POST['min_price'] ?? 0;
            $maxPrice = $_POST['max_price'] ?? 50;
            $minRating = $_POST['min_rating'] ?? 0;
            $sort = $_POST['sort'] ?? 'asc';

            // Fetch services with filters
            $serviceModel = new Service();
            $services = $serviceModel->filterServices($selectedCategory, $minPrice, $maxPrice, $minRating, $sort);
        } else {
            // Default to all services and default filter values
            $selectedCategory = 'all';
            $minPrice = 0;
            $maxPrice = 50;
            $minRating = 0;
            $sort = 'asc';

            $serviceModel = new Service();
            $services = $serviceModel->getAllServices();
        }

        // Prepare category options HTML
        $categoryOptions = '<option value="all"' . ($selectedCategory === 'all' ? ' selected' : '') . '>All Categories</option>';
        foreach ($categories as $cat) {
            $selected = $selectedCategory == $cat['category_id'] ? ' selected' : '';
            $categoryOptions .= '<option value="' . htmlspecialchars($cat['category_id']) . '"' . $selected . '>' . htmlspecialchars($cat['name']) . '</option>';
        }

        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();
        $categoryNames = [];
        foreach ($categories as $cat) {
            $categoryNames[$cat['category_id']] = $cat['name'];
        }        

        // Prepare service cards HTML
        $serviceCards = '';
        foreach ($services as $service) {
            $categoryName = $categoryNames[$service['category_id']] ?? 'Unknown';
            $image = Service_Media::getMainImage($service['service_id']);

            $rating = (new Review())->getAverageRating($service['service_id']);
            $ratingText = $rating ? number_format($rating, 1) . ' ★' : 'No rating';

            $serviceCards .= '
                <div class="service-card">
                    <img src="' . htmlspecialchars($image) . '" alt="Service Image" class="service-img">
                    <h2>' . htmlspecialchars($service['title']) . '</h2>
                    <div class="service-meta">
                        <span class="service-price">€ ' . htmlspecialchars($service['base_price']) . '</span>
                        <span class="service-category">' . htmlspecialchars($categoryName) . '</span>
                        <span class="service-rating">' . $ratingText . '</span>
                    </div>
                    <form action="index.php?page=service" method="post" style="display:inline;">
                        <input type="hidden" name="service_id" value="' . htmlspecialchars($service['service_id']) . '">
                        <button type="submit" class="main-btn">View</button>
                    </form>
                </div>
            ';
        }

        include __DIR__ . '/../Views/all_service_view.php';
    }
}
?>