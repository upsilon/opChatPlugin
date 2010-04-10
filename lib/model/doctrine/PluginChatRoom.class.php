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

  public function isEditable($member_id)
  {
    return !$this->is_closed && $this->member_id == $member_id;
  }

  public function isWritable()
  {
    return $this->isOpened() && !$this->is_closed;
  }

  public function isActive($member_id)
  {
    return Doctrine::getTable('ChatRoomMember')->isActive($member_id, $this);
  }

  public function countChatContent()
  {
    return Doctrine::getTable('ChatContent')->getCount($this);
  }

  public function getLastPostDate()
  {
    return Doctrine::getTable('ChatContent')->getLastPostDate($this);
  }

  public function login($member)
  {
    Doctrine::getTable('ChatRoomMember')->login($member->id, $this);
    Doctrine::getTable('ChatContent')->login($member, $this);
  }

  public function logout($member)
  {
    Doctrine::getTable('ChatRoomMember')->logout($member->id, $this);
    Doctrine::getTable('ChatContent')->logout($member, $this);
  }
}
