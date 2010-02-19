<?php

/**
 * PluginChatContent
 * 
 * @package    opChatPlugin
 * @subpackage ChatContent
 * @author     Youichi Kimura <kim.upsilon@gmail.com>
 */
abstract class PluginChatContent extends BaseChatContent
{
  protected $allowCommand = array('red', 'blue', 'green', 'white', 'bold',
    'italic', 'tera', 'giga', 'mega', 'big', 'small', 'micro');

  public function preSave($event)
  {
    if (!$this->number)
    {
      $this->number = $this->getTable()->getLastNumber($this->chat_room_id) + 1;
    }

    $command = $this->command;
    $command = preg_split('/[\s　]+/u', $command, -1, PREG_SPLIT_NO_EMPTY);

    $newCommand = ''; 
    foreach ($command as $entry)
    {
      if (in_array($entry, $this->allowCommand))
      {
        $newCommand .= $entry.' ';
      } 
    }

    $this->command = $newCommand;

    if ($this->level > 5)
    {
      $this->command .= ' message';
    }

    $this->command = trim($this->command);
  }
}
