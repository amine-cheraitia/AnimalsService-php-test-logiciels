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
final class AnimalServiceUnitTest extends TestCase {
    private $animalService;

    public function __construct(string $name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->animalService = new AnimalService();
    }


    public function testCreationAnimalWithoutAnyText() {
    }

    public function testCreationAnimalWithoutName() {
    }

    public function testCreationAnimalWithoutNumber() {
    }

    public function testSearchAnimalWithNumber() {
    }

    public function testModifyAnimalWithInvalidId() {
    }

    public function testDeleteAnimalWithTextAsId() {
    }

}*/
final class AnimalServiceUnitTest extends TestCase
{
    private $animalService;

    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->animalService = new AnimalService();
    }

    public function testCreationAnimalWithoutAnyText()
    {
        $this->expectException(invalidInputException::class);
        $this->animalService->createAnimal('', '');
    }

    public function testCreationAnimalWithoutName()
    {
        $this->expectException(invalidInputException::class);
        $this->animalService->createAnimal('', '123');
    }

    public function testCreationAnimalWithoutNumber()
    {
        $this->expectException(invalidInputException::class);
        $this->animalService->createAnimal('Test Animal', '');
    }

    public function testSearchAnimalWithNumber()
    {
        $this->expectException(invalidInputException::class);
        $this->animalService->searchAnimal(123);
    }

    public function testModifyAnimalWithInvalidId()
    {
        $this->expectException(invalidInputException::class);
        $this->animalService->updateAnimal('', 'New Name', '456');
    }

    public function testDeleteAnimalWithTextAsId()
    {
        $this->expectException(invalidInputException::class);
        $this->animalService->deleteAnimal('InvalidId');
    }

    public function testCreationAnimalWithValidInput()
    {
        $result = $this->animalService->createAnimal('Test Animal', '123');
        $this->assertTrue($result, 'Creation of animal should return true for valid input');
    }

    public function testSearchAnimalWithValidInput()
    {
        $result = $this->animalService->searchAnimal('Test');
        $this->assertIsArray($result, 'Search should return an array for valid input');
    }

    // Add more tests as needed for other methods
}
