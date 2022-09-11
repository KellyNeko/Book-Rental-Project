<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiKeyAuthenticator extends AbstractGuardAuthenticator
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getCredentials(Request $request)
    {
        //Extirper depuit la requête les données
        $token = str_replace('Bearer ', '', $request->headers->get('Authorization'));
        return [
            'apiKey' => $token,
        ];
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //A partir des credentials, être capable de renvoyer un utilisateur
        // $apikey = $credentials['token'];
        // if (null === $apikey) {
        //     return;
        // }
        // if null, authentication will fail
        // if a User object, checkCredentials() is called
        return $userProvider->loadUserByUsername($credentials['apikey']);
    }
    /**
     * Make sure the api key is valid
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        //Vérifier que le mot de passe corrrespond

        // $omegaApiKey = $this->container->getParameter('apiKey');
        // if($credentials == $omegaApiKey) {
        //     dump("Vous etes authentifié !");
        // }
        
        // return true;
        return $user instanceof User && $credentials['apiKey']->$user->getApiKey();
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );
        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }
    /**
     * Called when authentication is needed, but it's not sent
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            'message' => 'Il faut vous authentifier'
        );
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function supports(Request $request)
    {
        // Si ce système d'authentification doit rentrer en jeu
        $auth = $request->get('Authorization');

        return $auth && str_starts_with($auth, 'Bearer');
    }
}