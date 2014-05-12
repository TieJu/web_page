<?php
echo $this->Form->create('Forum', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Forum')
                              ,'name'
                              ));
if ( !empty($forumSet) ) {
    echo $this->Form->input('parent_id', array('type' => 'select', 'options' => $forumSet) );
}
?>
<?php if ( !empty($groupSet) ) : ?>
<table class="table table-hover">
<caption>
<h3><?php echo __('Forum User Group Permissions') ?></h3>
</caption>
<thead>
<tr>
<th><?php echo __('Name'); ?></th>
<th><?php echo __('Read'); ?></th>
<th><?php echo __('Write'); ?></th>
<th><?php echo __('Moderate'); ?></th>
</tr>
</thead>
<tbody>
<?php foreach ( $groupSet as $group ) : ?>
<tr>
<td><?php echo $group['UserGroup']['name']; ?></td>
<td><?php echo $this->Form->checkbox(sprintf('ForumUserGroupPermission.%d.%s', $group['UserGroup']['id'], PERMISSION_FORUM_READ)); ?></td>
<td><?php echo $this->Form->checkbox(sprintf('ForumUserGroupPermission.%d.%s', $group['UserGroup']['id'], PERMISSION_FORUM_WRITE)); ?></td>
<td><?php echo $this->Form->checkbox(sprintf('ForumUserGroupPermission.%d.%s', $group['UserGroup']['id'], PERMISSION_FORUM_MODERATE)); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
<?php
echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();
?>