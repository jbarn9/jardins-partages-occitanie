<?php
// src/Security/LoginForm.php
namespace App\Security;

use Psr\Log\LoggerInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormLoginAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;
    private LoggerInterface $logger;
    private $userRepository;
    private $router;

    public function __construct(private UrlGeneratorInterface $urlGenerator, RouterInterface $router, UserRepository $userRepository, LoggerInterface $logger, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->logger = $logger;
        $this->userRepository = $userRepository;
        $this->router = $router;
    }
    protected function getLoginUrl(): string
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }
    public function supports(Request $request): ?bool
    {
        $this->logger->info('Checking supports', [
            'route' => $request->attributes->get('_route'),
            'method' => $request->getMethod()
        ]);
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // Retrieve credentials
        $credentials = $this->getCredentials($request);
        // Find the user
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        // Check if the user exists and is active
        if (!$user || !$user->isVerified()) {
            throw new CustomUserMessageAuthenticationException('Votre compte n\'est pas encore actif. Un email doit vous attendre dans votre messagerie privée.');
        }

        // Verify password
        if (!$this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            throw new CustomUserMessageAuthenticationException('Les données renseignées ne sont pas correctes =/');
        }
        // dd($request->request->get('_csrf_token'));
        return new Passport(
            new UserBadge($user->getEmail()),
            new PasswordCredentials($request->request->get('_password')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function getCredentials(Request $request): array
    {
        $credentials =  [
            'email' => $request->request->get('_email'),
            'password' => $request->request->get('_password'),
            'token' => $request->request->get('_csrf_token'),
        ];

        return $credentials;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);

        // Redirige vers la page de connexion avec le message d'erreur
        return new RedirectResponse($this->router->generate('app_login'));
    }
}
