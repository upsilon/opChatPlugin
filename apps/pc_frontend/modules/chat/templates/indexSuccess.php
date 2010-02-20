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
<?php foreach ($pager->getResults() as $item): ?>
<dt><?php echo op_format_date($item->updated_at, 'f') ?></dt>
<dd>
<?php if ($item->isOpened()): ?>
<?php echo $item->is_closed ? '[終了] ' : '' ?>
<?php echo link_to(sprintf('%s (%d)', $item->title, count($item->ChatContent)), '@chatroom_login?id='.$item->id) ?>
<?php else: ?>
<?php echo $item->title ?>
<?php endif; ?>

<?php if (!is_null($item->open_date) && !$item->is_closed): ?>
<?php echo '(開始: '.$item->open_date.')' ?>
<?php endif; ?>

<?php if ($item->isEditable($sf_user)): ?>
<?php echo link_to('[編集]', '@chatroom_edit?id='.$item->id) ?>
<?php endif; ?>
</dd>
<?php endforeach; ?>
</dl>
<?php include_slot('pager'); ?>
</div>
</div>

<?php endif; ?>
