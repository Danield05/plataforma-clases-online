<?php
class HomeController {
    public function index() {
        // Aquí puedes cargar datos del modelo si es necesario
        // require_once 'models/UserModel.php';
        // $userModel = new UserModel();
        // $data = $userModel->getUsers();

        // Cargar la vista correspondiente
        require_once 'views/home.php';
    }

    // Puedes agregar más acciones aquí, como about(), contact(), etc.
    public function about() {
        require_once 'views/about.php';
    }
}
?>