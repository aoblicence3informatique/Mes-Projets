<?php
// Point d'entrée principal de l'application MVC

require_once '../config/config.php';
// Autoload des contrôleurs et modèles (à compléter)
// ...

// Exemple de routage simple (à améliorer selon les besoins)
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerFile = "../controllers/{$controller}Controller.php";
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = ucfirst($controller) . 'Controller';
    if (class_exists($controllerClass)) {
        $ctrl = new $controllerClass();
        if (method_exists($ctrl, $action)) {
            $ctrl->$action();
        } else {
            echo "Action non trouvée.";
        }
    } else {
        echo "Contrôleur non trouvé.";
    }
} else {
    echo "Fichier contrôleur non trouvé.";
}
