<?php

namespace TheHenriDev\PHPTerminalKit\Command;

class Route
{
  public protected(set) string $pattern;
  public protected(set) \Closure $handler;

  public function setCommand(string $command)
  {
    $this->pattern = "/^\s*(?'command'{$command})\s?(?'params'[\\x00-\\xFF]*)$/m";

    return $this;
  }

  public function setHandler(\Closure $handler)
  {
    $this->handler = $handler;

    return $this;
  }

  protected function splitParams(string $params)
  {
    preg_match_all("/(\s*(?:--\w*)\s*|\s*(?:'(?:\\\\|\'|[^'])+')\s*|\s*(?:\"(?:\\\\|\\\"|[^\"])+\")\s*|\s*(?:(?:[^\s]+))\s*)/m", $params, $m, PREG_SET_ORDER);

    $params = array();
    
    if(count($m)) {
      foreach(array_map('trim', @array_map('array_pop', $m)) as $target) {
        if(str_starts_with($target, '--')) {
          array_push($params, new Param($target, null));
        } elseif(!is_null(array_key_last($params)) and is_null($params[array_key_last($params)]->value)) {
          $params[array_key_last($params)]->setValue($target);
        } else {
          array_push($params, new Param(null, $target));
        }
      }
    }

    return $params;
  }

  public function is(string $command)
  {
    $handler = array(null, null, null);

    if(preg_match($this->pattern, $command, $m, PREG_UNMATCHED_AS_NULL)) {
      $handler = array($this->handler, $m['command'], $this->splitParams($m['params']));
    }

    return $handler;
  }

  public function __construct(string $command = null, \Closure $handler = null)
  {
    if(is_string($command)) {
      $this->setCommand($command);
    }

    if(is_callable($handler)) {
      $this->setHandler($handler);
    }
  }
}
