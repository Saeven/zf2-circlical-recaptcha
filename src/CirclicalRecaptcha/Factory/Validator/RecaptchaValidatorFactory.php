<?php

/**
,,
`""*3b..											
     ""*3o.					  						2/10/15 8:44 PM
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

namespace CirclicalRecaptcha\Factory\Validator;

use CirclicalRecaptcha\Form\Validator\RecaptchaValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class RecaptchaValidatorFactory implements FactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var \Zend\Form\FormElementManager       $serviceLocator
         * @var \Zend\ServiceManager\ServiceManager $serviceManager
         */
        $serviceManager = $serviceLocator->getServiceLocator();
        $config         = $serviceManager->get('config');

        $validator      = new RecaptchaValidator();
        $validator->setSecret( $config['circlical']['recaptcha']['server'] );
        return $validator;
    }
}