<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\Account;
use App\Form\AdressType;
use App\Form\ProfileType;
use App\Form\newPasswordFormType;
use App\Repository\AccountRepository;
use App\Service\Header\HeaderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    private $session;
    private $security;

    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }

    /**
     * @Route("/profil", name="profile")
     */
    public function index(HeaderService $header)
    {
        $user = $this->getUser();
        return $this->render('profil/index.html.twig', [
            'header' => $header,
            'user' => $user
        ]);
    }
    /**
     * @Route("/profil/editProfile", name="editProfile")
     */
    public function editProfile(HeaderService $header, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $user->setEmail($request->request->get('userEmail'));
        if ($request->request->get('userNewsletter') === 'on') {
            $user->setNewsletter(true);
        } else {
            $user->setNewsletter(false);
        }

        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute("success");
    }

    /**
     * @Route("/profil/editPassword", name="editPassword")
     */
    public function editPassword(HeaderService $header, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $manager = $this->getDoctrine()->getManager();
        $msg = '';
        $user = $this->getUser();

        $oldPass = $request->request->get('oldPassword');
        if (isset($oldPass)) {
            if ($encoder->isPasswordValid($user, $oldPass)) {
                return $this->redirectToRoute('newPassword');
            } else {
                $msg = "Your password doesn't match";
            }
        }
        return $this->render('profil/password.html.twig', [
            'header' => $header,
            'user' => $user,
            'alert' => $msg
        ]);
    }

    /**
     * @Route("/profil/newPassword", name="newPassword")
     */
    public function newPassword(HeaderService $header, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(newPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($request->request->get('new_password_form') as $newPass) {
                $newPassword = $newPass;
                break;
            }
            $hash = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('profil/newPassword.html.twig', [
            'header' => $header,
            'form' => $form->createView(),
            'method' => 'POST'

        ]);
    }

    /**
     * @Route("/profil/adresses", name="adresses")
     */
    public function adresses(HeaderService $header, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $userAdresses = $user->getAdresses();

        foreach ($userAdresses as $adress) {
            dump($adress);
        }
        return $this->render('profil/adressesPage.html.twig', [
            'header' => $header,
            'user' => $user,
            'adresses' => $userAdresses
        ]);
    }
    /**
     * @Route("/profil/addAdress", name="addAdress")
     */
    public function addAdress(HeaderService $header, Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $userAdress = new Adress();

        $form = $this->createForm(AdressType::class, $userAdress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userAdress->setAccount($user);
            $manager->persist($userAdress);
            $manager->flush();

            return $this->redirectToRoute('adresses');
        }

        return $this->render('profil/addAdress.html.twig', [
            'header' => $header,
            'form' => $form->createView()
        ]);
    }
}
