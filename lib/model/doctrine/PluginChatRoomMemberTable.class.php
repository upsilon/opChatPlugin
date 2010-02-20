<?php
/**
 */
class PluginChatRoomMemberTable extends Doctrine_Table
{
  public function findOne($member, $room)
  {
    return $this->createQuery()
      ->where('member_id = ?', $member->id)
      ->andWhere('chat_room_id = ?', $room->id)
      ->limit(1)
      ->fetchOne();
  }

  public function login($member, $room)
  {
    $obj = $this->findOne($member, $room);
    if (!$obj)
    {
      $obj = new ChatRoomMember();
      $obj->Member = $member;
      $obj->ChatRoom = $room;
    }
    $obj->is_active = true;
    $obj->save();
  }

  public function logout($member, $room)
  {
    $obj = $this->findOne($member, $room);
    if ($obj)
    {
      $obj->is_active = false;
      $obj->save();
    }
  }

  public function update($member, $room)
  {
    $obj = $this->findOne($member, $room);
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

  public function isActive($room, $member)
  {
    $obj = $this->findOne($member, $room);
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
