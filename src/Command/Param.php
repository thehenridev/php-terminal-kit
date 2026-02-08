<?php

namespace TheHenriDev\PHPTerminalKit\Command;

class Param
{
  public protected(set) string|null $name;
  public protected(set) string|float|int|null $value;

  public function setName(string|null $name)
  {
    $this->name = $name;
  }

  public function setValue(string|float|int|null $value)
  {
    preg_match("/'((?:\\\\|\'|[^'])+)'|\"((?:\\\\|\\\"|[^\"])+)\"/m", $value, $m);

    $this->value = count($m = array_filter($m)) ? array_pop($m) : $value;
  }

  public function __construct(string|null $name, string|float|int|null $value)
  {
    $this->setName($name);
    $this->setValue($value);
  }
}
