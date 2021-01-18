<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\Mail;
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
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();


            $searchEmail = $this->entityManager->getRepository(User::class)
                                               ->findOneByEmail($user->getEmail());


            if(! $searchEmail)
            {
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


                // Send mail to user
                $mail = new Mail();

                $content =  "Bonjour ". $user->getFirstname() ."<br>";
                $content .= "Bienvenue sur la premiere boutique dediee au made in France";
                $mail->send(
                    $user->getEmail(),
                    $user->getFirstname(),
                    'Bienvenue sur la Boutique Francaise',
                    $content
                );

                $notification = "Votre inscription s'est correctement deroulee. Vous pouvez des a present vous connecter a votre compte.";

            } else {

                $notification = "L'email que vous avez renseigne existe deja.";
            }
        }

        return $this->render('register/index.html.twig', [
            'register_form' => $form->createView(),
            'notification'  => $notification
        ]);
    }
}
