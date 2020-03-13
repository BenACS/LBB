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

        // If form is OK, send email & redirects to a success page
    	if($form->isSubmitted() && $form->isValid()){
    		$this->createEmail($form->getData(), $mailer);
            return $this->showSuccessMessage($header);
    	}

        return $this->render('contact/index.html.twig', [
        	'header' => $header,
        	'form' => $form->createView()
        ]);
    }

    /**
     * Redirects to a page showing that a message was sent
     * @Route("/contact", name="contact_success")
     */
    public function showSuccessMessage(HeaderService $header)
    {
    	return $this->render('contact/success.html.twig', [
    		'header' => $header
    	]);
    }


    // Create & send Email via SwiftMailer
    public function createEmail(array $test, Swift_Mailer $mailer)
    {
    	//dd($test);

    	$message = (new \Swift_Message('Test Email'))
    		->setFrom($test['email'])
    		->setTo('lesbonsbooleens@gmail.com')
    		->setSubject($test['reason'])
    		->setBody(
    			$this->renderView(
    				'email_swift_mailer.html.twig',
    				['test' => $test]
    			),
    			'text/html'
    		)
    		->setCharset('utf-8')
    	;

    	$mailer->send($message);
    }
}
