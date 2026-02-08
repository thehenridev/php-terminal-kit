<?php

namespace TheHenriDev\PHPTerminalKit\Command;

class Routes
{
  public protected(set) array $routes = array();
  public \stdClass $storage;
  public protected(set) string $prompt = '';

  public function write(string $text, int $recoil = 0)
  {
    $text = explode("\n", $text);
    foreach($text as $key => $line) {
      $text[$key] = str_pad('', $recoil, chr(32)) . "{$line}\n";
    }

    fwrite(STDIN, implode('', $text));
  }

  public function status(string $status, string $desc, int $recoil = 0, string $instruction = null)
  {
    $recoil = mb_str_pad('', $recoil, chr(32));
    $text = $recoil . (mb_strlen($status) ? "{$status}   " : "─┬ ") . " {$desc}\n";
    
    if($instruction) {
      $text .= $recoil . " └" . mb_str_pad('', mb_strlen($status), '─') . "» {$instruction}\n";
    }

    $this->write($text);
  }

  public function error(string $status, string $desc, int $recoil = 0, string $instruction = null)
  {
    $recoil = mb_str_pad('', $recoil, chr(32));
    $text = $recoil . (mb_strlen($status) ? "{$status}   " : "─┬ ") . " {$desc}\n";
    
    if($instruction) {
      $text .= $recoil . " └" . mb_str_pad('', mb_strlen($status), '─') . "» {$instruction}\n";
    }

    fwrite(STDERR, $text);
  }

  public function setPrompt(string $prompt)
  {
    $this->prompt = $prompt;

    return $this;
  }

  public function route(string $command, \Closure $handler)
  {
    $this->routes[] = new Route($command, $handler);

    return $this;
  }

  public function execute(string $incominCommand, \stdClass $storage = new \stdClass())
  {
    try {
      foreach($this->routes as $route) {
        list($handler, $command, $args) = $route->is($incominCommand);

        if($handler) {
          return $handler($this, $command, $args);
        }
      }
    } catch (\Throwable $reason) {
      $this->write("Erro ao processar comando: {$reason->getMessage()}\n", 1);
    }
  }

  public function __construct()
  {
    $this->storage = new \stdClass();
  }
}
