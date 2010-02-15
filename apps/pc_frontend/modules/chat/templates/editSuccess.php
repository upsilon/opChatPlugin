<?php use_helper('Javascript') ?>

<?php op_include_form('chatroom_from', $form, array(
  'title' => 'チャットルーム編集',
  'url' => url_for('@chatroom_update?id='.$form->getObject()->getId()),
)); ?>

<div class="dparts recentList"><div class="parts">
<div class="partsHeading">
<h3>チャットルームを終了する</h3>
</div>
<form action="<?php echo url_for('@chatroom_close_confirm?id='.$form->getObject()->getId()) ?>">
チャットルームへの書き込みの受付を終了し、閲覧のみ可能にします。<br />
この操作は元に戻せないの注意してください。
<div class="operation">
<ul class="moreInfo button">
<li>
<input type="submit" class="input_submit" value="チャットルームを終了" />
</li>
</ul>
</div>
</form>
</div>
</div>
</div>
