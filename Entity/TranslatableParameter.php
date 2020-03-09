<?php


namespace HalloVerden\MailableBundle\Entity;


class TranslatableParameter {

  /**
   * @var string
   */
  private $id;

  /**
   * @var array
   */
  private $parameters = [];

  /**
   * @var string|null
   */
  private $domain = null;

  /**
   * Translatable constructor.
   *
   * @param string      $id
   * @param array       $parameters
   * @param string|null $domain
   */
  public function __construct(string $id, array $parameters = [], ?string $domain = null) {
    $this->id = $id;
    $this->parameters = $parameters;
    $this->domain = $domain;
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
