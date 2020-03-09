<?php


namespace HalloVerden\MailableBundle\DependencyInjection;


use HalloVerden\MailableBundle\EventListener\MailerMessageListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;

class HalloVerdenMailableExtension extends Extension implements PrependExtensionInterface {

  /**
   * @inheritDoc
   * @throws \Exception
   */
  public function load(array $configs, ContainerBuilder $container) {
    $config = $this->getConfig($configs);

    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
    $loader->load('services.yaml');

    $defaultFrom = $config['default_from_address'] ?? null;
    $defaultReplyTo = $config['default_reply_to_address'] ?? null;

    if ($defaultFrom || $defaultReplyTo) {
      $mailerMessageListener = new Definition(MailerMessageListener::class, [
        '$from' => $defaultFrom,
        '$replyTo' => $defaultReplyTo
      ]);
      $mailerMessageListener->addTag('kernel.event_subscriber');
      $container->setDefinition(MailerMessageListener::class, $mailerMessageListener);
    }
  }

  /**
   * @inheritDoc
   */
  public function prepend(ContainerBuilder $container) {
    $this->addTranslationPaths($container);
  }

  /**
   * @param ContainerBuilder $container
   */
  private function addTranslationPaths(ContainerBuilder $container): void {
    $path = Configuration::getPath($this->getConfig($container->getExtensionConfig(Configuration::NAME)), $container);

    if ($path !== null) {
      $container->prependExtensionConfig('framework', [
        'translator' => [
          'paths' => $this->getTranslationPaths($path)
        ]
      ]);
    }
  }

  /**
   * @param array $configs
   *
   * @return array
   */
  private function getConfig(array $configs): array {
    return $this->processConfiguration(new Configuration(), $configs);
  }

  /**
   * @param string $mailablePath
   *
   * @return array
   */
  private function getTranslationPaths(string $mailablePath): array {
    $finder = Finder::create()
      ->followLinks()
      ->directories()
      ->filter(function (\SplFileInfo $file) {
        return $file->getFilename() === 'translations';
      })
      ->in($mailablePath);

    $paths = [];

    foreach ($finder as $file) {
      $paths[] = (string) $file;
    }

    return $paths;
  }
}
