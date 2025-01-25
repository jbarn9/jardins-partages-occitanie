<?php

namespace App\Service;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;

class MessagerieService
{
    private MailerInterface $mailer;
    private TransportInterface $transport;

    public function __construct(MailerInterface $mailer, TransportInterface $transport)
    {
        $this->mailer = $mailer;
        $this->transport = $transport;
    }
    public function sendMail($subject, $to, $template, array $context, ?string $from = 'julie.barn9@gmail.com'): bool
    {
        // dd($subject, $to, $template, $context, $from);
        //Load Composer's autoloader 
        require '../vendor/autoload.php';
        // do anything else you need here, like send an email
        // SUCCESS : email sent
        $mail = (new TemplatedEmail())
            ->from(new Address($from))
            ->to(new Address($to))
            ->subject($subject)
            ->replyTo(new Address($from))
            // path of the Twig template to render
            ->htmlTemplate($template)
            // change locale used in the template, e.g. to match user's locale
            ->locale('fr')
            // pass variables (name => value) to the template
            ->context($context);
        //envoi du mail   
        try {
            $this->transport->send($mail);
        } catch (TransportExceptionInterface $e) {
            // dd($e->getMessage());
            return false;
        }
        return true;
    }
}
