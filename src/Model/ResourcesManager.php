<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class ResourcesManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'user_resources';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $resources
     * @return int
     */
    public function insert(array $resources): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
        ('id', 'user_id', 'resource_id', 'quantity')
        VALUES (:id, :user_id, :resource_id, :quantity)");
        $statement->bindValue('id', $resources['id'], \PDO::PARAM_INT);
        $statement->bindValue('user_id', $resources['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('resource_id', $resources['resource_id'], \PDO::PARAM_INT);
        $statement->bindValue('quantity', $resources['quantity'], \PDO::PARAM_INT);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }

    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $resources
     * @return bool
     */
    public function update(array $resources):bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET `quantity = :quantity` WHERE id=:id");
        $statement->bindValue('quantity', $resources['quantity'], \PDO::PARAM_INT);

        return $statement->execute();
    }


    public function updateGold(array $resources):bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET 
                 quantity = :quantity  WHERE user_id = :user_id AND resource_id = 3");
        $statement->bindValue(':quantity', $resources['quantity'], \PDO::PARAM_INT);
        $statement->bindValue(':user_id', $_SESSION['user_id'], \PDO::PARAM_INT);

        return $statement->execute();
    }
    /**
     * Get 3 rows from database by user_id.
     *
     * @param int $id
     * @return array
     */
    public function selectPriceAnimal(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT price, user_resources.id, user_resources.quantity AS gold
                                                FROM $this->table                                     
                                                JOIN user_animals ON user_animals.user_id= $this->table.user_id
                                                JOIN animals ON animals.id = $this->table.user_id
                                                WHERE animals.id = :id AND user_resources.resource_id=3");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectAllResourcesByUser(int $userid)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT name, quantity, last_update
            FROM $this->table
            JOIN resources ON resources.id = $this->table.resource_id
            WHERE user_id=:user_id
            ORDER BY resource_id");
        $statement->bindValue('user_id', $userid, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function insertResources(array $resources)
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
        (user_id, resource_id, quantity, last_update) 
        VALUES (:user_id, :resource_id, :quantity, :last_update)");
        $statement->bindValue('user_id', $resources['user_id'], \PDO::PARAM_INT);
        $statement->bindValue('resource_id', $resources['resource_id'], \PDO::PARAM_INT);
        $statement->bindValue('quantity', $resources['quantity'], \PDO::PARAM_INT);
        $statement->bindValue('last_update', $resources['last_update'], \PDO::PARAM_INT);

        $statement->execute();
    }
}
