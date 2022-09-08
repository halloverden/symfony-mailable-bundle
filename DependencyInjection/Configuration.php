<?php


namespace HalloVerden\MailableBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Configuration implements ConfigurationInterface {
  const NAME = 'hallo_verden_mailable';
  const DEFAULT_PATH = '%kernel.project_dir%/src/Mailables';

  /**
   * @inheritDoc
   */
  public function getConfigTreeBuilder(): TreeBuilder {
    $treeBuilder = new TreeBuilder(self::NAME);

    $treeBuilder->getRootNode()
      ->children()
        ->scalarNode('path')->defaultValue(self::DEFAULT_PATH)->end()
        ->arrayNode('default_from_address')
          ->beforeNormalization()
            ->ifString()
              ->then(function (string $from) { return ['email' => $from]; })
          ->end()
          ->children()
            ->scalarNode('name')->end()
            ->scalarNode('email')->end()
          ->end()
        ->end()
        ->arrayNode('default_reply_to_address')
          ->beforeNormalization()
            ->ifString()
              ->then(function (string $replyTo) { return ['email' => $replyTo]; })
          ->end()
          ->children()
            ->scalarNode('name')->end()
            ->scalarNode('email')->end()
          ->end()
        ->end()
      ->end()
    ;

    return $treeBuilder;
  }

  /**
   * @param array            $config
   * @param ContainerBuilder $container
   *
   * @return string|null
   */
  public static function getPath(array $config, ContainerBuilder $container): ?string {
    $path = isset($config['path']) ? $container->getParameterBag()->resolveValue($config['path']) : null;
    return is_dir($path) ? $path : null;
  }

}
