<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Expense;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $expense = new Expense();
            $expense->setCategory('Category ' . $i);
            $expense->setAmount(mt_rand(10, 1000));
            $expense->setDate(new \DateTime());
            $expense->setTenantId(1);

            $manager->persist($expense);
        }

        $manager->flush();
    }
}

