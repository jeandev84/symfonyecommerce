<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class RegisterController
 * @package App\Controller
*/
class RegisterController extends AbstractController
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
     * @Route("/inscription", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
    */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $plainPassword = $user->getPassword();
            $encodedPassword = $encoder->encodePassword($user, $plainPassword);

            $user->setPassword($encodedPassword);

            /*
             dd($user);
             $em = $this->getDoctrine()->getManager();
             $em->persist($user);
             $em->flush();
            */
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
