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
use App\Model\InventoryManager;
use Composer\Package\Loader\ValidatingArrayLoader;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Model\ResourcesManager;

/**
 * Class animalsController
 *
 */
class AnimalsController extends AbstractController
{

    /**
     * Display animals listing
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function index(int $id)
    {
        $animalsManager = new AnimalsManager();
        $animals = $animalsManager->selectAllAnimals($id);
        return $this->twig->render('Animals/index.html.twig', [
            'animals' => $animals
        ]);
    }

    /**
     * Display animals informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */

    public function show(int $id)
    {
        $animalsManager = new AnimalsManager();
        $animals = $animalsManager->selectOneById($id);

        return $this->twig->render('Animals/show.html.twig', ['animals' => $animals]);
    }

    /**
     * Display animals edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */

    public function edit(int $id)
    {
        $animalsManager = new AnimalsManager();
        $animals = $animalsManager->selectOneById($id);
        $animals['quantity'] ++;
        $animalsManager->update($animals);
        $idUser = $_SESSION["user_id"];
        header("location: /animals/index/$idUser");
    }

    /**
     * Display animals creation page
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $animalsManager = new AnimalsManager();
            $animals = [
                'animals_id' => $_POST['animals_id'],
                'user_id' => $_POST['user_id'],
                'quantity' => $_POST['quantity'],
                'id' => $_POST['id'],
            ];
            $id = $animalsManager->insert($animals);
            header('Location:/Animals/show/' . $id);
        }

        return $this->twig->render('Animals/add.html.twig');
    }

    /**
     * Handle animals deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $animalsManager = new AnimalsManager();
        $animalsManager->delete($id);
        header('Location:/Animals/index');
    }

    public function fight(int $id)
    {
        if (!isset($_GET['result'])) {
            $objAtk = new AnimalsManager();
            $objDef = new AnimalsManager();

            $troupAtk = $objAtk->selectTroup($id);
            $tabDef = $objDef->selectTroupDef();
            $troupDef = $objDef->selectTroup($tabDef[rand(0, count($tabDef))]['user_id']);

            // SELECTIONNER ALEATOIREMENT UNE ESPECE ANIMAL
            $troupAtk = $troupAtk[rand(0, 3)];
            $troupDef = $troupDef[rand(0, 3)];
            $_SESSION['troupAtk'] = $troupAtk;
            $_SESSION['troupDef'] = $troupDef;

            return $this->twig->render('Animals/fight.html.twig', ['troupAtk' => $troupAtk,
                'troupDef' => $troupDef
            ]);
        }

        if (isset($_GET['result'])) {
            $troupAtk = $_SESSION['troupAtk'];
            $troupDef = $_SESSION['troupDef'];

            // CALCUL DE LA VIE TOTALE    var_dump($troupAtk);DE L'ATTAQUANT
            $pvAtk = $troupAtk['quantity'] * $troupAtk['health'];

            // CALCUL DE LA VIE TOTALE DU    var_dump($troupAtk);DEFENSEUR
            $pvDef = $troupDef['quantity'] * $troupDef['health'];

            // CALCUL DE LA PUISSANCE D'ATTAQUE DE L'ATTAQUANT
            $pwrAtk = $troupAtk['attack'] * $troupAtk['quantity'];

            // CALCUL DE LA PUISSANCE D'ATTAQUE DU DEFENSEUR
            $pwrDef = $troupDef['attack'] * $troupDef['quantity'];

            // CALCUL DU DEFENSEUR SUR L'ATTAQUANT
            $resultAtk = $pvAtk - $pwrDef;

            if ($resultAtk < 0) {
                $newQuantityAtk = 0;
                $lostQuantityAtk = $troupAtk['quantity'];
            } else {
                $lostQuantityAtk = round($pwrDef / $troupAtk['health']);
                $newQuantityAtk = $troupAtk['quantity'] - $lostQuantityAtk;
            }

            // CALCUL DE L'ATTAQUE SUR LA DEFENSE
            $resultDef = $pvDef - $pwrAtk;
            if ($resultDef < 0) {
                $newQuantityDef = 0;
                $lostQuantityDef = $troupDef['quantity'];
            } else {
                $lostQuantityDef = round($pwrAtk / $troupDef['health']);
                $newQuantityDef = $troupDef['quantity'] - $lostQuantityDef;
            }

            // Détermine le gagnant
            if ($resultAtk >= $resultDef) {
                $winner = $troupAtk['user_name'];
                $gain = new ResourcesManager();
                $selectGold = $gain->selectPriceAnimal($troupAtk['animals_id']);
                $quantityGold = $lostQuantityAtk * $selectGold['price'];
                $newGold = $selectGold['gold'] + $quantityGold;
                $addGold = ['quantity' => $newGold];
                $gain->updateGold($addGold);
            } else {
                $winner = $troupDef['user_name'];
                $gain = new ResourcesManager();
                $selectGold = $gain->selectPriceAnimal($troupDef['animals_id']);
                $quantityGold = $lostQuantityAtk * $selectGold['price'];
            }

            // modification pour l'attaque
            $animalsAtk = new AnimalsManager();
            $animalsDef = new AnimalsManager();
            $lineAnimalAtk = ['id' => $troupAtk['lineToModif'],
                'quantity' => $newQuantityAtk,
            ];
            $lineAnimalDef = ['id' => $troupDef['lineToModif'],
                'quantity' => $newQuantityDef,
            ];

            $animalsAtk->update($lineAnimalAtk);
            $animalsDef->update($lineAnimalDef);
            unset($_SESSION['troupAtk']);
            unset($_SESSION['troupDef']);

            return $this->twig->render('Animals/result.html.twig', [
                'troupAtk' => $troupAtk,
                'troupDef' => $troupDef,
                'lostQuantityAtk' => $lostQuantityAtk,
                'lostQuantityDef' => $lostQuantityDef,
                'winner' => $winner,
                'quantityGold' => $quantityGold
            ]);
        }
    }
}
