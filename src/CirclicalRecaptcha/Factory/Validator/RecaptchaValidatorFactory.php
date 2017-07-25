<?php

namespace CirclicalRecaptcha\Factory\Validator;

use CirclicalRecaptcha\Form\Validator\RecaptchaValidator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;


class RecaptchaValidatorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        $validator = new RecaptchaValidator();
        $validator->setSecret($config['circlical']['recaptcha']['server']);

        if (!empty($config['circlical']['recaptcha']['bypass'])) {
            $validator->setCaptchaBypassed($config['circlical']['recaptcha']['bypass']);
        }

        return $validator;
    }
}