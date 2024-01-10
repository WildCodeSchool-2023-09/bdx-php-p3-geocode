<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PictureUserType;
use App\Form\ProfileUserType;
use App\Form\TownType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

        return $this->render('profile_user/edit.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/profile/user/show/{id<^[0-9]+$>}', name: 'app_profile_user_show', methods: ['GET', 'POST'])]
    public function show(
        int $id,
        User $user,
        UserRepository $userRepository,
    ): Response {

        $user = $userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }

        return $this->render('profile_user/showProfile.html.twig', [
        'user' => $user,
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
//        $pictureForm = $this->createForm(PictureUserType::class);

        $form->handleRequest($request);
        $townForm->handleRequest($request);
//        $pictureForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil à été modifié');

            return $this->redirectToRoute('app_profile_user');
        }

        return $this->render('profile_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
//            'pictureForm' => $pictureForm,
            'townForm' => $townForm->createView(),
        ]);
    }

    #[Route('/profile/user/{id}/picture', name: 'app_profile_user_picture', methods: ['GET', 'POST'])]
    public function modifyPicture(
        Request $request,
        User $user,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $pictureForm = $this->createForm(PictureUserType::class, $user);
        $pictureForm->handleRequest($request);

        if ($pictureForm->isSubmitted() && $pictureForm->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre image de profil à été modifié');

            return $this->redirectToRoute('app_profile_user');
        }

        return $this->render('profile_user/pictureUser.html.twig', [
            'user' => $user,
            'pictureForm' => $pictureForm,
        ]);
    }


    #[Route('/profile/user/{id}/modify-password', name: 'app_modify_password', methods: ['GET', 'POST'])]
    public function modifyPassword(
        Request $request,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
    ): Response {

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form['plainPassword']->getData();
            if ($newPassword) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $newPassword
                    )
                );
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès!');

            return $this->redirectToRoute('app_logout');
        }

        return $this->render('profile_user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
