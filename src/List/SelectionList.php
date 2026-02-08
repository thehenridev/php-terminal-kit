<?php

namespace TheHenriDev\PHPTerminalKit\List;

class SelectionList
{
  protected array $options;

  public function add(array|string $options)
  {
    if(is_string($options)) {
      $options = array($options);
    }

    foreach($options as $option) {
      if(!is_string($option)) {
        continue;
      }

      $this->options[] = new Option($option);
    }

    return $this;
  }

  public function del(array|string $options)
  {
    if(is_string($options)) {
      $options = array($options);
    }

    foreach($options as $target) {
      if(!is_string($target)) {
        continue;
      }

      foreach($this->options as $key => $option) {
        if($target === $option->getValue()) {
          unset($this->options[$key]);
        }
      }
    }

    return $this;
  }

  public function getOptions(bool $selected = null, bool $expand = false)
  {
    $options = array();
    if($selected) {
      $options = array_filter(array_map(function($option) {
        return $option->isSelected() ? $option : null;
      }, $this->options));
    } elseif(is_null($selected)) {
      $options = $this->options;
    } else {
      $options = array_filter(array_map(function($option) {
        return $option->isSelected() ? null : $option;
      }, $this->options));
    }

    return $expand ? $options : array_map(function($option) {
      return $option->getValue();
    }, $options);
  }

  public function select(array|string $options)
  {
    if(is_string($options)) {
      $options = array($options);
    }

    foreach($options as $target) {
      if(!is_string($target)) {
        continue;
      }

      foreach($this->options as $key => $option) {
        if($target === $option->getValue()) {
          $this->options[$key]->select();
        }
      }
    }
    
    return $this;
  }

  public function unselect(array|string $options)
  {
    if(is_string($options)) {
      $options = array($options);
    }

    foreach($options as $target) {
      if(!is_string($target)) {
        continue;
      }

      foreach($this->options as $key => $option) {
        if($target === $option->getValue()) {
          $this->options[$key]->unselect();
        }
      }
    }
    
    return $this;
  }

  public function unselectAll()
  {
    foreach($this->options as $option) {
      $option->unselect();
    }
    
    return $this;
  }

  public function clear()
  {
    $this->options = array();
    
    return $this;
  }

  public function prettyPrint()
  {
    $pretty = array();
    foreach($this->getOptions() as $option) {
      array_push($pretty, sprintf("[%s] %s", ($option->isSelected() ? '*' : ' '), $option->getValue()));
    }
    
    return implode("\n", $pretty);
  }

  public function __construct(array|string $options = [])
  {
    $this->options = array();
    $this->add($options);
  }
}
