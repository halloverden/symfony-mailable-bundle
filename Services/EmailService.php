<?php


namespace HalloVerden\MailableBundle\Services;

use HalloVerden\MailableBundle\Entity\TranslatableParameter;
use HalloVerden\MailableBundle\Interfaces\EmailServiceInterface;
use HalloVerden\MailableBundle\Interfaces\LocaleAwareMailableRecipientInterface;
use HalloVerden\MailableBundle\Interfaces\MailableInterface;
use HalloVerden\MailableBundle\Interfaces\MailableRecipientInterface;
use HalloVerden\MailableBundle\Interfaces\TranslatableMailableInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailService implements EmailServiceInterface {

  /**
   * NewEmailService constructor.
   */
  public function __construct(private readonly MailerInterface $mailer, private readonly TranslatorInterface $translator) {
  }

  /**
   * @param MailableInterface $mailable
   *
   * @throws TransportExceptionInterface
   */
  public function send(MailableInterface $mailable): void {
    if ($mailable instanceof TranslatableMailableInterface) {
      $this->sendTranslatableMailable($mailable);
      return;
    }

    $this->mailer->send($mailable->get());
  }

  /**
   * Send one email per locale
   *
   * @param TranslatableMailableInterface $mailable
   *
   * @throws TransportExceptionInterface
   */
  private function sendTranslatableMailable(TranslatableMailableInterface $mailable): void {
    foreach ($this->getRecipientsByLocale($mailable->getRecipients()) as $locale => $recipients) {
      /** @var TranslatableMailableInterface $mailable */
      $mailable = $mailable->withRecipients($recipients);
      $this->mailer->send($this->getTranslatedMailable($mailable, $locale)->get());
    }
  }

  /**
   * @param MailableRecipientInterface[] $recipients
   *
   * @return array<string, MailableRecipientInterface[]> key = locale
   */
  private function getRecipientsByLocale(array $recipients): array {
    $defaultLocale = $this->translator->getLocale();
    $localeRecipients = [];

    foreach ($recipients as $recipient) {
      if ($recipient instanceof LocaleAwareMailableRecipientInterface) {
        $localeRecipients[$recipient->getMailableLocale()][] = $recipient;
      } else {
        $localeRecipients[$defaultLocale][] = $recipient;
      }
    }

    return $localeRecipients;
  }

  /**
   * @param TranslatableMailableInterface $mailable
   * @param string                        $locale
   *
   * @return TranslatableMailableInterface
   */
  private function getTranslatedMailable(TranslatableMailableInterface $mailable, string $locale): TranslatableMailableInterface {
    $translatedParameters = [];
    foreach ($mailable->getTranslatableParameters() as $parameter => $translatableParameter) {
      $translatedParameters[$parameter] = $this->translateTranslatableParameter($translatableParameter, $locale);
    }

    return $mailable->withLocale($locale, $translatedParameters);
  }

  /**
   * @param TranslatableParameter $translatableParameter
   * @param string                $locale
   *
   * @return string
   */
  private function translateTranslatableParameter(TranslatableParameter $translatableParameter, string $locale): string {
    return $this->translator->trans(
      $translatableParameter->getId(),
      $translatableParameter->getParameters(),
      $translatableParameter->getDomain(),
      $locale
    );
  }

}
