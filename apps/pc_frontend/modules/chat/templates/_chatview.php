<?php foreach($chatlist as $chat): ?>
<dt>
<span class="number"><?php echo $chat->number ?></span>: 
<?php echo link_to($chat->Member->name, '@obj_member_profile?id='.$chat->member_id, array('popup' => true)); ?> 
<?php echo $chat->created_at ?>
</dt>
<dd class="<?php echo $chat->command ?>">
<?php echo op_auto_link_text($chat->body) ?>
</dd>
<?php endforeach; ?>
