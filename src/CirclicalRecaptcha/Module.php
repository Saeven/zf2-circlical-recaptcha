<?php

namespace CirclicalRecaptcha;

use CirclicalRecaptcha\Form\Element\Recaptcha;

class Module
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function onBootstrap($event)
    {
        $application = $event->getApplication();
        $services = $application->getServiceManager();
        $services->get('ViewHelperManager')->get('FormElement')->addType(Recaptcha::ELEMENT_TYPE, Recaptcha::ELEMENT_TYPE);
    }
}