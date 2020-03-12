<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactFormType;

use App\Service\Header\HeaderService;

use Swift_Mailer;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(HeaderService $header, Request $request, Swift_Mailer $mailer)
    {
    	$form = $this->createForm(ContactFormType::class);
    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()){
    		$this->createEmail($form->getData(), $mailer);
    	}

        return $this->render('contact/index.html.twig', [
        	'header' => $header,
        	'form' => $form->createView()
        ]);
    }

    /**
     * Redirects to a page showing that a message was sent
     * @Route("/contact/success", name="contact_success")
     */
    public function showSuccessMessage(HeaderService $header, Swift_Mailer $mailer, Request $request)
    {
    	$this->createEmail($mailer);
    	dd($request->request->get('email'));

    	return $this->render('contact/success.html.twig', [
    		'header' => $header
    	]);
    }


    // Create & send Email via SwiftMailer
    public function createEmail(array $test, Swift_Mailer $mailer)
    {
    	dd($test);

    	$message = (new \Swift_Message('Test Email'))
    		->setFrom('test@example.com')
    		->setTo('LeBonMail@lbb.com')
    		->setBody(
    			$this->renderView(
    				'email_swift_mailer.html.twig'
    			),
    			'text/html'
    		)
    	;

    	$mailer->send($message);
    }
}
