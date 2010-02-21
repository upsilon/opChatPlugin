<?php slot('chatroom_log'); ?>

<?php if ($pager->getNbResults()): ?>

<?php slot('pager'); ?>
<?php op_include_pager_navigation($pager, '@chatroom_log?page=%d&id='.$room->id); ?>
<?php end_slot() ?>

<?php include_slot('pager'); ?>
<dl id="chatview">
<?php include_partial('chatview', array('chatlist' => $pager->getResults())) ?>
</dl>
<?php include_slot('pager'); ?>

<?php endif; ?>

<?php end_slot(); ?>

<?php op_include_box('chatroom_log', get_slot('chatroom_log'), array(
  'title' => $room->title.' の過去ログ',
)) ?>

<style>
<!--
#chatview dd {
  margin-left: 5px;
  margin-bottom: 5px;
}
-->
</style>
