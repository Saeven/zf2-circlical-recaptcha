<?php


namespace CirclicalRecaptcha\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement;

class Recaptcha extends FormElement
{
    public function render(ElementInterface $element)
    {
        return '<div class="form-group">
            <div id="register_recaptcha">
                <div class="g-recaptcha" data-sitekey="' . $element->getSecret() . '"></div>
            </div>
        </div>
        <script src="//www.google.com/recaptcha/api.js"></script>';
    }
}