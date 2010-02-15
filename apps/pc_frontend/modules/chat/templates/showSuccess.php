<?php slot('chatroom_body'); ?>

<?php if ($room->isEditable($sf_user)): ?>
<div style="float: right">
<?php echo link_to('[編集]', '@chatroom_edit?id='.$room->id) ?>&nbsp;
<?php echo link_to('[終了]', '@chatroom_close_confirm?id='.$room->id) ?>
</div>
<?php endif; ?>
<div>作成者: <?php echo link_to($room->getMember()->name, '@obj_member_profile?id='.$room->member_id); ?></div>

<table id="chat"><tr>
<td id="chatview">
<?php include_partial('chatview', array('list' => $chatlist)) ?>
</td>
<td id="memberlist">
<?php include_partial('memberlist', array('list' => $memberlist)) ?>
</td>
</tr></table>

<?php if (isset($form)): ?>

<form id="chat_content" method="post" action="<?php echo url_for('@chatroom_post?id='.$room->id) ?>">
<?php echo $form->renderHiddenFields() ?>
<div>
<?php echo $form['command']->renderLabel().': '.$form['command'] ?><br />
<?php echo $form['body'] ?>
<input type="submit" id="submit" value="送信" />
</div>
</form>

<?php endif; ?>

<?php end_slot(); ?>

<?php op_include_box('chatroom_body', get_slot('chatroom_body'), array(
  'title' => $room->getTitle(),
)) ?>

