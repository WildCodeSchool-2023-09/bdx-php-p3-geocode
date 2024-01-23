<?php

namespace App\Controller;

use App\Entity\Terminal;
use App\Form\TerminalType;
use App\Repository\TerminalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/terminal')]
class AdminTerminalController extends AbstractController
{
    #[Route('/', name: 'app_admin_terminal_index', methods: ['GET'])]
    public function index(TerminalRepository $terminalRepository): Response
    {
        $nbTerminalByPage = $this->getParameter('app.nbTerminalByPage');
        $totalPages = intval(ceil($terminalRepository->getNbTerminals() / $nbTerminalByPage));
        return $this->render('admin_terminal/index.html.twig', [
            'nbPages' => $totalPages,
        ]);
    }

    #[Route('/page/{page}', name: 'app_admin_terminal_page', methods: ['GET'])]
    public function displayPage(int $page, TerminalRepository $terminalRepository): Response
    {
        $nbTerminalByPage = $this->getParameter('app.nbTerminalByPage');
        $paginatedResult = $terminalRepository->getTerminals($page, $this->getParameter('app.nbTerminalByPage'));
        $nbPages = intval(ceil($terminalRepository->getNbTerminals() / $nbTerminalByPage));
        return $this->render('admin_terminal/page.html.twig', [
            'terminals' => $paginatedResult,
            'nbPages' => $nbPages,
            'page' => $page,
        ]);
    }

    #[Route('/new', name: 'app_admin_terminal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $terminal = new Terminal();
        $form = $this->createForm(TerminalType::class, $terminal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($terminal);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_terminal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_terminal/new.html.twig', [
            'terminal' => $terminal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_terminal_show', methods: ['GET'])]
    public function show(Terminal $terminal): Response
    {
        return $this->render('admin_terminal/show.html.twig', [
            'terminal' => $terminal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_terminal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Terminal $terminal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TerminalType::class, $terminal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_terminal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_terminal/edit.html.twig', [
            'terminal' => $terminal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_terminal_delete', methods: ['POST'])]
    public function delete(Request $request, Terminal $terminal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $terminal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($terminal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_terminal_index', [], Response::HTTP_SEE_OTHER);
    }
}
