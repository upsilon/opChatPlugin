<?php

/**
 * PluginChatContentTable
 * 
 * @package    opChatPlugin
 * @subpackage ChatContent
 * @author     Youichi Kimura <kim.upsilon@gmail.com>
 */
class PluginChatContentTable extends Doctrine_Table
{
  public function getList($room, $last = 0, $count = 20)
  {
    $query = $this->createQuery()
      ->where('chat_room_id = ?', $room->id)
      ->andWhere('level >= 5')
      ->andWhere('number > ?', $last)
      ->orderBy('id DESC')
      ->limit($count);

    return array_reverse(iterator_to_array($query->execute()));
  }

  public function getLastNumber($room)
  {
    return $this->createQuery()
      ->select('MAX(number)')
      ->where('chat_room_id = ?', $room->id)
      ->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
  }

  public function login($member, $room)
  {
    $obj = new ChatContent();
    $obj->ChatRoom = $room;
    $obj->Member = $member;
    $obj->level = 9;
    $obj->body = $member->name.' さんがログインしました';
    $obj->save();
  }

  public function logout($member, $room)
  {
    $obj = new ChatContent();
    $obj->ChatRoom = $room;
    $obj->Member = $member;
    $obj->level = 10;
    $obj->body = $member->name.' さんがログアウトしました';
    $obj->save();
  }
}
