<?php

namespace TheHenriDev\PHPTerminalKit\List;

class Option
{
  protected string $value;
  protected bool $selected;
  protected \stdClass $storage;

  public function select()
  {
    $this->selected = true;
  }

  public function unselect()
  {
    $this->selected = false;
  }

  public function isSelected()
  {
    return $this->selected;
  }

  public function getValue()
  {
    return $this->value;
  }

  public function __set(string $property, $value)
  {
    $this->storage->$property = $value;
  }

  public function __construct(string $value)
  {
    $this->value = $value;
    $this->selected = false;
    $this->storage = new \stdClass;
  }

  public function __get(string $property)
  {
    return $this->storage->$property ?? null;
  }
}
