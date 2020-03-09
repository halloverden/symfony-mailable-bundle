<?php


namespace HalloVerden\MailableBundle;


use HalloVerden\MailableBundle\DependencyInjection\Compiler\TwigMailablePathPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HalloVerdenMailableBundle extends Bundle {

  public function build(ContainerBuilder $container) {
    parent::build($container);

    $container->addCompilerPass(new TwigMailablePathPass());
  }
}
