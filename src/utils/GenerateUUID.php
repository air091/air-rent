<?php
class GenerateUUID
{
  public static function generate(): string
  {
    $uuid = random_bytes(16);
    $uuid[6] = chr((ord($uuid[6]) & 0x0f) | 0x40);
    $uuid[8] = chr((ord($uuid[8]) & 0x3f) | 0x80);
    return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($uuid), 4));
  }
}
