<?php slot('body') ?>
<div>
チャットルームを終了します。<br />
このチャットルームへの書き込みができなくなってしまいますがよろしいですか？<br />
（終了後も書き込みは閲覧できます）
</div>
<?php end_slot() ?>

<?php op_include_yesno('close_confirm', new BaseForm(), new BaseForm(array(), array(), false), array(
  'title' => '確認',
  'body' => get_slot('body'),
  'yes_url' => url_for('@chatroom_close?id='.$room->getId()),
  'no_method' => 'get',
  'no_url' => url_for($room->isOpened() ? '@chatroom_show?id='.$room->getId() : '@chatroom_list'),
)) ?>
