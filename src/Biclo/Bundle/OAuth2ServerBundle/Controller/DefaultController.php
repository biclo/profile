<?php

namespace Biclo\Bundle\OAuth2ServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    const CLIENT_NAME = 'bicloProfileTest';

    public function linksAction()
    {
        $client = $this->get('doctrine')->getRepository('BicloOAuth2ServerBundle:Client')->findOneByName(self::CLIENT_NAME);
        $route = $this->get('router')->generate('biclo_oauth2_server_default_links', array(), UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->render('BicloOAuth2ServerBundle:Default:links.html.twig', array(
            'client' => $client,
            'route' => $route,
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
        $client->setAllowedGrantTypes(array('authorization_code'));

        $clientManager->updateClient($client);

        return $this->redirect($route);
    }
}
