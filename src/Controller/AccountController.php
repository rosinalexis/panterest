<?php

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account', methods: ["GET"])]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/account/edit', name: 'app_account_edit', methods: ["GET","PUT"])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form= $this->createForm(UserFormType::class,$user,['method' => 'PUT']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success','Account update successfully!');

            return $this->redirectToRoute('app_account');
        }
        return $this->render('account/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/changer-password', name: 'app_account_change_password', methods: ["GET","PUT"])]
    public function changePassword(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class,$user,['method' => 'PUT']);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword($user,$form['plainPassword']->getData())
            );

            $em->flush();

            $this->addFlash('success','Password updated successfully !');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/change_password.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
