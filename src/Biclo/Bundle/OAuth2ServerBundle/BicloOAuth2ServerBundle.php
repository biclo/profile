<?php

namespace Biclo\Bundle\OAuth2ServerBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BicloOAuth2ServerBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSOAuthServerBundle';
    }
}
