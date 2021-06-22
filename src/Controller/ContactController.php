<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    private $recaptcha;

    function __construct($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }

    /**
     * @Route("/contact.{format}", name="contact", format="html")
     */
    public function index(Request $request, MailerInterface $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();

            $client = HttpClient::create();
            $secret = $this->recaptcha;

            $recaptcha = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret' => $secret,
                    'response' => $contact->getToken()
                ]
            ]);

            $recaptcha = json_decode($recaptcha->getContent());
            if($recaptcha->success == false || $recaptcha->score < .5) {
                return $this->redirectToRoute('contact_fail', ['format' => 'html']);
            }

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

            return $this->render('contact/success.html.twig');
        }

        return $this->render('contact/index.html.twig');
    }
}
