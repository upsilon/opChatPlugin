<?php use_helper('opChat'); ?>
<?php op_include_box('chatroom_create_box',
  'チャットルームを作成することができます！',
  array(
    'title' => 'チャットルーム作成',
    'moreInfo' => array(link_to('チャットルーム作成', '@chatroom_new')),
  )
); ?>

<?php if ($pager->getNbResults()): ?>

<?php slot('pager'); ?>
<?php op_include_pager_navigation($pager, '@chatroom_list?page=%d'); ?>
<?php end_slot() ?>

<div class="dparts recentList"><div class="parts">
<div class="partsHeading">
<h3>チャットルーム一覧</h3>
</div>
<?php include_slot('pager'); ?>
<dl>
<?php foreach ($pager->getResults() as $room): ?>
<dt><?php echo op_format_date($room->updated_at, 'f') ?></dt>
<dd>
<?php echo op_chat_room_link($room, $sf_user); ?>
</dd>
<?php endforeach; ?>
</dl>
<?php include_slot('pager'); ?>
</div>
</div>

<?php endif; ?>
