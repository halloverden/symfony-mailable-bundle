<?php


namespace HalloVerden\MailableBundle\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailerMessageListener implements EventSubscriberInterface {

  /**
   * @var Address|null
   */
  private $from = null;

  /**
   * @var Address|null
   */
  private $replyTo = null;

  /**
   * MailerMessageListener constructor.
   *
   * @param array|null $from
   * @param array|null $replyTo
   */
  public function __construct(?array $from = null, ?array $replyTo = null) {
    $this->from = $from ? $this->createAddress($from) : null;
    $this->replyTo = $replyTo ? $this->createAddress($replyTo): null;
  }

  /**
   * @param array $array
   *
   * @return Address
   */
  private function createAddress(?array $array): Address {
    return new Address($array['email'], $array['name'] ?? '');
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return [
      MessageEvent::class => 'onMessage'
    ];
  }

  /**
   * @param MessageEvent $event
   */
  public function onMessage(MessageEvent $event) {
    $message = $event->getMessage();

    if (!$message instanceof Email) {
      return;
    }

    if ($this->from !== null && empty($message->getFrom())) {
      $message->addFrom($this->from);
      $event->getEnvelope()->setSender($this->from);
    }

    if ($this->replyTo !== null && empty($message->getReplyTo())) {
      $message->addReplyTo($this->replyTo);
    }
  }

}
