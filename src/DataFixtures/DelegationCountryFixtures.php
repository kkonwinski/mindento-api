<?php

namespace App\DataFixtures;

use App\Entity\DelegationCountry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DelegationCountryFixtures extends Fixture
{
    protected $faker;

    public function load(ObjectManager $manager)
    {
        $this->faker = new Factory();

        $delegationCountry = new DelegationCountry();
        $delegationCountry->setAmountDoe(10);
        $delegationCountry->setCountry("PL");
        $delegationCountry->setCurrency("PLN");

        $manager->persist($delegationCountry);

        $delegationCountry = new DelegationCountry();
        $delegationCountry->setAmountDoe(50);
        $delegationCountry->setCountry("DE");
        $delegationCountry->setCurrency("PLN");

        $manager->persist($delegationCountry);

        $delegationCountry = new DelegationCountry();
        $delegationCountry->setAmountDoe(75);
        $delegationCountry->setCountry("GB");
        $delegationCountry->setCurrency("PLN");


        $manager->persist($delegationCountry);

        $manager->flush();
    }
}
