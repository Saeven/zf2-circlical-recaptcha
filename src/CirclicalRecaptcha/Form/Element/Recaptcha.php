<?php

namespace CirclicalRecaptcha\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;

class Recaptcha extends Element implements InputProviderInterface
{
    protected $attributes = [
        'type' => 'recaptcha',
    ];

    protected $validator;

    protected $secret;

    public function getSecret()
    {
        return $this->secret;
    }

    public function __construct($validator, $secret)
    {
        parent::__construct();
        $this->validator = $validator;
        $this->secret = $secret;
    }

    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'validators' => [
                $this->validator,
            ],
        ];
    }
}