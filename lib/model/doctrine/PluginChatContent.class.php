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
  public function preSave($event)
  {
    if (!$this->number)
    {
      $this->number = $this->getTable()->getLastNumber($this->ChatRoom) + 1;
    }

    if ($this->level > 5)
    {
      $this->command = 'message';
    }
  }
}
