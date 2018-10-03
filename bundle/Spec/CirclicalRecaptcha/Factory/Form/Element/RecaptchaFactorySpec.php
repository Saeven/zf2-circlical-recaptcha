<?php

namespace Spec\CirclicalRecaptcha\Factory\Form\Element;

use CirclicalRecaptcha\Form\Element\Recaptcha;
use CirclicalRecaptcha\Factory\Form\Element\RecaptchaFactory;
use Interop\Container\ContainerInterface;
use PhpSpec\ObjectBehavior;

class RecaptchaFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(RecaptchaFactory::class);
    }

    public function it_creates_recaptchas(ContainerInterface $container)
    {
        $container->get('config')->willReturn([
            'circlical' => [
                'recaptcha' => [
                    'server' => 'somekey',
                    'client' => 'otherkey',
                    'bypass' => true,
                ],
            ],
        ]);

        $recaptcha = $this->__invoke($container, Recaptcha::class);
        $recaptcha->shouldBeAnInstanceOf(Recaptcha::class);
        $recaptcha->getSecret()->shouldBe('otherkey');
    }
}
