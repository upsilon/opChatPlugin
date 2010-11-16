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
  public function findOne($member_id, $room)
  {
    return $this->createQuery()
      ->where('member_id = ?', $member_id)
      ->andWhere('chat_room_id = ?', $room->id)
      ->limit(1)
      ->fetchOne();
  }

  public function login($member_id, $room)
  {
    $obj = $this->findOne($member_id, $room);
    if (!$obj)
    {
      $obj = new ChatRoomMember();
      $obj->member_id = $member_id;
      $obj->ChatRoom = $room;
    }
    $obj->is_active = true;
    $obj->save();
  }

  public function logout($member_id, $room)
  {
    $obj = $this->findOne($member_id, $room);
    if ($obj)
    {
      $obj->is_active = false;
      $obj->save();
    }
  }

  public function update($member_id, $room)
  {
    $obj = $this->findOne($member_id, $room);
    if ($obj)
    {
      $obj->updated_at = null;
      $obj->save();
    }
  }

  public function getMembers($room)
  {
    $query = $this->createQuery()
      ->where('chat_room_id = ?', $room->id)
      ->andWhere('is_active = true');

    $result = array();
    foreach ($query->execute() as $record)
    {
      if (strtotime($record->updated_at) > strtotime('-3 minute'))
      {
        $result[] = $record;
      }
    }

    return $result;
  }

  public function isActive($member_id, $room)
  {
    $obj = $this->findOne($member_id, $room);
    if ($obj)
    {
      return $obj->is_active;
    }
    else
    {
      return false;
    }
  }
}
