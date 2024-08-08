<?php

// Exemple simplifié de la classe parent pour éviter les erreurs
class BaseSendGridClientInterface {
    protected $auth;
    protected $host;
    protected $options;

    public function __construct($auth, $host, $options = array()) {
        $this->auth = $auth;
        $this->host = $host;
        $this->options = $options;
    }
}

// Classe SendGrid héritant de la classe parent
class SendGrid extends BaseSendGridClientInterface
{
    public function __construct($apiKey, $options = array())
    {
        $auth = 'Authorization: Bearer ' . $apiKey;
        $host = 'https://api.sendgrid.com';
        parent::__construct($auth, $host, $options);
    }
}
