<?php use_stylesheet('/opChatPlugin/css/gadget.css') ?>
<div class="block">
<?php if ($options['numOfMembers'] == 0): ?>
現在、チャットルームには誰もいません。
<?php else: ?>
現在、チャットルームには <?php echo $options['numOfMembers'] ?> 人います。
<?php endif ?>
</div>
