<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmployeeFixtures extends Fixture
{
  //  protected $faker;

    public function load(ObjectManager $manager)
    {
//        $this->faker = Factory::create();
//        for ($i = 0; $i < 20; $i++) {
//            $employee = new Employee();
//            $employee->setFirstName($this->faker->firstName);
//            $employee->setLastName($this->faker->lastName);
//            $manager->persist($employee);
//        }
//        $manager->flush();
    }
}
