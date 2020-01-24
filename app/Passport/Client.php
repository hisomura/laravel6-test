<?php

namespace App\Passport;

use Laravel\Passport\Client as BaseClient;

/**
 * Class Client
 * @package App\Passport
 * @property string $redirect
 */
class Client extends BaseClient
{
    public function skipsAuthorization()
    {
        return $this->redirect === config('oauth.redirect_url');
    }
}
