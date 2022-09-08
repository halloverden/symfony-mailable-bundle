<?php


namespace HalloVerden\MailableBundle\Entity;


class TranslatableParameter {

  /**
   * Translatable constructor.
   */
  public function __construct(private readonly string $id, private readonly array $parameters = [], private readonly ?string $domain = null) {
  }

  /**
   * @return string
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * @return array
   */
  public function getParameters(): array {
    return $this->parameters;
  }

  /**
   * @return string|null
   */
  public function getDomain(): ?string {
    return $this->domain;
  }

}
