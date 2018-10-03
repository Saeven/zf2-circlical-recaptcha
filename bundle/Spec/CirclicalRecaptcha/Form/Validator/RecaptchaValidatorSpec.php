<?php

namespace Spec\CirclicalRecaptcha\Form\Validator;

use CirclicalRecaptcha\Form\Validator\RecaptchaValidator;
use PhpSpec\ObjectBehavior;

class RecaptchaValidatorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('secret', 900);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RecaptchaValidator::class);
    }

    public function it_returns_error_codes()
    {
        $this->getErrorCodes()->shouldBeArray();
    }

    public function it_returns_my_ip()
    {
        $_SERVER['HTTP_CLIENT_IP'] = '1.2.3.4';
        $this->getIP()->shouldBe('1.2.3.4');
    }

    public function it_returns_forwarded_ip()
    {
        $_SERVER['HTTP_CLIENT_IP'] = '10.10.2.2';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '2.3.4.5';
        $this->getIP()->shouldBe('2.3.4.5');
    }

    public function it_returns_null_when_nothing_adds_up()
    {
        unset($_SERVER['HTTP_CLIENT_IP']);
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        $this->getIP()->shouldBeNull();
    }

    public function it_requires_a_value()
    {
        /** @var RecaptchaValidator $this */
        $this->isValid('')->shouldBe(false);
    }

    public function it_performs_the_bypass()
    {
        /** @var RecaptchaValidator $this */
        $this->setCaptchaBypassed(true);
        $this->isValid('anything')->shouldBe(true);
    }

}
