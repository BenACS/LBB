<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Adress;
use App\Form\RegistrationType;
use App\Form\RegistrationTypeStepTwoType;

use App\Service\Header\HeaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Doctrine\Common\Persistence\ObjectManager;

class AuthentificationController extends AbstractController
{
    /**
     * @Route("/authentification", name="authentification")
     */
    public function index(HeaderService $header, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = new Account();
        $userAdress = new Adress();
        
        $form = $this->createForm(RegistrationType::class, $user);
        $formStepTwo = $this->createForm(RegistrationTypeStepTwoType::class, $userAdress);

        $formStepTwo->handleRequest($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $manager->persist($user);
            $manager->flush();
        }
        if ($formStepTwo->isSubmitted() && $formStepTwo->isValid() ) {
            $manager->persist($userAdress);
            $manager->flush();
        }

        return $this->render('authentification/index.html.twig', [
            'controller_name' => 'AuthentificationController',
            'header' => $header,
            'form' => $form->createView(),
            'form2' => $formStepTwo->createView()
        ]);
    }
}
