<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require 'invalidInputException.php';

class AnimalService
{
    public $pdo;

    /**
     * AnimalService constructor.
     * Initialise la BDD
     */
    public function __construct()
    {
        $this->pdo = new PDO('sqlite:' . __DIR__ . '/animals.sqlite');

        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    /*  */
    /**
     * permet de de renvoyer les détails d'un animal
     * @param $id l'identifiant du animal recherché
     * @return mixed le retour de la requete SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function getAnimal($id)
    {
        if (empty($id)) {
            throw new invalidInputException("l'id doit être renseigné");
        }
        if (!is_numeric($id) || $id < 0) {
            throw new invalidInputException("l'id doit être un entier non nul");
        }
        $req = $this->pdo->query('SELECT * from animal where id =' . $id);

        $row = $req->fetchAll();

        // si req ok (!false)
        if ($req) {
            // on renvoie le 1er et seul élément du tableau de résultats
            return $row[0];
        }
    }

    /**
     * Effectue une recherche de animal sur nom ou prénom
     * @param $search le critère de recherche
     * @return array le retour de la requete SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function searchAnimal($search)
    {
        if (empty($search)) {
            throw new invalidInputException('search doit être renseigné');
        }
        if (!is_string($search)) {
            throw new invalidInputException('search doit être une chaine de caractères');
        }
        $req = "SELECT * from animal where nom like '%" . $search . "%' or numeroIdentifcation like '%" . $search . "%'";

        $res = $this->pdo->query($req);

        $row = $res->fetchAll();

        // si req ok (!false)
        if ($res) {
            return $row;
        }
    }

    /**
     * Récupère tous les animals en BDD
     * @return array le retour de la requete SQL
     */
    public function getAllAnimals()
    {
        $req = $this->pdo->query('SELECT * from animal');

        $row = $req->fetchAll();

        // si req ok (!false)
        if ($req) {
            return $row;
        }
    }

    /**
     * Créé un nouveau animal
     * @param $nom le nom du animal
     * @param $numeroIdentification le prénom du animal
     * @return bool true si ok, false si erreur SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function createAnimal($nom, $numeroIdentification)
    {
        if (empty($nom) || !is_string($nom)) {
            throw new invalidInputException('le nom doit être renseigné');
        }
        if (empty($numeroIdentification) && !is_string($numeroIdentification)) {
            throw new invalidInputException('le numeroIdentification doit être renseigné');
        }
        $stmt = $this->pdo->prepare('INSERT INTO animal (nom, numeroIdentifcation) VALUES (:nom, :numeroIdentification)');

        return $stmt->execute([
            'nom' => $nom,
            'numeroIdentification' => $numeroIdentification,
        ]);
    }

    /**
     * Créé un nouveau animal
     * @param $id l'id du animal à modifier
     * @param $nom le nom du animal
     * @param $numeroIdentification le prénom du animal
     * @return bool true si ok, false si erreur SQL
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function updateAnimal($id, $nom, $numeroIdentification)
    {
        if (empty($nom) && !is_string($nom)) {
            throw new invalidInputException('le nom  doit être renseigné');
        }

        if (empty($id)) {
            throw new invalidInputException("l'id doit être renseigné");
        }
        if (!is_numeric($id) || $id < 0) {
            throw new invalidInputException("l'id doit être un entier non nul");
        }
        if (empty($numeroIdentification) && !is_string($numeroIdentification)) {
            throw new invalidInputException('le numeroIdentification doit être renseigné');
        }
        $stmt = $this->pdo->prepare('UPDATE animal SET nom=:nom, numeroIdentifcation=:numeroIdentification where id=:id');

        return $stmt->execute([
            'nom' => $nom,
            'numeroIdentification' => $numeroIdentification,
            'id' => $id,
        ]);
    }

    /**
     * Supprime un animal par son id
     * @param $id l'id du animal à supprimer
     * @return bool true si SQL ok, false si non
     * @throws invalidInputException en cas d'erreur de paramètre
     */
    public function deleteAnimal($id)
    {
        if (null === $id) {
            throw new invalidInputException("l'id doit être renseigné");
        }
        if (!is_numeric($id) || $id < 0) {
            throw new invalidInputException("l'id doit être un entier non nul");
        }
        $stmt = $this->pdo->prepare('DELETE from animal where id=:id');

        return $stmt->execute([
            'id' => $id,
        ]);
    }

    /**
     * Supprime tous les animals
     * @return false|PDOStatement
     */
    public function deleteAllAnimal()
    {
        return $this->pdo->query('DELETE from animal');
    }
}
