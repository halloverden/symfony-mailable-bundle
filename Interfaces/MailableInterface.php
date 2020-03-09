<?php


namespace HalloVerden\MailableBundle\Interfaces;


use Symfony\Component\Mime\RawMessage;

interface MailableInterface {

  /**
   * @return RawMessage
   */
  public function get(): RawMessage;

  /**
   * @return MailableRecipientInterface[]
   */
  public function getRecipients(): array;

  /**
   * @param MailableRecipientInterface[] $recipients
   *
   * @return MailableInterface
   */
  public function withRecipients(array $recipients): MailableInterface;

  /**
   * @param string|resource $body
   * @param string|null     $name
   * @param string|null     $contentType
   *
   * @return MailableInterface
   */
  public function withAttachment($body, string $name = null, string $contentType = null): MailableInterface;

  /**
   * @param string      $path
   * @param string|null $name
   * @param string|null $contentType
   *
   * @return MailableInterface
   */
  public function withAttachmentFromPath(string $path, string $name = null, string $contentType = null): MailableInterface;
}
