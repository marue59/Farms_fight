<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\ResourcesManager;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            $resources = new ResourcesManager();
            $resources = $resources->selectAllResourcesByUser($_SESSION["user_id"]);

            return $this->twig->render('Home/index.html.twig', ['resources' => $resources]);
        } else {
            return $this->twig->render('Home/play.html.twig');
        }
    }
    public function play()
    {
        return $this->twig->render('Home/play.html.twig');
    }
}
