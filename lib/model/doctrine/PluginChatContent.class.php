<?php

/**
 * opChatPlugin
 *
 * This source file is subject to the Apache License version 2.0
 * that is bundled with this package in the file LICENSE.
 *
 * @license     Apache License 2.0
 */

/**
 * PluginChatContent
 *
 * @package     opChatPlugin
 * @subpackage  model
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */

abstract class PluginChatContent extends BaseChatContent
{
  public function preSave($event)
  {
    if (!$this->number)
    {
      $this->number = $this->getTable()->getLastNumber($this->chat_room_id) + 1;
    }

    if ($this->level > 5)
    {
      $this->command = 'message';
    }

    $filterEvent = sfContext::getInstance()->getEventDispatcher()
      ->filter(new sfEvent(null, 'op_chat.filter_message'), $this);

    if (empty($this->body))
    {
      $event->skipOperation();
    }
  }
}
