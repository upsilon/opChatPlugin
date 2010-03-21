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
      ->orderBy('number DESC')
      ->limit($count);

    return array_reverse(iterator_to_array($query->execute()));
  }

  public function getListJson($controller, $contents)
  {
    $result = array();
    foreach ($contents as $content)
    {
      $data = array(
        'number'      => $content->number,
        'member_url'  => $controller->genUrl('@obj_member_profile?id='.$content->member_id),
        'member_name' => $content->Member->name,
        'command'     => $content->command,
        'body'        => $content->body,
        'created_at'  => $content->created_at,
      );
      foreach ($data as &$d)
      {
        sfContext::getInstance()->getConfiguration()->loadHelpers('Escaping');
        $d = sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $d);
      }
      $result[] = $data;
    }

    return json_encode($result);
  }

  public function getLastNumber($room)
  {
    return $this->createQuery()
      ->select('MAX(number)')
      ->where('chat_room_id = ?', $room->id)
      ->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
  }

  public function getCount($room)
  {
    return $this->createQuery()
      ->where('chat_room_id = ?', $room->id)
      ->count();
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

  public function getListPager($room, $page = 1, $size = 20)
  {
    $query = $this->createQuery()
      ->where('chat_room_id = ?', $room->id)
      ->andWhere('level >= 5')
      ->orderBy('number');

    $pager = new sfDoctrinePager('ChatContent', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
