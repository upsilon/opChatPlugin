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
 * PluginChatRoom
 *
 * @package     opChatPlugin
 * @subpackage  model
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
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

  public function postInsert($event)
  {
    $msg = new ChatContent();
    $msg->ChatRoom = $this;
    $msg->member_id = $this->member_id;
    $msg->level = 8;
    $msg->body = $this->Member->name.' さんがチャットルームを作成しました';
    $msg->save();
  }
}
