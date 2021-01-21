<?php
namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class ResetPasswordController
 * @package App\Controller
*/
class ResetPasswordController extends AbstractController
{


    /**
     * @var EntityManagerInterface
    */
    private $em;


    /**
     * ResetPasswordController constructor.
     * @param EntityManagerInterface $em
    */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    /**
     * @Route("/mot-de-passe-oublie", name="reset_password")
    */
    public function index(Request $request): Response
    {
        // Si l' utilisateur est connecte on le redirige a la home
        if($this->getUser())
        {
            return $this->redirectToRoute('home');
        }


        if($email = $request->get('email'))
        {
            $user = $this->em->getRepository(User::class)
                             ->findOneByEmail($email);

            if($user)
            {
                  // 1 : Enregistrer en base la demande de reset_passord avec
                  // user, token, createdAt.
                  $resetPassword = new ResetPassword();
                  $resetPassword->setUser($user);
                  $resetPassword->setToken(uniqid());
                  $resetPassword->setCreatedAt(new \DateTime());

                  $this->em->persist($resetPassword);
                  $this->em->flush();

                  // 2 : Envoyer un email a l' utilisateur avec un lien lui permettant de mettre ajour son mot de passe

                  $url = $this->generateUrl('update_password', [
                      'token' => $resetPassword->getToken()
                  ]);

                  $content = "Bonjour ". $user->getFirstname().
                             "<br/>Vous avez demande a reinitialiser votre mot de passe sur le site la Boutique Francaise<br/><br/>";
                  $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='". $url ."'>mettre ajour votre mot de passe.</a>";

                  $mail = new Mail();
                  $mail->send(
                      $user->getEmail(),
              $user->getFirstname().' '. $user->getLastname(),
                      'Reinitialiser votre mot de passe sur la boutique francaise',
                        $content
                  );

                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procedure pour reinitialiser votre mot de passe.');
            }else{

                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }



    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
    */
    public function updatePassword(Request $request, $token, UserPasswordEncoderInterface $encoder)
    {
        $resetPassword = $this->em->getRepository(ResetPassword::class)
                                  ->findOneByToken($token);

        if(! $resetPassword)
        {
            return $this->redirectToRoute('reset_password');
        }


        // Verifier si le createdAt = now - 3h
        $now = new \DateTime();
        $sup = $resetPassword->getCreatedAt()->modify('+ 3 hour');

        // Verife si le token a expire
        if($now > $sup)
        {
            $this->addFlash('notice', 'Votre demande de mot de passe a expire. Merci de la renouveller.');

            return $this->redirectToRoute('reset_password');
        }

        # Process de modification de mot de passe.

        // Rendre une vue avec mot de passe et confirmez votre mot de passe.

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
             /** @var string (plain text) $newPassword */
             $newPassword = $form->get('new_password')->getData();

            // Encodage des mots de passe

             $user = $resetPassword->getUser();
             $passwordHashed = $encoder->encodePassword($user, $newPassword);
             $user->setPassword($passwordHashed);


             // Flush en base de donnees
             $this->em->flush();


             // Redirection de l' utilisateur vers la page de connexion.
             $this->addFlash('notice', 'Votre mot de passe a bien ete mise a jour.');

             return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'updatePasswordForm' => $form->createView()
        ]);
    }
}
