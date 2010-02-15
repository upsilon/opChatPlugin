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
  public function getList($room_id, $count = 20)
  {
    $query = $this->createQuery()
      ->where('chat_room_id = ?', $room_id)
      ->andWhere('level >= 5')
      ->orderBy('id DESC')
      ->limit($count);

    return array_reverse(iterator_to_array($query->execute()));
  }

  public function getLastNumber($room_id)
  {
    return $this->createQuery()
      ->select('MAX(number)')
      ->where('chat_room_id = ?', $room_id)
      ->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
  }

  public function getMemberList($room_id)
  {
    $query = $this->createQuery()
      ->select('')
      ->where('chat_room_id = ?', $room_id)
      ->andWhere('level >= 9')
      ->groupBy('member_id');
  }
}
