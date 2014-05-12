<section class="well well-lg">
<table class="table table-hover">
<caption>
<h1>
<?php echo __('User Groups'); ?>
</h1>
</caption>
<thead>
<tr>
<th><?php echo __('Name'); ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ( $data as $userGroup ): ?>
<tr>
<td><?php echo $this->Html->link($userGroup['UserGroup']['name'], array('action' => 'edit', $userGroup['UserGroup']['id'])); ?></td>
<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $userGroup['UserGroup']['id']), array('escape' => false), __('Are you sure to delete the user group %s ?', $userGroup['UserGroup']['name'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<td colspan="2">
<nav>
<?php echo $this->Html->link(__('Create Usergroup'), array('action' => 'edit'), array('class' => 'btn btn-primary')); ?>
</nav>
</td>
</tr>
</tfoot>
</table>
</section>