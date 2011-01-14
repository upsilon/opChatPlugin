<?php op_include_parts('chatStatusBox', 'chatStatus_'.$gadget->getId(), array(
  'title' => '現在のチャットルーム',
  'moreInfo' => array(link_to('チャットルーム一覧', '@chatroom_list')),
  'numOfMembers' => $numOfMembers,
)) ?>
