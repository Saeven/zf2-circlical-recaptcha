<?php

/**
,,
`""*3b..											
     ""*3o.					  						2/11/15 11:55 PM
         "33o.			                  			S. Alexandre M. Lemaire
           "*33o.                                 	(c) Circlical Inc.
              "333o.
                "3333bo...       ..o:
                  "33333333booocS333    ..    ,.
               ".    "*3333SP     V3o..o33. .333b
                "33o. .33333o. ...A33333333333333b
          ""bo.   "*33333333333333333333P*33333333:
             "33.    V333333333P"**""*"'   VP  * "l
               "333o.433333333X
                "*3333333333333AoA3o..oooooo..           .b
                       .X33333333333P""     ""*oo,,     ,3P
                      33P""V3333333:    .        ""*****"
                    .*"    A33333333o.4;      .
                         .oP""   "333333b.  .3;
                                  A3333333333P
                                  "  "33333P"
                                      33P*"
		                              .3"
                                     "
                                     
                                     
*/

namespace CirclicalRecaptcha\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;

class Recaptcha extends Element implements InputProviderInterface
{
	protected $attributes = array(
        'type' => 'recaptcha'
    );

    protected $validator;

    protected $secret;

    public function getSecret(){
        return $this->secret;
    }

    public function __construct( $validator, $secret ){
        parent::__construct();
        $this->validator = $validator;
        $this->secret    = $secret;
    }

    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'validators' => [
                $this->validator,
            ]
        ];
    }
}