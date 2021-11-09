<?php

namespace CirclicalRecaptcha\Form\Element;

use Laminas\Form\Element;

class Recaptcha extends Element
{
    public const ELEMENT_TYPE = 'recaptcha';

    protected $attributes = [
        'type' => self::ELEMENT_TYPE,
    ];

    private $secret;

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function __construct(string $secret)
    {
        parent::__construct();
        $this->secret = $secret;
    }
}