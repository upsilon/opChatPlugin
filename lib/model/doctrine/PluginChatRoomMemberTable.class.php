<?php
/**
 */
class PluginChatRoomMemberTable extends Doctrine_Table
{
  public function update($member, $room)
  {
    $obj = $this->find(array($member->id, $room->id));
    if (!$obj)
    {
      $obj = new ChatRoomMember();
      $obj->Member = $member;
      $obj->ChatRoom = $room;
    }
    $obj->updated_at = null;
    $obj->save();
  }
}
