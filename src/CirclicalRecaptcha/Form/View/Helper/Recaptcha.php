<?php

/**
,,
`""*3b..											
     ""*3o.					  						2/11/15 1:04 AM
         "33o.			                  			S. Alexandre M. Lemaire
           "*33o.                                 	(c) Circlical Inc.
              "333o.								Redistribution of these files is illegal.
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

namespace CirclicalRecaptcha\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement;

class Recaptcha extends FormElement
{
    public function render( ElementInterface $element )
    {
        return '<div class="form-group">
            <div id="register_recaptcha">
                <div class="g-recaptcha" data-sitekey="' . $element->getSecret() . '"></div>
            </div>
        </div>
        <script src="//www.google.com/recaptcha/api.js"></script>';
    }
}