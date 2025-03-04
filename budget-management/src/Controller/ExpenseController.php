<?php

// src/Controller/ExpenseController.php
namespace App\Controller;

use App\Entity\Expense;
use App\Form\ExpenseType;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/expense")
 */
class ExpenseController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="expense_index", methods={"GET"})
     */
    public function index(ExpenseRepository $expenseRepository): Response
    {
        $expenses = $expenseRepository->findAll();
        $totalAmount = array_reduce($expenses, function($carry, $expense) {
            return $carry + $expense->getAmount();
        }, 0);

        return $this->render('expense/index.html.twig', [
            'expenses' => $expenses,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * @Route("/new", name="expense_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $expense = new Expense();
        $form = $this->createForm(ExpenseType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser();
            if ($user) {
                $expense->setTenantId($user->getTenantId());
            } else {
                // Jeśli użytkownik nie jest zalogowany, ustaw wartość domyślną
                $expense->setTenantId(1);
            }

            $entityManager->persist($expense);
            $entityManager->flush();

            return $this->redirectToRoute('expense_index');
        }

        return $this->render('expense/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="expense_delete", methods={"POST"})
     */
    public function delete(Request $request, Expense $expense, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$expense->getId(), $request->request->get('_token'))) {
            $entityManager->remove($expense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('expense_index');
    }

    /**
     * @Route("/api/expenses", name="api_expense_index", methods={"GET"})
     */
    public function apiIndex(ExpenseRepository $expenseRepository): Response
    {
        $expenses = $expenseRepository->findAll();
        return $this->json($expenses);
    }

    /**
     * @Route("/api/expenses", name="api_expense_new", methods={"POST"})
     */
    public function apiNew(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $expense = new Expense();
        $expense->setCategory($data['category']);
        $expense->setAmount($data['amount']);
        $expense->setDate(new \DateTime($data['date']));

        $user = $this->security->getUser();
        if ($user) {
            $expense->setTenantId($user->getTenantId());
        } else {
            // Jeśli użytkownik nie jest zalogowany, ustaw wartość domyślną
            $expense->setTenantId(1);
        }

        $entityManager->persist($expense);
        $entityManager->flush();

        return $this->json(['status' => 'Expense added'], Response::HTTP_CREATED);
    }
}
