<?php

namespace CirclicalRecaptcha;


class Module
{
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }


	public function onBootstrap(MvcEvent $e)
    {
        $application    = $e->getApplication();
        $services       = $application->getServiceManager();

	    $services->get('ViewHelperManager')->get('FormElement')->addType( 'recaptcha', 'recaptcha' );
    }
}