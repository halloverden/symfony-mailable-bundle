<?php


namespace HalloVerden\MailableBundle\Interfaces;


interface LocaleAwareMailableRecipientInterface extends MailableRecipientInterface {

  /**
   * @return string|null
   */
  public function getMailableLocale(): ?string;
}
