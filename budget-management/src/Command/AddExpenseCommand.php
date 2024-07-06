<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Expense;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddExpenseCommand extends Command
{
    protected static $defaultName = 'app:add-expense';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Adds a new expense')
            ->setHelp('This command allows you to create a new expense');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $expense = new Expense();
        $expense->setCategory('Sample Category');
        $expense->setAmount(123.45);
        $expense->setDate(new \DateTime());

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        $output->writeln('Expense added successfully.');
        return Command::SUCCESS;
    }
}
