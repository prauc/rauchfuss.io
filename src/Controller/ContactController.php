<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact.{format}", name="contact", format="html")
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        dump($_ENV);
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();

            if($contact->getCopy()) {
                $email = (new TemplatedEmail())
                    ->from('noreply@rauchfuss.io')
                    ->to($contact->getEmail())
                    ->subject('Your request to rauchfuss.io | Patrick Rauchfuss')
                    ->htmlTemplate('emails/mail_customer.html.twig')
                    ->context([
                        'name' => $contact->getName(),
                        'message' => $contact->getMessage()
                    ])
                ;

                $mailer->send($email);
            }

            $email = (new TemplatedEmail())
                ->from('noreply@rauchfuss.io')
                ->to('patrick.rauchfuss@gmail.com')
                ->subject('New request')
                ->htmlTemplate('emails/mail.html.twig')
                ->context([
                    'name' => $contact->getName(),
                    'mail' => $contact->getEmail(),
                    'message' => $contact->getMessage()
                ])
            ;

            $mailer->send($email);

            return $this->redirectToRoute('contact_success', ['format' => 'html']);
        }

        return $this->render('contact/index.html.twig');
    }

    /**
     * @Route("/contact-success.{format}", name="contact_success", format="html")
     */
    public function success() {
        return $this->render('contact/success.html.twig');
    }
}
