<?php

/**
 * PluginChatRoom
 * 
 * @package    opChatPlugin
 * @subpackage ChatRoom
 * @author     Youichi Kimura <kim.upsilon@gmail.com>
 */
abstract class PluginChatRoom extends BaseChatRoom
{
  public function isOpened()
  {
    return (strtotime($this->open_date) <= time());
  }

  public function isEditable($user)
  {
    return ($this->member_id == $user->getMemberId());
  }
}
