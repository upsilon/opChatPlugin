<dl>
<?php foreach($list as $member): ?>
<dt>
<?php echo link_to($member->getName(), '@obj_member_profile?id='.$member->getId()); ?>
</dt>
<?php endforeach; ?>
</dl>
