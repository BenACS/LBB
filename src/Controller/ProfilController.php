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

        return $this->render('profil/adressesPage.html.twig', [
            'header' => $header,
            'user' => $user,
            'adresses' => $user->getAdresses()
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
            if (count($user->getAdresses()) < 5) {
                foreach ($request->request->get('adress') as  $key => $adressInfo) {
                    if ("$key" === "defaultAdress") {
                        $default = $adressInfo;
                    }
                }
                foreach ($user->getAdresses() as $adress) {
                    if (isset($default) && "$default" === "1") {
                        $adress->setDefaultAdress(false);
                    }
                }
                $userAdress->setAccount($user);
                $manager->persist($userAdress);
                $manager->persist($user);
                $manager->flush();

                return $this->redirectToRoute('adresses');
            } else {
                return $this->redirectToRoute('error');
            }
        }

        return $this->render('profil/addAdress.html.twig', [
            'header' => $header,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/profil/removeAdress/{adressId}", name="removeAdress")
     */
    public function removeAdress(HeaderService $header, Request $request, $adressId)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        foreach ($user->getAdresses() as $adress) {
            if ($adress->getId() == $adressId) {
                $remove = $user->removeAdress($adress);
            }
        }

        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('adresses');
    }
    /**
     * @Route("/profil/editAdress/{adressId?0}", name="removeAdress")
     */
    public function editAdress(HeaderService $header, Request $request, int $adressId = 0)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        foreach ($user->getAdresses() as $adress) {
            if ($adress->getId() == $adressId) {
                $adressToEdit = $adress;
            }
        }
        if ($adressId == 0 || !isset($adressToEdit)) {
            return $this->redirectToRoute("adresses");
        }

        if ($request->isMethod('POST')) {
            $adressToEdit->setCountry(ucfirst($request->request->get('adressCountry')));
            $adressToEdit->setCity(ucfirst($request->request->get('adressCity')));
            $adressToEdit->setZip($request->request->get('adressZip'));
            $adressToEdit->setAddress($request->request->get('adressAdress'));
            $adressToEdit->setOptionalInfo(ucfirst($request->request->get('adressOptions')));

            if ($request->request->get('defaultAdress') === 'on') {
                foreach ($user->getAdresses() as $adress) {
                    $adress->setDefaultAdress(false);
                }
                $adressToEdit->setDefaultAdress(true);
            } else {
                $adressToEdit->setDefaultAdress(false);
            }

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('adresses');
        }

        return $this->render('profil/editAdress.html.twig', [
            'header' => $header,
            'user' => $user,
            'adress' => $adressToEdit
        ]);
    }
}
