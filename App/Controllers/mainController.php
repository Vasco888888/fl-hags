<?php
class mainController {
    public function index() {
        $imgDir = __DIR__ . '/../../assets/img/';
        $imgUrlBase = '/assets/img/';
        $images = [];
        foreach (glob($imgDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE) as $img) {
            $images[] = $imgUrlBase . basename($img);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['signin'])) {
                header("Location: index.php?page=signIn");
                exit;
            } elseif (isset($_GET['signup'])) {
                header("Location: index.php?page=signUp");
                exit;
            }
        }

        include __DIR__ . '/../Views/main_page_view.php';
    }
}