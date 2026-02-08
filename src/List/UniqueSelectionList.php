<?php

namespace TheHenriDev\PHPTerminalKit\List;

class UniqueSelectionList extends SelectionList
{
  public function select(array|string $option)
  {
    $this->unselectAll();

    if(is_array($option)) {
      throw new Exception("UniqueSelectionList::select() The argument given must be string.", 1);
    }

    return parent::select($option);
  }

  public function getSelectedOption(bool $expand = false)
  {
    $options = $this->getOptions(selected: true, expand: $expand);

    return empty($options) ? null : $options[array_key_first[$options]];
  }

  public function getUnselectedOptions(bool $expand = false)
  {
    return $this->getOptions(selected: false, expand: $expand);
  }
}
