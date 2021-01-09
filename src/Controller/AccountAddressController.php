<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AccountAddressController
 * @package App\Controller
*/
class AccountAddressController extends AbstractController
{

    /**
     * @var EntityManagerInterface
    */
    private $em;


    /**
     * RegisterController constructor.
     * @param EntityManagerInterface $em
    */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/compte/adresses", name="account_address")
    */
    public function index(): Response
    {
        /*
         dd($this->getUser());
         dd($this->getUser()->getAddresses());
         twig: app.user.addresses
        */
        return $this->render('account/address.html.twig');
    }


    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address_add")
     * @param Request $request
     * @return Response
    */
    public function add(Request $request): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $address->setUser($this->getUser());
            /* dd($address); */

            $this->em->persist($address);
            $this->em->flush();

            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'address_form' => $form->createView()
        ]);
    }


    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_address_edit")
     * @param Request $request
     * @return Response
    */
    public function edit(Request $request, $id): Response
    {
        $address = $this->em->getRepository(Address::class)
                            ->findOneById($id);

        // On verifie si l'adresse existe
        // oubien si address n'appartient pas a mon utilisateur
        // cad si l'utisateur de cette addresse est bel et bien l'utilisateur connecte
        if(! $address || $address->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('account_address');
        }

        // On verifie si l'adresse
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();

            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'address_form' => $form->createView()
        ]);
    }


    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_address_delete")
     * @param $id
     * @return Response
     */
    public function delete($id): Response
    {
        $address = $this->em->getRepository(Address::class)
                            ->findOneById($id);

        if($address && $address->getUser() == $this->getUser())
        {
            $this->em->remove($address);
            $this->em->flush();
        }

        return $this->redirectToRoute('account_address');
    }

}
