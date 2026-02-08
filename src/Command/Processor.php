<?php

namespace TheHenriDev\PHPTerminalKit\Command;

class Processor
{
  public private(set) Routes $routes;

  public function prompt(string $prompt, string $symbol, string $desc = "", bool $hidden = false, int $recoil = 0) {
    echo(($desc ? str_pad("", $recoil, " ") . "{$desc}\n" : ""));
    
    readline_callback_handler_install('', function(){});

    list($text, $char, $pretty) = array_fill_keys(range(0, 3), "");

    do {
      if(preg_match("/^[\\x20-\\x7E]$/m", $char)) {
        $text .= $char;
      } else {
        if(chr(10) === $char) {
          break;
        } elseif(chr(127) === $char) {
          echo(str_pad("\r", strlen($pretty), chr(32)));

          $text = substr($text, 0, -1);
        }
      }

      $pretty = $text;
      if($hidden) {
        $pretty = preg_replace("/[\\x00-\\xFF]/m", "*", $text);
      }

      echo($pretty = sprintf("\r%s%s%s %s", str_pad("", $recoil, " "), $prompt, $symbol, $pretty));
    } while(is_string($char = stream_get_contents(STDIN, 1)));

    echo "\n";

    return $text;
  }

  public function listen(string $command)
  {
    $storage = new \stdClass();

    while(!($this->routes->execute($command, $storage) instanceof Bye)) {
      $command = $this->prompt(sprintf("[%s] ", $this->routes->prompt), "»");
    }
  }

  public function __construct()
  {
    $this->routes = new Routes();
  }
}
