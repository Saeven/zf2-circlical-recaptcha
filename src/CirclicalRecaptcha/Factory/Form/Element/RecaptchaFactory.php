<?php

namespace CirclicalRecaptcha\Factory\Form\Element;

use CirclicalRecaptcha\Form\Element\Recaptcha;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class RecaptchaFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $secret = !empty( $config['circlical']['recaptcha']['client'] ) ? $config['circlical']['recaptcha']['client'] : 'configure_me';

        return new Recaptcha( $secret );
    }
}

