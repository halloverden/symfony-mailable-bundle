<?php


namespace HalloVerden\MailableBundle\Interfaces;


use Symfony\Component\Mime\Address;

interface MailableRecipientInterface {

  /**
   * @return Address
   */
  public function getMailableRecipientAddress(): Address;
}
