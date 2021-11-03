<?php
/**
 * Created by US
 * User: US
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 * classe qui gere l'ensemble de nos animaux
 *
 */
class AnimalsManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'user_animals';

    /**
     *  Initializes this class.
     */

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /**
     * @param array $animals
     * @return int
     */

    public function insert(array $animals): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
        ('id', 'user_id', 'animals_id', 'quantity') 
        VALUES (:id, :user_id, :animals_id, :quantity)");
        $statement->bindValue('id', $animals['id'], \PDO::PARAM_INT);
        $statement->bindValue('user_id', $animals['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('animals_id', $animals['animals_id'], \PDO::PARAM_INT);
        $statement->bindValue('quantity', $animals['quantity'], \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('user_id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @param array $animals
     * @return bool
     */
    public function update(array $animals): bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET 
                 quantity = :quantity  WHERE id = :id");
        $statement->bindValue(':id', $animals['id'], \PDO::PARAM_INT);
        $statement->bindValue(':quantity', $animals['quantity'], \PDO::PARAM_INT);

        return $statement->execute();
    }

// Selection des animaux par utilisateur ainsi que le nom de l'animal
    public function selectAllAnimals($id)
    {
        $selectAllAnimal = $this->pdo->prepare("SELECT user_animals.id, user.user_name, animals.species,
                                               animals.id AS animalId, quantity, health, attack
                                               FROM $this->table
                                               JOIN animals ON animals.id = $this->table.animals_id
                                               JOIN user ON user.id = $this->table.user_id
                                               WHERE user_id = :id");
        $selectAllAnimal->bindValue('id', $id, \PDO::PARAM_INT);
        $selectAllAnimal->execute();
        return $selectAllAnimal->fetchAll();
    }

    public function selectTroup($id)
    {
        $selectTroup = $this->pdo->prepare("SELECT $this->table.id AS lineToModif, species, health, attack,
                                               user_id, animals_id, quantity, user.user_name
                                               FROM $this->table
                                               JOIN animals ON animals.id = $this->table.animals_id
                                               JOIN user ON user.id = $this->table.user_id
                                               WHERE user_id = :id");
        $selectTroup->bindValue('id', $id, \PDO::PARAM_INT);
        $selectTroup->execute();
        return $selectTroup->fetchAll();
    }

    public function selectTroupDef()
    {
        $selectTroup = $this->pdo->prepare("SELECT $this->table.id AS lineToModif, species, health, attack,
                                               user_id, animals_id, quantity, user.user_name
                                               FROM $this->table
                                               JOIN animals ON animals.id = $this->table.animals_id
                                               JOIN user ON user.id = $this->table.user_id
                                               WHERE NOT $this->table.user_id = :session_id");
        $selectTroup->bindValue('session_id', $_SESSION['user_id'], \PDO::PARAM_INT);
        $selectTroup->execute();
        return $selectTroup->fetchAll();
    }

    public function insertAnimals(array $animals)
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
        (user_id, animals_id, quantity) 
        VALUES (:user_id, :animals_id, :quantity)");
        $statement->bindValue('user_id', $animals['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('animals_id', $animals['animals_id'], \PDO::PARAM_INT);
        $statement->bindValue('quantity', $animals['quantity'], \PDO::PARAM_INT);
        $statement->execute();
    }
}
