<?php use_helper('Javascript') ?>
<?php use_helper('opChat') ?>

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

<div id="config_sound" style="display: none">
<input type="checkbox" id="is_enable_sound" />
<label for="is_enable_sound">サウンドを有効にする</label>
</div>

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

<?php javascript_tag() ?>
var op_chat = new Chat({
<?php foreach (opChatPluginConfiguration::getIntervalConfigs() as $interval => $value): ?>
  <?php echo sprintf('%s: %d,', $interval, $value)."\n" ?>
<?php endforeach ?>
<?php if (($sounds = op_chat_get_sounds()) !== null): ?>
  sounds: <?php echo json_encode($sounds) ?>,
<?php endif ?>
  url: {
    post: "<?php echo url_for('@chatroom_post?id='.$room->id) ?>",
    show: "<?php echo url_for('@chatroom_show?id='.$room->id) ?>",
    heartbeat: "<?php echo url_for('@chatroom_heartbeat?id='.$room->id) ?>"
  }
});
<?php end_javascript_tag() ?>

