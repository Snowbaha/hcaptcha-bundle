<?php

namespace MeteoConcept\HCaptchaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class MeteoConceptHCaptchaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $validator = $container->getDefinition('meteo_concept_h_captcha.captcha_validator');
        $validator->replaceArgument(1, $config['hcaptcha']['secret']);
        $formtype = $container->getDefinition('meteo_concept_h_captcha.hcaptcha_form_type');
        $formtype->replaceArgument(1, $config['hcaptcha']['site_key']);
    }

    public function getNamespace()
    {
        return 'http://www.meteo-concept.fr/schema/dic/hcaptcha';
    }
}
