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
 * opChatPluginChatComponents
 *
 * @package     opChatPlugin
 * @subpackage  action
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */
class opChatPluginChatComponents extends sfComponents
{
  public function executeStatusGadget(sfWebRequest $request)
  {
    $this->numOfMembers = ChatRoomMemberTable::getInstance()->countAllMembers();
  }
}
