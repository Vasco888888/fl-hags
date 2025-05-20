<?php
class userController {
    
    public function index() {
        header('Location: index.php?page=edit');
        exit;
    }
}
?>