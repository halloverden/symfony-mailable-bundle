<?php


namespace HalloVerden\MailableBundle\Entity;


class ClassNameHelper {

  /**
   * @param string $fqcn
   *
   * @return string
   */
  public static function getSnakeCaseClassNameFromFQCN(string $fqcn): string {
    return self::camelCaseToSnakeCase(self::getClassNameFromFQCN($fqcn));
  }

  /**
   * @param string $camelCase
   *
   * @return string
   */
  public static function camelCaseToSnakeCase(string $camelCase): string {
    return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($camelCase)));
  }

  /**
   * @param string $fqcn
   *
   * @return string
   */
  public static function getClassNameFromFQCN(string $fqcn): string {
    $c = explode('\\', $fqcn);
    return end($c);
  }
}
