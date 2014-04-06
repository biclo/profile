<?php

namespace Biclo\Bundle\OAuth2ServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    const CLIENT_NAME = 'bicloProfileTest';

    public function userAction(Request $request)
    {
        $token = $request->query->get('access_token');
        if (null === $token) {
            throw $this->createAccessDeniedException('No access_token found in the request query parameters');
        }

        $accessToken = $this->get('doctrine')->getRepository('BicloOAuth2ServerBundle:AccessToken')->findOneByToken($token);

        if (null === $accessToken) {
            throw $this->createAccessDeniedException('No token found with the access_token in the request query parameters');
        }

        $user = $accessToken->getUser();

        return new JsonResponse(array(
            'id' => $user->getId(),
            'email' => $user->getemail(),
            'name' => $user->getUsername(),
        ));
    }

    public function linksAction()
    {
        $client = $this->get('doctrine')->getRepository('BicloOAuth2ServerBundle:Client')->findOneByName(self::CLIENT_NAME);
        $route = $this->get('router')->generate('biclo_oauth2_server_default_links', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('BicloOAuth2ServerBundle:Default:links.html.twig', array(
            'client' => $client,
            'route' => $route,
        ));
    }

    public function clientsAction()
    {
        $accessTokens = $this->get('doctrine')->getRepository('BicloOAuth2ServerBundle:AccessToken')->findAll();
        $authCodes = $this->get('doctrine')->getRepository('BicloOAuth2ServerBundle:AuthCode')->findAll();
        $clients = $this->get('doctrine')->getRepository('BicloOAuth2ServerBundle:Client')->findAll();
        $refreshTokens = $this->get('doctrine')->getRepository('BicloOAuth2ServerBundle:AuthCode')->findAll();

        return $this->render('BicloOAuth2ServerBundle:Default:clients.html.twig', array(
            'accessTokens' => $accessTokens,
            'authCodes' => $authCodes,
            'clients' => $clients,
            'refreshTokens' => $refreshTokens,
            'now' => new \DateTime(),
        ));
    }

    public function apiAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        die('prout');
    }

    public function createClientAction(Request $request)
    {
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');  
        $route = $this->get('router')->generate('biclo_oauth2_server_default_links', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        $client = $clientManager->createClient();
        $client->setName(self::CLIENT_NAME);
        $client->setRedirectUris(array($route));
        $client->setAllowedGrantTypes(array('token', 'authorization_code'));

        $clientManager->updateClient($client);

        return $this->redirect($route);
    }
}
