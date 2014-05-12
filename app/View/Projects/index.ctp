<section class="well">
<table class="table table-hover">
<caption>
<h1>
<?php echo __('Projects') ?>
</h1>
</caption>
<thead>
<tr>
<th><?php echo __('Name') ?></th>
<th><?php echo __('Description') ?></th>
<th><?php echo __('# Versions') ?></th>
<?php if ( $is_admin ): ?>
<th></th>
<?php endif; ?>
</tr>
</thead>
<tbody>
<?php foreach ( $data as $project ): ?>
<tr>
<td><?php echo $this->Html->link($project['Project']['name'], array('action' => 'view', $project['Project']['id'])) ?></td>
<td><?php echo $this->Html->link(shorten_text($this->Parsedown->text($project['Project']['description']), 75), array('action' => 'view', $project['Project']['id'])) ?></td>
<td><?php echo count($project['ProjectVersion']) ?></td>
<?php if ( $is_admin ): ?>
<td>
<?php if ( $is_admin ): ?>
<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $project['Project']['id']), array('escape' => false)); ?>
<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('action' => 'delete', $project['Project']['id']), array('escape' => false), __('Are you sure to delete the project %s ?', $project['Project']['name'])) ?>
<?php endif; ?>
</td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
<?php if ( $is_admin ): ?>
<tfoot>
<tr>
<td colspan="4">
<nav>
<ul style="padding:0">
<li style="display:inline"><?php echo $this->Html->link(__('Create Project'), array('action' => 'edit'), array('class' => 'btn btn-primary')); ?></li>
<li style="display:inline"><?php echo $this->Html->link(__('Manage Project Status Types'), array('controller' => 'project_version_statuses', 'action' => 'index'), array('class' => 'btn btn-default')); ?></li>
<li style="display:inline"><?php echo $this->Html->link(__('Manage Report Priority Types'), array('controller' => 'report_priorities', 'action' => 'index'), array('class' => 'btn btn-default')); ?></li>
<li style="display:inline"><?php echo $this->Html->link(__('Manage Report Status Types'), array('controller' => 'report_statuses', 'action' => 'index'), array('class' => 'btn btn-default')); ?></li>
</ul>
</nav>
</td>
</tr>
</tfoot>
<?php endif; ?>
</table>
</section>