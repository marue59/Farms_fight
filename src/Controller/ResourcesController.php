<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ResourcesManager;

/**
 * Class ResourcesController
 *
 */
class ResourcesController extends AbstractController
{


    /**
     * Display resources listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $resources = new ResourcesManager();
        $resources = $resources->selectAllResourcesByUser($_SESSION["user_id"]);
        return $this->twig->
        render("resources/index.html.twig", ['resources' => $resources]);
    }


    /**
     * Display resources informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $resourcesManager = new ResourcesManager();
        $resources = $resourcesManager->selectOneById($id);

        return $this->twig->render('resources/show.html.twig', ['resources' => $resources]);
    }


    /**
     * Display resource edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $resourcesManager = new ResourcesManager();
        $resources = $resourcesManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resources['title'] = $_POST['title'];
            $resourcesManager->update($resources);
        }

        return $this->twig->render('resources/edit.html.twig', ['resources' => $resources]);
    }


    /**
     * Display resource creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resourcesManager = new ResourcesManager();
            $resources = [
                'id' => $_POST['id'],
                'user_id' => $_POST['user_id'],
                'resource_id' => $_POST['resource_id'],
                'quantity' => $_POST['quantity'],
            ];
            $id = $resourcesManager->insert($resources);
            header('Location:/resources/show/' . $id);
        }

        return $this->twig->render('resources/add.html.twig');
    }


    /**
     * Handle resources deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $resourcesManager = new ResourcesManager();
        $resourcesManager->delete($id);
        header('Location:/resources/index');
    }
}
