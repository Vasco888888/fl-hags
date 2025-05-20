<?php

class Router
{
    public function handleRequest()
    {
        $page = $_GET['page'] ?? 'main';

        // Capitalize and append Controller
        $controllerName = $page . 'Controller';
        $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                $controller->index(); // Calls default method
            } else {
                $this->show404("Controller class '$controllerName' not found.");
            }
        } else {
            $this->show404("Controller file '$controllerFile' not found.");
        }
    }

    private function show404($message)
    {
        echo "<h1>404 Not Found</h1><p>$message</p>";
        exit;
    }
}

?>