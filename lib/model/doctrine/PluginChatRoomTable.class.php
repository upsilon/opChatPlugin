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
    $query = $this->createQuery()
      ->orderBy('updated_at DESC');
   
    $pager = new sfDoctrinePager('ChatRoom', $size);
    $pager->setQuery($query);
    $pager->setPage($page);
    $pager->init();
   
    return $pager;
  }
}
