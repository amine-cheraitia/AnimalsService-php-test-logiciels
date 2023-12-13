<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../../src/AnimalService.php';

/**
 * * @covers invalidInputException
 * @covers \AnimalService
 *
 * @internal
 */
/*
final class AnimalServiceIntegrationTest extends TestCase
{
    private $animalService;

    public function __construct(string $name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->animalService = new AnimalService();
    }

    // test de suppression de toute les données, nécessaire pour nettoyer la bdd de tests à la fin
    public function testDeleteAll()
    {
    }


    public function testCreation()
    {
    }

    public function testSearch()
    {
    }

    public function testModify()
    {
    }

    public function testDelete()
    {
    }

}
*/

final class AnimalServiceIntegrationTest extends TestCase
{
    private $animalService;
    private $testAnimalId;



    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->animalService = new AnimalService();
    }
    public function setUp(): void
    {
        parent::setUp();
        $this->animalService = new AnimalService();

        // Créer un nouvel animal pour chaque test
        $this->testAnimalId = $this->animalService->createAnimal('44', 'Test Animal', '999');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // Supprimer l'animal créé après chaque test
        $this->animalService->deleteAnimalByNumeroIdentification('999');
    }

    public function testDeleteAll()
    {
        // Le setUp crée déjà un animal, et tearDown le supprimera
        // Assurez-vous que la méthode deleteAllAnimal fonctionne correctement
        $result = $this->animalService->deleteAllAnimal();
        $this->assertInstanceOf(PDOStatement::class, $result, 'DeleteAll should return a PDOStatement object');

        // Vérifiez que la table est vide après le deleteAll
        $emptyTable = $this->animalService->getAllAnimals();
        $this->assertEmpty($emptyTable, 'Table should be empty after deleteAll');
    }

    public function testCreation()
    {
        // Test de la création d'un nouvel animal avec un nom et un numéro d'identification valides
        $nom = 'Test Animal 55';
        $num = '456';

        try {
            $result = $this->animalService->createAnimal($nom, $num);

            // Vérification que la création réussit
            $this->assertTrue($result, 'Creation of animal should return true for valid input');

            // Vérification que l'animal existe dans la base de données
            $searchResult = $this->animalService->searchAnimal($nom);
            $this->assertCount(1, $searchResult, 'Search should return one result after creation');
            $this->assertGreaterThanOrEqual($nom, $searchResult[0]['nom'], 'Search result should have correct name');
            $this->assertGreaterThanOrEqual($num, $searchResult[0]['numeroIdentifcation'], 'Search result should have correct number');
            $this->animalService->deleteAnimalByNumeroIdentification($num);
        } catch (invalidInputException $e) {
            // Si une exception est levée, le test a échoué
            $this->fail('Exception should not be thrown for valid input');
        }
        $this->animalService->deleteAnimalByNumeroIdentification($num);
    }


    public function testSearch()
    {

        // Test de la recherche d'un animal existant
        $searchResult = $this->animalService->searchAnimal('Test Animal');
        $this->assertCount(1, $searchResult, 'Search should return one result for existing animal');
        $this->assertGreaterThanOrEqual('Test Animal', $searchResult[0]['nom'], 'Search result should have correct name');
        $this->assertGreaterThanOrEqual('123', $searchResult[0]['numeroIdentification'], 'Search result should have correct number');

        // Test de la recherche d'un animal inexistant
        $searchResult = $this->animalService->searchAnimal('Non-Existent Animal');
        $this->assertEmpty($searchResult, 'Search should return empty result for non-existent animal');
    }

    public function testModify()
    {
        // Test de la modification d'un animal existant
        $result = $this->animalService->updateAnimal($this->testAnimalId, 'Modified Animal', '789');
        $this->assertTrue($result, 'Update of animal should return true for valid input');

        // Vérification que l'animal modifié existe dans la base de données
        $searchResult = $this->animalService->searchAnimal('Modified Animal');
        $this->assertCount(1, $searchResult, 'Search should return one result after modification');
        $this->assertGreaterThanOrEqual('Modified Animal', $searchResult[0]['nom'], 'Search result should have correct name');
        $this->assertGreaterThanOrEqual('789', $searchResult[0]['numeroIdentification'], 'Search result should have correct number');
    }

    public function testDelete()
    {
        // Test de la suppression de l'animal créé par setUp
        $result = $this->animalService->deleteAnimal($this->testAnimalId);
        $this->assertTrue($result, 'Delete of animal should return true for valid input');

        // Vérification que l'animal a été supprimé de la base de données
        $searchResult = $this->animalService->searchAnimal('Modified Animal');
        $this->assertEmpty($searchResult, 'Search should return empty result after deletion');
    }
}
