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
 * PluginChatContentTable
 *
 * @package     opChatPlugin
 * @subpackage  model
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 * @author      Shogo Kawahara <kawahara@bucyou.net>
 */

class PluginChatContentTable extends Doctrine_Table
{
  public function getList($room_id, $last = 0, $count = 20)
  {
    $query = $this->createQuery()
      ->where('chat_room_id = ?', $room_id)
      ->andWhere('level >= 5')
      ->andWhere('number > ?', $last)
      ->orderBy('number DESC')
      ->limit($count);

    return array_reverse(iterator_to_array($query->execute()));
  }

  public function getListJson($controller, $contents)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('Helper', 'Tag', 'Escaping', 'opUtil'));
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
        $d = sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $d);
      }
      $data['body'] = op_auto_link_text(op_decoration(nl2br($data['body'])));
      $result[] = $data;
    }

    return json_encode($result);
  }

  public function getLastNumber($room_id)
  {
    return $this->createQuery()
      ->select('MAX(number)')
      ->where('chat_room_id = ?', $room_id)
      ->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
  }

  public function getLastPostDate($room_id)
  {
    return $this->createQuery()
      ->select('MAX(created_at)')
      ->where('chat_room_id = ?', $room_id)
      ->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
  }

  public function getCount($room_id)
  {
    return $this->createQuery()
      ->where('chat_room_id = ?', $room_id)
      ->count();
  }

  public function post($room_id, $member_id, $body, $options = array())
  {
    $post = new ChatContent();
    $post->chat_room_id = $room_id;
    $post->member_id = $member_id;
    $post->body = $body;

    if (isset($options['level']))
    {
      $post->level = $options['level'];
    }

    if (isset($options['command']))
    {
      $post->command = $options['command'];
    }

    $post->save();
  }

  public function login($room_id, $member)
  {
    $this->post($room_id, $member->id, $member->name.' さんがログインしました',
      array('level' => 9));
  }

  public function logout($room_id, $member)
  {
    $this->post($room_id, $member->id, $member->name.' さんがログアウトしました',
      array('level' => 10));
  }

  public function getListPager($room_id, $page = 1, $size = 20)
  {
    $query = $this->createQuery()
      ->where('chat_room_id = ?', $room_id)
      ->andWhere('level >= 5')
      ->orderBy('number');

    $pager = new sfDoctrinePager('ChatContent', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
