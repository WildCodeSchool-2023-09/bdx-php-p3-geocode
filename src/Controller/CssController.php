<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CssController extends AbstractController
{
    #[Route('/css', name: 'app_css')]
    public function index(): Response
    {
        return $this->render('css/index.html.twig', [
            'controller_name' => 'CssController',
        ]);
    }

    #[Route('/css/form', name: 'app_css_form')]
    public function contact(Request $request): Response
    {
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);
        return $this->render('css/index.html.twig', [
            'controller_name' => 'CssController',
            'form' => $form,
        ]);
    }
}
