<?php

/**
,,
`""*3b..											
     ""*3o.					  						2/11/15 12:21 AM
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

namespace CirclicalRecaptcha\Factory\Form\Element;

use CirclicalRecaptcha\Form\Element\Recaptcha;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RecaptchaFactory implements FactoryInterface
{

    public function createService( ServiceLocatorInterface $serviceLocator )
    {
        /**
         * @var \Doctrine\Common\Persistence\ObjectRepository $userRepository
         * @var \Zend\InputFilter\InputFilterPluginManager $serviceLocator
         */

        $serviceManager = $serviceLocator->getServiceLocator();
        $config = $serviceManager->get('config');
        $secret = !empty( $config['circlical']['recaptcha']['client'] ) ? $config['circlical']['recaptcha']['client'] : 'configure_me';

        return new Recaptcha( $secret );
    }
}

