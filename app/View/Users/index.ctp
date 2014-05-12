<section class="well well-lg">
<table class="table table-hover">
<thead>
<tr>
<th><?php echo __('Name'); ?></th>
<th></th>
</tr>
</thead>
<caption>
<h1><?php echo __('Users'); ?></h1>
</caption>
<tbody>
<?php foreach ( $data as $user ) : ?>
<tr>
<td><?php echo $this->Html->link($user['User']['username'], array('action' => 'admin_edit', $user['User']['id']) ); ?></td>
<td><?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $user['User']['id']), array('escape' => false),__('Are you sure to delete the user %s ?', $user['User']['username'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<td colspan="2">
<?php echo $this->Html->link(__('Create User'), array('action' => 'admin_edit'), array('class' => 'btn btn-primary')); ?>
</td>
</tr>
</tfoot>
</table>
</section>