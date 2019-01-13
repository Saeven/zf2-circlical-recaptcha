<?php

namespace Spec\CirclicalRecaptcha\Form\View\Helper;

use CirclicalRecaptcha\Form\View\Helper\Recaptcha;
use PhpSpec\ObjectBehavior;

class RecaptchaSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Recaptcha::class);
    }

    public function it_can_render_properly(\CirclicalRecaptcha\Form\Element\Recaptcha $element)
    {
        $element->getAttribute('id')->willReturn('the-element');
        $element->getSecret()->willReturn('abcd');
        $element->getOption('no_script')->willReturn(true);
        $element->getOption('no_sitekey')->willReturn(false);
        $element->getOption('language')->willReturn(null);

        $control = sprintf(
            '<div class="form-group"><div%s class="g-recaptcha"%s></div></div>%s',
            ' id="the-element"',
            ' data-sitekey="abcd"',
            ''
        );

        $this->render($element)->shouldBeLike(trim($control));
    }

    public function it_can_render_properly_with_a_script(\CirclicalRecaptcha\Form\Element\Recaptcha $element)
    {
        $element->getAttribute('id')->willReturn('the-element');
        $element->getSecret()->willReturn('abcd');
        $element->getOption('no_script')->willReturn(false);
        $element->getOption('no_sitekey')->willReturn(false);
        $element->getOption('render')->willReturn('a');
        $element->getOption('onload')->willReturn('cb');
        $element->getOption('async')->willReturn('c');
        $element->getOption('defer')->willReturn('d');
        $element->getOption('language')->willReturn(null);


        $params = [
            'render' => 'a',
            'onload' => 'cb',
        ];

        $control = sprintf(
            '<div class="form-group"><div%s class="g-recaptcha"%s></div></div>%s',
            ' id="the-element"',
            ' data-sitekey="abcd"',
            sprintf(
                '<script src="//www.google.com/recaptcha/api.js%s"%s%s></script>',
                $params ? ('?' . http_build_query($params)) : '',
                'c' ? ' async' : '',
                'd' ? ' defer' : ''
            )
        );

        $this->render($element)->shouldBeLike(trim($control));
    }
}
