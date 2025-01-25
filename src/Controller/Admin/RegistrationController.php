<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\MessagerieService;
use App\Service\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    private $JWTService;
    private $entityManager;
    public function __construct(JWTService $JWTService, EntityManagerInterface $entityManager)
    {
        $this->JWTService = $JWTService;
        $this->entityManager = $entityManager;
    }
    // A RETIRER
    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(TransportInterface $transport): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $emailReceiver = 'jbarenne@laposte.net';
        $email = (new TemplatedEmail())
            ->from(new Address('jbarenne@laposte.net'))
            ->to(new Address('julie.barn9@gmail.com'))
            ->subject('Validation de votre compte')

            // path of the Twig template to render
            ->html('Validation de votre compte Test')

            // change locale used in the template, e.g. to match user's locale
            ->locale('fr')

            // pass variables (name => value) to the template
            ->context([
                'expiration_date' => new \DateTime('+1 day'),
                'username' => 'foo',
            ]);

        try {
            $sentMessage = $transport->send($email);
            // Accéder à l'ID du message
            $messageId = $sentMessage->getMessageId();
            // Accéder aux informations de débogage
            $debugInfo = $sentMessage->getDebug();

            // Log des informations de débogage
            $this->addFlash('success', 'Email envoyé avec succès! Message ID: ' . $messageId);
            $this->addFlash('debug', 'Debug Info: ' . $debugInfo);
        } catch (TransportExceptionInterface $e) {
            // Log de l'erreur
            $debugInfo = $e->getDebug();
            $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
            $this->addFlash('debug', 'Debug Info: ' . $debugInfo);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'email_sent' => false
        ]);
    }
    #[Route('/register', name: 'admin_registration')]
    public function register(MessagerieService $messagerie, Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $repository): ?Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Test if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $email */
            $emailReceiver = $form->get('email')->getData();
            // Send email
            if (empty($repository->findOneBy(['email' => $emailReceiver]))) {
                // GET DATA
                $roles = $form->get('roles')->getData();
                $cleanedRoles = [];
                foreach ($roles as $role) {
                    $cleanedRole = Utils::cleanInputStatic($role); // CLEANING
                    if (in_array($cleanedRole, ['ROLE_ADMIN', 'ROLE_EDITOR'])) { // VALIDATION
                        $cleanedRoles[] = $cleanedRole; // ADD VALID ROLES
                    }
                }
                /** @var string $plainPassword */
                $plainPassword = Utils::cleanInputStatic($form->get('plainPassword')->getData());
                /** @var \DateTimeImmutable $tokenExpirateAt */
                $tokenExpirateAt = new \DateTimeImmutable('+1 day');
                /** @var string $login */
                $login = Utils::cleanInputStatic($form->get('login')->getData());
                $token = 'TokenSent';
                /** @var Association $assoUser */
                $assoUser = $form->get('user_asso')->getData();
                // SETTERS
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
                $user->setTokenExpirateAt($tokenExpirateAt);
                // define creation date
                $user->setCreatedAt(new \DateTimeImmutable());
                $user->setLogin($login);
                $user->setToken($token);
                // define roles
                $user->setRoles($cleanedRoles);
                // ensure $assoUser is of type null|App\Entity\Association before assigning
                $user->setUserAsso($assoUser instanceof \App\Entity\Association ? $assoUser : null);
                // add user to the database
                try {
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                    /** @var string $token */
                    $header = [
                        'alg' => 'HS256',
                        'typ' => 'JWT',
                    ];
                    $payload = [
                        'user_id' => $user->getId(),
                    ];
                    $token = JWTService::generate($header, $payload, $this->getParameter('app.jwtsecret'), 14400);
                    $emailSent = $messagerie->sendMail('Validation de votre compte', $user->getEmail(), 'email/confirmation_email.html.twig', [
                        'token' => $token,
                        'login' => $user->getLogin(),
                        'expiresAtMessageData' => '3 jours',
                    ]);
                    if ($emailSent) {
                        $this->addFlash('success', 'Bienvenue sur le Réseau des Semeurs de Jardins ! Un email de confirmation a été envoyé si l\'adresse existe.');
                    } else {
                        $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email =/. Veuillez réessayer.');
                    }
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'enregistrement de l\'utilisateur =/. Veuillez réessayer.');
                }
            } else {
                // ERROR : email already used
                $this->addFlash('error', 'Erreur dans la saisie de l\'email =/');
            }
        }
        $pageTitle = 'Inscription';
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'email_sent' => false,
            'pageTitle' => $pageTitle
        ]);
    }

    #[Route('/verify/{token}', name: 'app_verify_email')]
    public function verifyUserEmail(String $token,  UserRepository $repository, EntityManagerInterface $entityManager): Response
    {
        if ($this->JWTService->isValid($token) &&  !$this->JWTService->isExpired($token) && $this->JWTService->check($token, $this->getParameter('app.jwtsecret'))) {
            $payload = $this->JWTService->getPayload($token);
            $user = $repository->find($payload['user_id']);
            $user->setVerified(true);
            $user->setToken('noToken');
            $user->setTokenExpirateAt(new \DateTimeImmutable('00:00:00'));
            try {
                $entityManager->flush();
                // SUCCESS : isVerified = true
                $this->addFlash('success', 'Votre compte a été validé avec succès !');
            } catch (\Exception $exception) {
                // ERROR : error while generating the token
                $this->addFlash('error', 'Erreur, le token n\'a pas été trouvé =/');
            }
        } else {
            $this->addFlash('error', 'Erreur, votre lien n\'est pas correct ou a expiré =/');
        }

        // @TODO Change the redirect on success and handle or remove the flash message
        return $this->redirectToRoute('app_login');
    }
}
