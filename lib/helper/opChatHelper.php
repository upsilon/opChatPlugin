<?php

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

