<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PictureUserType;
use App\Form\ProfileUserType;
use App\Form\TownType;
use App\Form\ModifyPasswordConnectType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfileUserController extends AbstractController
{
    #[Route('/profile/user', name: 'app_profile_user')]
    public function index(): Response
    {
        return $this->render('profile_user/index.html.twig', [
            'controller_name' => 'ProfileUserController',
        ]);
    }

//    #[Route('/profile/user/new', name: 'app_profile_user_new', methods: ['GET', 'POST'])]
//    public function new(
//        Request $request,
//        UserRepository $userRepository,
//        EntityManagerInterface $entityManager
//    ): Response {
//        $user = new User();
//
//        $form = $this->createForm(ProfileUserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $newFile = $form['pictureFile']->getData();
//            if ($newFile) {
//                $user->setPictureFile($newFile);
//            }
//
//            $entityManager->persist($user);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_profile_user');
//        }
//
//        return $this->render('profile_user/edit.html.twig', [
//            'form' => $form,
//        ]);
//    }

    #[Route('/profile/user/show/{id<^[0-9]+$>}', name: 'app_profile_user_show', methods: ['GET', 'POST'])]
    public function show(
        int $id,
        User $user,
        UserRepository $userRepository,
    ): Response {

        $user = $userRepository->findOneBy(['id' => $id]);

        if (!$user) {
            throw $this->createNotFoundException(
                'Utilisateur introuvable'
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

        return $this->render('profile_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
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

            $this->addFlash('success', 'Votre image de profil a été modifié');

            return $this->redirectToRoute('app_profile_user');
        }

        return $this->render('profile_user/pictureUser.html.twig', [
            'user' => $user,
            'pictureForm' => $pictureForm,
        ]);
    }


    #[Route('/profile/user/{id}/modify-password', name: 'app_profile_user_modify_password', methods: ['GET', 'POST'])]
    public function modifyPassword(
        Request $request,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
    ): Response {

        $form = $this->createForm(ModifyPasswordConnectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe actuel soumis dans le formulaire
            $currentPassword = $form['currentPassword']->getData();

            // Vérifier si le mot de passe actuel est correct
            if (!$userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                return $this->redirectToRoute('app_modify_password', ['id' => $user->getId()]);
            }

            // Mettre à jour le mot de passe avec le nouveau mot de passe hashé
            $newPassword = $form['plainPassword']->getData();
            if ($newPassword) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $newPassword
                    )
                );
            }

            // Persister les changements et rediriger vers la déconnexion
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Mot de passe modifié avec succès!');
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('profile_user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/user/{id}/delete', name: 'app_profile_user_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        user $user,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
    ): Response {

        $tokenStorage->getToken()->getUser();


        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été supprimé');
        } else {
            $this->addFlash('error', 'Erreur lors de la suppression du profil');
        }

        return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profile/user/{id}/delete/confirm', name: 'app_profile_user_confirm_delete')]
    public function confirmDeleteProfile(User $user): Response
    {
        return $this->render('profile_user/delete.html.twig', [
            'user' => $user,
        ]);
    }
}
