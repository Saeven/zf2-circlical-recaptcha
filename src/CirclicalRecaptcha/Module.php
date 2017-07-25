<?php

namespace CirclicalRecaptcha;

class Module
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $application = $e->getApplication();
        $services = $application->getServiceManager();
        $services->get('ViewHelperManager')->get('FormElement')->addType('recaptcha', 'recaptcha');
    }
}