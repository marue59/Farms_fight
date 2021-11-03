<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 15:38
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ResourcesManager;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 *
 */
abstract class AbstractController
{
    /**
     * @var Environment
     */
    protected $twig;


    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(APP_VIEW_PATH);
        $this->twig = new Environment(
            $loader,
            [
                'cache' => !APP_DEV,
                'debug' => APP_DEV,
            ]
        );

        $this->twig->addExtension(new DebugExtension());
        if (isset($_SESSION['user_id'])) {
            $resourcesManager = new ResourcesManager();
            $resources = $resourcesManager->selectAllResourcesByUser($_SESSION["user_id"]);
            $number = 0;
            foreach ($resources as $resource) {
                if ($resource['name'] != 'gold') {
                    $gainPerDay = 10000;
                    $automaticGain = $gainPerDay / 24 / 60 / 60;
                    $seconds = time() - $resource['last_update'];
                    $resource['quantity'] = floor($resource['quantity'] + $seconds * $automaticGain);
                    $resources[$number]['quantity'] = $resource['quantity'];
                }
                $number++;
            }
                $this->twig->addGlobal('session', $_SESSION);
                $this->twig->addGlobal('waterQuantity', $resources[0]['quantity']);
                $this->twig->addGlobal('cerealQuantity', $resources[1]['quantity']);
                $this->twig->addGlobal('goldQuantity', $resources[2]['quantity']);
        }
    }
}
