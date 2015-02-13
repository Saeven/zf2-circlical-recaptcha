# zf2-circlical-recaptcha

Google just rolled out their great new CAPTCHA (fewer angry old people is always great!), and you want to get it into your ZF2 project!  Please users and management alike with this easy module.


![Captcha Image]
(http://i.imgur.com/GWpqSH9.png)

## Requirements

Add this line to your composer.json
```js
"saeven/zf2-circlical-recaptcha": "dev-master"
```

Then include ```'CirclicalRecaptcha',``` in **module.config.php**.  The module should now be loaded.

## Configuration

Copy **circlical.recaptcha.local.php** into your config/autoload folder.  Open it up, and insert your ReCaptcha keys (you get these from Google's website).

```php
<?php
return [
    'circlical' => [
        'recaptcha' => [
            'client' => 'yourclientkeygoeshere',
            'server' => 'yourserverkeygoeshere',
        ],
    ],
];
```


# Templates

You need to add the captcha to your templates,  E.g., using Twig it'd look like:

``` twig
<div class="form-group" style="margin-bottom:25px;margin-top:25px;">
    {{ formLabel( form.get('g-recaptcha-response') ) }}
    {{ recaptcha( form.get('g-recaptcha-response') ) }}
    <div class="error_container alert alert-danger">{{ formElementErrors( form.get('g-recaptcha-response') ) }}</div>
</div>
```
*For now, the g-recaptcha-response is not mutable.  The reason being that Google renders it with this name, there's no option to change*

# Form & InputFilter

If you're unfamiliar with ZF2, here's sample form code that implements the Captcha.  There's more than needed, but you can see how the Element is added, and similarly, how the counterpart InputFilter (validator) is added as well.

```php
<?php

namespace CirclicalUser\Form;

use Zend\Form\Element,
    Zend\Captcha,
    Zend\InputFilter,
    Zend\Form\Element\Password,
    Zend\Form\Element\Text,
    Zend\Form\Form,
    CirclicalUser\Form\Element\Recaptcha,
    Zend\Form\Element\Button;


class UserForm extends Form
{

    const       EMAIL = 'email';

    public function __construct( $name, $options = array() )
    {
        parent::__construct( $name, $options );
    }

    /**
     * Construct a registration form, with an AuthenticationFormInterface instance to establish minimum field count
     */
    public function init()
    {

          $this->add([
              'name'    => 'g-recaptcha-response',
              'type'    => Recaptcha::class,
              'options' => [
                  'label'     => _( "Please complete the challenge below" ),
                  'recaptcha' => [
                      'secret' => $this->captcha_secret,
                  ],
              ],
          ]);


        $this->add([
            'name'      => self::EMAIL,
            'type'      => self::EMAIL,
            'options' => [
                'label' => _( 'Email' ),
            ],
            'attributes' => [
                'maxlength' => 254,
            ],
        ]);

        $this->add([
            'name' => 'email_confirm',
            'type' => self::EMAIL,
            'options' => [
                'label' => _( "Confirm Email" ),
            ],
            'attributes' => [
                'maxlength' => 254,
            ],
        ]);


        $this->add([
            'name' => 'submit',
            'type' => Button::class,
            'options' => [
                'label' => _( "Submit" ),
            ],
            'attributes' => [
                'class' => 'btn btn-primary',
                'type'  => 'submit',
            ]
        ]);
    }
}
```

And here's a sample InputFilter

```php
<?php

namespace CirclicalUser\InputFilter;

use CirclicalUser\Form\Validator\RecaptchaValidator;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Validator\NoObjectExists;
use Zend\Filter\StringToLower;
use Zend\Filter\StringTrim;
use Zend\InputFilter\InputFilter;
use Zend\Form\Element;
use Zend\Captcha;
use CirclicalUser\Form\Filter\ArrayBlock;
use HTMLPurifier;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

class UserInputFilter extends InputFilter implements UserInputFilterInterface
{
    const EMAIL     = 'email';
    const RECAPTCHA = 'g-recaptcha-response';

    protected $userRepository;
    protected $has_captcha;


    public function __construct( ObjectRepository $userRepository, $has_captcha )
    {
        $this->userRepository = $userRepository;
        $this->has_captcha    = $has_captcha;
    }

    public function init()
    {

        if( $this->has_captcha )
        {
            $this->add([
                'name'       => self::RECAPTCHA,
                'required'   => true,
                'break_chain_on_failure' => true,
                'messages' => array( _( "Please complete the anti-robot check!" ) ),
            ]);

            $this->get( self::RECAPTCHA )->setBreakOnFailure( true );
        }

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => ArrayBlock::class],
                ['name' => StringTrim::class],
                ['name' => HTMLPurifier::class],
                ['name' => StringToLower::class],
            ],
            'validators' => [
                [
                    'name' => EmailAddress::class,
                    'options' => [
                        'useMxCheck'        => true,
                        'useDeepMxCheck'    => true,
                        'useDomainCheck'    => true,
                        'message'           => _( "That email address has a typo in it, or its domain can't be checked" ),
                    ],
                ],

                [
                    'name' => NoObjectExists::class,
                    'options' => [
                        'fields'            => ['email'],
                        'messages'          => [
                            NoObjectExists::ERROR_OBJECT_FOUND => _( "That email is already taken, please log in instead" ),
                        ],
                        'object_repository' => $this->userRepository,
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'email_confirm',
            'required' => true,
            'filters' => [
                ['name' => ArrayBlock::class],
                ['name' => StringTrim::class],
                ['name' => HTMLPurifier::class],
                ['name' => StringToLower::class],
            ],
            'validators' => [
                [
                    'name' => 'identical',
                    'options' => [
                        'message' => _( "Your email and confirmation email are different" ),
                        'token' => self::EMAIL,
                    ],
                ],
            ],
        ]);
    }
}
```

That's all there is to it!
