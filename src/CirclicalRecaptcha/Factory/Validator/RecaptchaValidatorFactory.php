<?php

namespace CirclicalRecaptcha\Factory\Validator;

use CirclicalRecaptcha\Form\Validator\RecaptchaValidator;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;


class RecaptchaValidatorFactory implements FactoryInterface
{
    private const DEFAULT_TIMEOUT = 900;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $config = $config['circlical']['recaptcha'] ?? [];

        $validator = new RecaptchaValidator($config['server'] ?? 'not configured', $config['default_timeout'] ?? self::DEFAULT_TIMEOUT);

        if (!empty($config['bypass'])) {
            $validator->setCaptchaBypassed($config['bypass']);
        }

        return $validator;
    }
}