<?php

/**
 * PluginChatRoomTable
 * 
 * @package    opChatPlugin
 * @subpackage ChatContent
 * @author     Youichi Kimura <kim.upsilon@gmail.com>
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
}
