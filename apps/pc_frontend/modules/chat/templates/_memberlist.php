<?php foreach($memberlist as $member): ?>
<dt>
<?php echo link_to($member->Member->getName(), '@obj_member_profile?id='.$member->Member->id, array('popup' => true)); ?>
</dt>
<?php endforeach; ?>
