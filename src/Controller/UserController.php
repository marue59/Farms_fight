<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\AnimalsManager;
use App\Model\ResourcesManager;
use App\Model\UserManager;

/**
 * Class UserController
 *
 */
class UserController extends AbstractController
{

    /**
     * Display user listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $userManager = new UserManager();
        $users = $userManager->selectAll();
        $resources = new ResourcesManager();
        $resources = $resources->selectAllResourcesByUser($_SESSION["user_id"]);
        return $this->twig->render('user/index.html.twig', ['users' => $users, 'resources' => $resources]);
    }

    /**
     * Display user informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        return $this->twig->render('user/show.html.twig', ['user' => $user]);
    }

    /**
     * Display user edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user['user_name'] = $_POST['user_name'];
            $user['password'] = $_POST['password'];
            $user['email'] = $_POST['email'];
            $user['farm'] = $_POST['farm'];
            $user['current_level'] = $_POST['current_level'];
            $userManager->update($user);
        }

        return $this->twig->render('user/edit.html.twig', ['user' => $user]);
    }

    /**
     * Display user creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userPassword = trim(htmlspecialchars($_POST['password']));
            $safePassword = password_hash($userPassword, PASSWORD_DEFAULT);
            $userManager = new UserManager();
            $user = [
                'user_name' => $_POST['user_name'],
                'password' => $safePassword,
                'email' => $_POST['email'],
                'farm' => $_POST['farm'],
                'current_level' => $_POST['current_level'],
            ];
            // insère les données
            $userManager->insert($user);
            // va chercher le nouvel ID généré
            $newId = $userManager->selectLastId();
            $newId = $newId['id'];

            // Insère 4 lignes animaux dans la table user_animals
            $animalManager = new AnimalsManager();
            $animals1 = [
                'user_id' => $newId,
                'animals_id' => 1,
                'quantity' => 500,
            ];
            $animals2 = [
                'user_id' => $newId,
                'animals_id' => 2,
                'quantity' => 300,
            ];
            $animals3 = [
                'user_id' => $newId,
                'animals_id' => 3,
                'quantity' => 200,
            ];
            $animals4 = [
                'user_id' => $newId,
                'animals_id' => 4,
                'quantity' => 100,
            ];

            $animalManager->insertAnimals($animals1);
            $animalManager->insertAnimals($animals2);
            $animalManager->insertAnimals($animals3);
            $animalManager->insertAnimals($animals4);

            // Insère 3 lignes ressources dans la table user_resources
            $resourceManager = new ResourcesManager();
            $resources1 = [
                'user_id' => $newId,
                'resource_id' => 1,
                'quantity' => 0,
                'last_update' => time()
            ];
            $resources2 = [
                'user_id' => $newId,
                'resource_id' => 2,
                'quantity' => 26,
                'last_update' => time()
            ];
            $resources3 = [
                'user_id' => $newId,
                'resource_id' => 3,
                'quantity' => 500,
                'last_update' => time()
            ];

            $resourceManager->insertResources($resources1);
            $resourceManager->insertResources($resources2);
            $resourceManager->insertResources($resources3);
            header('Location:/user/connect/');
        }

        return $this->twig->render('user/add.html.twig');
    }

    public function connect()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = trim(htmlspecialchars($_POST['password']));

            $userManager = new UserManager();
            $user = $userManager->selectOneByUser($email);

            if (password_verify($password, $user['password'])) {
                $_SESSION["user_name"] = $user['user_name'];
                $_SESSION["user_id"] = $user['id'];
                header('Location:/Home/index/' . $_SESSION['user_id']);
            } else {
                $mdp = 1;
                return $this->twig->render('user/connect.html.twig', ['mdp' => $mdp]);
            }
        }
        return $this->twig->render('user/connect.html.twig');
    }

    /**
     * Handle user deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $userManager = new UserManager();
        $userManager->delete($id);
        header('Location:/user/index');
    }

    public function logout()
    {
        session_destroy();
        header('Location:/user/connect/');
    }
}
