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
 * opChatHelper
 *
 * @package     opChatPlugin
 * @subpackage  helper
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */

function op_chat_room_link($room, $member_id)
{
  $html = '';
  if ($room->is_closed)
  {
    $html .= '[終了] ';
    $linkto = '@chatroom_log?id='.$room->id;
  }
  else
  {
    $linkto = '@chatroom_login?id='.$room->id;
  }

  $title = !is_null($room->open_date) && !$room->isOpened() ? $room->title : op_chat_get_title_and_count($room);

  if ($room->isOpened())
  {
    $html .= link_to($title, $linkto, array(
      'title' => '最終投稿日時: '.op_format_date($room->getLastPostDate(), 'f'),
    ));
  }
  else
  {
    $html .= $title;
  }

  if (!is_null($room->open_date))
  {
    $html .= ' (開始: '.$room->open_date.')';
  }
  if ($room->isEditable($member_id))
  {
    $html .= ' '.link_to('[編集]', '@chatroom_edit?id='.$room->id);
  }

  return $html;
}

function op_chat_get_title_and_count($room)
{
  return sprintf('%s (%d)', $room->title, $room->countChatContent());
}

function op_chat_get_sounds($absolute = false)
{
  use_helper('Asset');
  static $sounds;
  $result = array();
  if (!is_array($sounds))
  {
    $sounds = array(
      'in' => '/opChatPlugin/sounds/in.mp3',
      'out' => '/opChatPlugin/sounds/out.mp3',
      'notice' => '/opChatPlugin/sounds/notice.mp3'
    );
    foreach ($sounds as $name => $sound)
    {
      if (file_exists(sfConfig::get('sf_web_dir').$sound))
      {
        $result[$name] = _compute_public_path($sound, sfConfig::get('sf_web_dir', ''), 'mp3', $absolute);
      }
    }
  }

  return empty($result) ? null : $result;
}
