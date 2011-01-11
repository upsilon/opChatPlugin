<?php use_helper('Url') ?>
<h2>プラグイン設定</h2>

<h3>更新間隔</h3>

<?php echo form_tag('@op_chat_update_interval') ?>
<table>
<tbody>
<?php echo $form ?>
</tbody>
<tfoot>
<tr><td colspan="2">
<input type="submit" value="<?php echo __('Save') ?>" />
</td></tr>
</tfoot>
</table>
</form>

