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
class UserManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'user';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $user
     * @return int
     */
    public function insert(array $user): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table 
            (user_name, password, email, farm, current_level) 
            VALUES (:user_name, :password, :email, :farm, :current_level)");
        $statement->bindValue('user_name', $user['user_name'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('farm', $user['farm'], \PDO::PARAM_INT);
        $statement->bindValue('current_level', $user['current_level'], \PDO::PARAM_INT);

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
     * @param array $user
     * @return bool
     */
    public function update(array $user): bool
    {

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table SET 
            `user_name` = :user_name,`password` = :password,`email` = :email,`farm` = :farm,
            `current_level` = :current_level WHERE id=:id");
        $statement->bindValue('id', $user['id'], \PDO::PARAM_INT);
        $statement->bindValue('user_name', $user['user_name'], \PDO::PARAM_STR);
        $statement->bindValue('password', $user['password'], \PDO::PARAM_STR);
        $statement->bindValue('email', $user['email'], \PDO::PARAM_STR);
        $statement->bindValue('farm', $user['farm'], \PDO::PARAM_INT);
        $statement->bindValue('current_level', $user['current_level'], \PDO::PARAM_INT);
        return $statement->execute();
    }

    public function selectOneByUser(string $email)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE email=:email");
        $statement->bindValue('email', $email, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    public function selectLastId()
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT id FROM $this->table ORDER BY id DESC LIMIT 1");
        $statement->execute();

        return $statement->fetch();
    }
}
