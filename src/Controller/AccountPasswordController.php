<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     * RegisterController constructor.
     * @param EntityManagerInterface $entityManager
    */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     *
     * account/changePassword
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
    */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
             $oldPassword = $form->get('old_password')->getData();

             // si $user->getPassword() [encoded password] === $oldPassword
             if($encoder->isPasswordValid($user, $oldPassword))
             {
                  $newPassword = $form->get('new_password')->getData();
                  $encodedPassword = $encoder->encodePassword($user, $newPassword);

                  $user->setPassword($encodedPassword);

                  $this->entityManager->flush();
                  $notification = "Votre mot de passe a bien ete mise a jour.";
             } else{
                 $notification = "Votre mot de passe actuel n'est pas le bon";
             }
        }

        return $this->render('account/password.html.twig', [
            'change_password_form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}