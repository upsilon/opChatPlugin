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
    return Doctrine::getTable('ChatRoomMember')->isActive($this->id, $member_id);
  }

  public function countChatContent()
  {
    return Doctrine::getTable('ChatContent')->getCount($this->id);
  }

  public function getLastPostDate()
  {
    return Doctrine::getTable('ChatContent')->getLastPostDate($this->id);
  }

  public function post($member_id, $body, $options = array())
  {
    Doctrine::getTable('ChatContent')->post($this->id, $member_id, $body, $options);
  }

  public function login($member)
  {
    Doctrine::getTable('ChatRoomMember')->login($this->id, $member->id);
    Doctrine::getTable('ChatContent')->login($this->id, $member);
  }

  public function logout($member)
  {
    Doctrine::getTable('ChatRoomMember')->logout($this->id, $member->id);
    Doctrine::getTable('ChatContent')->logout($this->id, $member);
  }

  public function postInsert($event)
  {
    $member = $this->Member;
    $this->post($member->id, $member->name.' さんがチャットルームを作成しました',
      array('level' => 8));
  }
}
