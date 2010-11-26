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
 * PluginChatRoomTable
 *
 * @package     opChatPlugin
 * @subpackage  model
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */

class PluginChatRoomTable extends Doctrine_Table
{
  public function getListPager($page = 1, $size = 20)
  {
    $query = $this->createQuery('r')
      ->leftJoin('r.ChatContent c')
      ->groupBy('r.id')
      ->orderBy('MAX(c.created_at) DESC');
   
    $pager = new sfDoctrinePager('ChatRoom', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();
   
    return $pager;
  }

  /**
   * Returns an instance of this class.
   *
   * @return object ChatRoomTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('ChatRoom');
  }
}
