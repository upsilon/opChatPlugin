<dl>
<?php foreach($list as $chat): ?>
<dt>
<?php echo $chat->number ?>: 
<?php echo link_to($chat->Member->name, '@obj_member_profile?id='.$chat->member_id); ?> 
<?php echo $chat->created_at ?>
</dt>
<dd class="<?php echo $chat->command ?>">
<?php echo $chat->body ?>
</dd>
<?php endforeach; ?>
</dl>
