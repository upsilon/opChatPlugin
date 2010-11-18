<?php use_helper('Javascript') ?>

<?php slot('chatroom_body'); ?>

<div style="float: right">
<?php echo link_to('[過去ログ]', '@chatroom_log?id='.$room->id, array('popup' => true)) ?>&nbsp;
<?php if ($room->isEditable($sf_user->getMemberId())): ?>
<?php echo link_to('[編集]', '@chatroom_edit?id='.$room->id) ?>&nbsp;
<?php endif; ?>
<?php echo link_to('[ログアウト]', '@chatroom_logout?id='.$room->id) ?>
</div>
<div>作成者: <?php echo link_to($room->getMember()->name, '@obj_member_profile?id='.$room->member_id, array('popup' => true)) ?></div>

<div id="chat">
<dl id="memberlist">
<?php include_partial('memberlist', array('memberlist' => $memberlist)) ?>
</dl>
<dl id="chatview">
<?php include_partial('chatview', array('chatlist' => $chatlist)) ?>
</dl>
</div>

<?php if (isset($form)): ?>

<form id="chat_content" method="post" action="<?php echo url_for('@chatroom_post?id='.$room->id) ?>">
<?php echo $form->renderHiddenFields() ?>

<div id="chat_input">
<?php echo $form['body'] ?>
</div>

<span class="submit">
<input type="submit" class="input_submit" value="送信" />
</span>
</form>

<?php endif; ?>

<?php end_slot(); ?>

<?php op_include_box('chatroom_body', get_slot('chatroom_body'), array(
  'title' => $room->getTitle(),
)) ?>

<?php echo javascript_tag('
var op_chat = new Chat({url: {
  post: "'.url_for('@chatroom_post?id='.$room->id).'",
  show: "'.url_for('@chatroom_show?id='.$room->id).'",
  heartbeat: "'.url_for('@chatroom_heartbeat?id='.$room->id).'"
}});
') ?>

