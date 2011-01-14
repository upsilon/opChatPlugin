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
 * PluginChatRoomMemberTable
 *
 * @package     opChatPlugin
 * @subpackage  model
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */

class PluginChatRoomMemberTable extends Doctrine_Table
{
  public function findOne($member_id, $room_id)
  {
    return $this->createQuery()
      ->where('member_id = ?', $member_id)
      ->andWhere('chat_room_id = ?', $room_id)
      ->limit(1)
      ->fetchOne();
  }

  public function login($room_id, $member_id)
  {
    $obj = $this->findOne($member_id, $room_id);
    if (!$obj)
    {
      $obj = new ChatRoomMember();
      $obj->member_id = $member_id;
      $obj->chat_room_id = $room_id;
    }
    $obj->is_active = true;
    $obj->save();
  }

  public function logout($room_id, $member_id)
  {
    $obj = $this->findOne($member_id, $room_id);
    if ($obj)
    {
      $obj->is_active = false;
      $obj->save();
    }
  }

  public function update($room_id, $member_id)
  {
    $obj = $this->findOne($member_id, $room_id);
    if ($obj)
    {
      $obj->updated_at = null;
      $obj->save();
    }
  }

  public function countAllMembers()
  {
    return count($this->getMembers());
  }

  public function getMembers($room_id = null)
  {
    $query = $this->createQuery()
      ->where('is_active = true')
      ->groupBy('member_id');

    if ($room_id !== null)
    {
      $query->andWhere('chat_room_id = ?', $room_id);
    }

    $result = array_filter($query->execute()->getData(), array($this, 'isOutOfTime'));

    return $result;
  }

  protected function isOutOfTime(ChatRoomMember $record)
  {
    return strtotime($record->updated_at) > strtotime('-3 minute');
  }

  public function isActive($room_id, $member_id)
  {
    $obj = $this->findOne($member_id, $room_id);
    if ($obj)
    {
      return $obj->is_active;
    }
    else
    {
      return false;
    }
  }

  /**
   * Returns an instance of this class.
   *
   * @return object ChatRoomMemberTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('ChatRoomMember');
  }
}
