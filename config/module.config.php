<?php

namespace CirclicalRecaptcha;

use CirclicalRecaptcha\Form\Validator\RecaptchaValidator;
use CirclicalRecaptcha\Form\Element\Recaptcha;
use CirclicalRecaptcha\Factory\Form\Element\RecaptchaFactory;
use CirclicalRecaptcha\Form\View\Helper\Recaptcha as RecaptchaHelper;
use CirclicalRecaptcha\Factory\Validator\RecaptchaValidatorFactory;

return [


    'form_elements' => [
        'factories' => [
            Recaptcha::class => RecaptchaFactory::class,
        ],
    ],

    'view_helpers' => [
        'invokables' => [
            'recaptcha' => RecaptchaHelper::class,
        ],
    ],

    'validators' => [
        'factories' => [
            RecaptchaValidator::class => RecaptchaValidatorFactory::class,
        ],
    ],
];