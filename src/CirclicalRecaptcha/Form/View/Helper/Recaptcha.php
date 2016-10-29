<?php


namespace CirclicalRecaptcha\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement;

class Recaptcha extends FormElement
{
    public function render(ElementInterface $element)
    {
        $noScript = $element->getOption('no_script');
        $noSitekey = $element->getOption('no_sitekey');

        $sitekeyVariable = $noSitekey ? '' : 'data-sitekey="' . $element->getSecret() . '"';

        $output = '<div class="form-group">
            <div id="register_recaptcha">
                <div class="g-recaptcha" ' . $sitekeyVariable . '></div>
            </div>
        </div>';

        if (!$noScript) {
            $output .= '<script src="//www.google.com/recaptcha/api.js"></script>';
        }

        return $output;
    }
}