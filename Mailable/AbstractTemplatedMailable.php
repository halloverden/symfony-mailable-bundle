<?php


namespace HalloVerden\MailableBundle\Mailable;

use HalloVerden\MailableBundle\Entity\ClassNameHelper;
use HalloVerden\MailableBundle\Interfaces\MailableInterface;
use HalloVerden\MailableBundle\Interfaces\MailableRecipientInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\RawMessage;

abstract class AbstractTemplatedMailable implements MailableInterface {
  const TEMPLATE_NAMESPACE = 'HalloVerdenMailable';

  /**
   * @var MailableRecipientInterface[]
   */
  private $recipients;

  /**
   * @var array
   */
  private $attachments = [];

  /**
   * @var array
   */
  private $pathAttachments = [];

  /**
   * BaseTemplatedMailable constructor.
   *
   * @param MailableRecipientInterface[] $recipients
   */
  public function __construct(?array $recipients = null) {
    $this->recipients = $recipients ?: [];
  }

  /**
   * @return string
   */
  protected abstract function getSubject(): string;

  /**
   * @return MailableRecipientInterface[]
   */
  public function getRecipients(): array {
    return $this->recipients;
  }

  /**
   * @param MailableRecipientInterface[] $recipients
   *
   * @return MailableInterface
   */
  public function withRecipients(array $recipients): MailableInterface {
    $new = clone $this;
    $new->recipients = $recipients;

    return $new;
  }

  /**
   * @inheritDoc
   */
  public function get(): RawMessage {
    $email = new TemplatedEmail();

    $email->htmlTemplate($this->getTemplate());
    $email->context($this->getContext());
    $email->subject($this->getSubject());

    foreach ($this->recipients as $recipient) {
      $email->addTo($recipient->getMailableRecipientAddress());
    }

    foreach ($this->attachments as $attachment) {
      $email->attach(...$attachment);
    }

    foreach ($this->pathAttachments as $attachment) {
      $email->attachFromPath(...$attachment);
    }

    return $email;
  }

  /**
   * @inheritDoc
   */
  public function withAttachment($body, string $name = null, string $contentType = null): MailableInterface {
    $new = clone $this;
    $new->attachments[] = [$body, $name, $contentType];

    return $new;
  }

  /**
   * @inheritDoc
   */
  public function withAttachmentFromPath(string $path, string $name = null, string $contentType = null): MailableInterface {
    $new = clone $this;
    $new->pathAttachments[] = [$path, $name, $contentType];

    return $new;
  }

  /**
   * @return string
   */
  protected function getTemplate(): string {
    $className = ClassNameHelper::getClassNameFromFQCN(static::class);
    return '@' . self::TEMPLATE_NAMESPACE. '/' . $className . '/' . ClassNameHelper::camelCaseToSnakeCase($className) . '.html.twig';
  }

  /**
   * @return array
   */
  protected function getContext(): array {
    return [];
  }

}
