<?php

namespace Spec\CirclicalRecaptcha;

use CirclicalRecaptcha\Module;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormElementManagerFactory;
use Zend\Form\View\Helper\FormElement;
use Zend\Mvc\Application;
use Zend\Mvc\Controller\PluginManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Service\ViewHelperManagerFactory;
use Zend\ServiceManager\ServiceManager;

class ModuleSpec extends ObjectBehavior
{
    public function it_adds_the_captcha_element_in_boostrap(MvcEvent $e, Application $application, ServiceManager $serviceManager, PluginManager $viewHelper, FormElement $formManager)
    {
        $serviceManager->get('ViewHelperManager')->willReturn($viewHelper);
        $viewHelper->get('FormElement')->willReturn($formManager);
        $formManager->addType('recaptcha', 'recaptcha')->shouldBeCalled();
        $application->getServiceManager()->willReturn($serviceManager);
        $e->getApplication()->willReturn($application);

        $this->onBootstrap($e);
    }

    public function it_returns_config_from_file()
    {
        $config = include getcwd() . '/config/module.config.php';

        $this->getConfig()->shouldBeLike($config);
    }
}
