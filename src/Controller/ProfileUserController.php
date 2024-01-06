<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileUserType;
use App\Form\TownType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

class ProfileUserController extends AbstractController
{
    #[Route('/profile/user', name: 'app_profile_user')]
    public function index(): Response
    {
        return $this->render('profile_user/index.html.twig', [
            'controller_name' => 'ProfileUserController',
        ]);
    }

//    #[IsGranted('ROLE_ADMIN')]
    #[Route('/profile/user/new', name: 'app_profile_user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();

        $form = $this->createForm(ProfileUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newFile = $form['pictureFile']->getData();
            if ($newFile) {
                $user->setPictureFile($newFile);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_user');
        }

        return $this->render('profile_user/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profile/user/{id}/edit', name: 'app_profile_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {

//        if (($this->getUser() !== $user->getOwner()) && (!$this->isGranted('ROLE_ADMIN'))) {
//            // If not the owner, throws a 403 Access Denied exception
//            throw $this->createAccessDeniedException('Seul le propriétaire peut modifier la série!');
//        }
        $form = $this->createForm(ProfileUserType::class, $user);
        $townForm = $this->createForm(TownType::class);
        $form->handleRequest($request);
        $townForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'Votre profil à été modifié');

            return $this->redirectToRoute('app_profile_user');
        }

        return $this->render('profile_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'townForm' => $townForm->createView(),
        ]);
    }
}
