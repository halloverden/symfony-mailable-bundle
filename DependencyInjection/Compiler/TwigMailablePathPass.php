<?php


namespace HalloVerden\MailableBundle\DependencyInjection\Compiler;


use HalloVerden\MailableBundle\DependencyInjection\Configuration;
use HalloVerden\MailableBundle\Mailable\AbstractTemplatedMailable;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigMailablePathPass implements CompilerPassInterface {

  /**
   * @inheritDoc
   */
  public function process(ContainerBuilder $container) {
    $path = Configuration::getPath($this->getConfig($container), $container);

    if ($path !== null) {
      $loader = $container->getDefinition('twig.loader.native_filesystem');
      $loader->addMethodCall('addPath', [$path, AbstractTemplatedMailable::TEMPLATE_NAMESPACE]);
    }
  }

  /**
   * @param ContainerBuilder $container
   *
   * @return array
   */
  private function getConfig(ContainerBuilder $container): array {
    return $this->processConfiguration(new Configuration(), $container->getExtensionConfig(Configuration::NAME));
  }

  /**
   * @param ConfigurationInterface $configuration
   * @param array                  $configs
   *
   * @return array
   */
  private function processConfiguration(ConfigurationInterface $configuration, array $configs): array {
    return (new Processor())->processConfiguration($configuration, $configs);
  }

}
