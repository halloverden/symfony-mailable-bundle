<?php


namespace HalloVerden\MailableBundle\Mailable;

use HalloVerden\MailableBundle\Entity\ClassNameHelper;
use HalloVerden\MailableBundle\Entity\TranslatableParameter;
use HalloVerden\MailableBundle\Interfaces\TranslatableMailableInterface;

abstract class AbstractTranslatableMailable extends AbstractTemplatedMailable implements TranslatableMailableInterface {
  const SUBJECT_TRANSLATION_ID = 'meta.subject';
  const SUBJECT_PARAMETER = 'subject';
  const TRANSLATION_DOMAIN = null;

  /**
   * @var string|null
   */
  private $subject = null;

  /**
   * @var string|null
   */
  protected $locale = null;

  /**
   * @var string|null
   */
  private $translationDomain = null;

  /**
   * @inheritDoc
   */
  public function getLocale(): ?string {
    return $this->locale;
  }

  /**
   * @inheritDoc
   */
  public function withLocale(?string $locale, array $translatedParameters = []): TranslatableMailableInterface {
    $new = clone $this;
    $new->locale = $locale;

    foreach ($translatedParameters as $key => $value) {
      $new->setTranslation($key, $value);
    }

    return $new;
  }

  /**
   * @inheritDoc
   */
  public function getTranslatableParameters(): array {
    return [
      self::SUBJECT_PARAMETER => new TranslatableParameter($this->getSubjectTranslationId(), $this->getSubjectTranslationParameters(), $this->getTranslationDomain())
    ];
  }

  /**
   * @inheritDoc
   */
  private function setTranslation(string $key, string $translatedValue): TranslatableMailableInterface {
    switch ($key) {
      case self::SUBJECT_PARAMETER:
        $this->subject = $translatedValue;
        break;
    }

    return $this;
  }

  /**
   * @return string|null
   */
  protected function getTranslationDomain(): ?string {
    return static::TRANSLATION_DOMAIN ?: ($this->translationDomain ?: $this->translationDomain = ClassNameHelper::getSnakeCaseClassNameFromFQCN(static::class));
  }

  /**
   * @return string
   */
  protected function getSubjectTranslationId() {
    return static::SUBJECT_TRANSLATION_ID;
  }

  /**
   * @return array
   */
  protected function getSubjectTranslationParameters(): array {
    return [];
  }

  /**
   * @inheritDoc
   */
  protected function getSubject(): string {
    if ($this->subject === null) {
      throw new \RuntimeException("translation need to run before getSubject()");
    }

    return $this->subject;
  }


  /**
   * @inheritDoc
   */
  protected function getContext(): array {
    return [
      'transArguments' => $this->getTranslationArguments(),
      'transDomain' => $this->getTranslationDomain(),
      'locale' => $this->getLocale(),
    ];
  }

  /**
   * @return array
   */
  protected function getTranslationArguments(): array {
    return [];
  }

}
