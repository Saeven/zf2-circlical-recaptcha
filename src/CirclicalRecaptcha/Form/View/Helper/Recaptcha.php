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
        $elementId = $element->getAttribute('id');

        if (!$noScript) {
            $params = [];

            if ($render = $element->getOption('render')) {
                $params['render'] = $render;
            }

            if ($callback = $element->getOption('onload')) {
                $params['onload'] = $callback;
            }

            if ($language = $element->getOption('language')) {
                $params['hl'] = $language;
            }

            $async = $element->getOption('async');
            $defer = $element->getOption('defer');
            $scriptTag = sprintf(
                '<script src="//www.google.com/recaptcha/api.js%s"%s%s></script>',
                $params ? ('?' . http_build_query($params)) : '',
                $async ? ' async' : '',
                $defer ? ' defer' : ''
            );
        }

        return sprintf(
            '<div class="form-group"><div %s class="g-recaptcha"%s></div></div>%s',
            $elementId ? ' id="' . $elementId . '"' : '',
            $noSitekey ? '' : ' data-sitekey="' . $element->getSecret() . '"',
            $noScript ? '' : $scriptTag
        );
    }
}