<?php

namespace Spec\CirclicalRecaptcha\Factory\Validator;

use CirclicalRecaptcha\Factory\Validator\RecaptchaValidatorFactory;
use CirclicalRecaptcha\Form\Validator\RecaptchaValidator;
use Interop\Container\ContainerInterface;
use PhpSpec\ObjectBehavior;

class RecaptchaValidatorFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RecaptchaValidatorFactory::class);
    }

    function it_create_recaptcha_validators(ContainerInterface $container)
    {
        $this->__invoke($container, RecaptchaValidator::class)->shouldBeAnInstanceOf(RecaptchaValidator::class);
    }

    function it_creates_recaptchas_with_environment_bypass(ContainerInterface $container)
    {
        $container->get('config')->willReturn([
            'circlical' => [
                'recaptcha' => [
                    'server' => 'somekey',
                    'bypass' => true
                ],
            ],
        ]);
        /** @var RecaptchaValidator $validator */
        $validator = $this->__invoke($container, Recaptcha::class);
        $validator->isCaptchaBypassed()->shouldBe(true);
    }
}
