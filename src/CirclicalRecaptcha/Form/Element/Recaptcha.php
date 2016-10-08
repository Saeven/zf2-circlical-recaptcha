<?php

namespace CirclicalRecaptcha\Form\Element;

use Zend\Form\Element;

class Recaptcha extends Element
{
    protected $attributes = [
        'type' => 'recaptcha',
    ];

    protected $secret;

    public function getSecret()
    {
        return $this->secret;
    }

    public function __construct($secret)
    {
        parent::__construct();
        $this->secret = $secret;
    }
}