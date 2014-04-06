<?php

namespace Biclo\Bundle\OAuth2ServerBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *   name="oauth2_client"
 * )
 */
class Client extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;  

    /**
     * @ORM\Column(name="random_id", type="string", length=255, nullable=false)
     */
    protected $randomId;

    /**
     * @ORM\Column(type="array", name="redirect_uris")
     */
    protected $redirectUris;

    /**
     * @ORM\Column(type="string")
     */
    protected $secret;

    /**
     * @ORM\Column(type="array", name="allowed_grant_types")
     */
    protected $allowedGrantTypes;

    public function __construct()  
    {  
        parent::__construct();  
    }  
      
    public function getName()  
    {  
        return $this->name;  
    }  
  
    public function setName($name)  
    {  
        $this->name = $name;  
    }      
}
