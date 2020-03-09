<?php


namespace HalloVerden\MailableBundle\Interfaces;

use HalloVerden\MailableBundle\Entity\TranslatableParameter;

interface TranslatableMailableInterface extends MailableInterface {

  /**
   * @return string|null
   */
  public function getLocale(): ?string;

  /**
   * @param string|null $locale
   * @param array       $translatedParameters
   *
   * @return TranslatableMailableInterface
   */
  public function withLocale(?string $locale, array $translatedParameters): TranslatableMailableInterface;

  /**
   * @return array<string, TranslatableParameter>
   */
  public function getTranslatableParameters(): array;

}
