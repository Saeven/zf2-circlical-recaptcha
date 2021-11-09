<?php

namespace Spec\CirclicalRecaptcha;

use CirclicalRecaptcha\Module;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Laminas\Form\FormElementManager\FormElementManagerV3Polyfill;
use Laminas\Form\FormElementManagerFactory;
use Laminas\Form\View\Helper\FormElement;
use Laminas\Mvc\Application;
use Laminas\Mvc\Controller\PluginManager;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Service\ViewHelperManagerFactory;
use Laminas\ServiceManager\ServiceManager;

class ModuleSpec extends ObjectBehavior
{
    public function it_adds_the_captcha_element_in_boostrap(MvcEvent $event, Application $application, ServiceManager $serviceManager, PluginManager $viewHelper, FormElement $formManager)
    {
        $serviceManager->get('ViewHelperManager')->willReturn($viewHelper);
        $viewHelper->get('FormElement')->willReturn($formManager);
        $formManager->addType('recaptcha', 'recaptcha')->shouldBeCalled();
        $application->getServiceManager()->willReturn($serviceManager);
        $event->getApplication()->willReturn($application);

        $this->onBootstrap($event);
    }

    public function it_returns_config_from_file()
    {
        $config = include getcwd() . '/config/module.config.php';

        $this->getConfig()->shouldBeLike($config);
    }
}
