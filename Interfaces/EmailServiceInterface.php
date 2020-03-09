<?php


namespace HalloVerden\MailableBundle\Interfaces;


interface EmailServiceInterface {
  public function send(MailableInterface $mailable): void;
}
