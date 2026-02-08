<?php

namespace TheHenriDev\PHPTerminalKit\List;

class MultiSelectionList extends SelectionList
{
  public function getSelectedOptions(bool $expand = false)
  {
    return $this->getOptions(selected: true, expand: $expand);
  }

  public function getUnselectedOptions(bool $expand = false)
  {
    return $this->getOptions(selected: false, expand: $expand);
  }
}
