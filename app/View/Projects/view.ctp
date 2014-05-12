<section class="well well-lg">
<header>
<h1><?php echo __('Project: %s', $project['Project']['name']); ?></h1>
</header>
<?php echo $this->Parsedown->text($project['Project']['description']) ?>
<table class="table table-hover">
<caption>
<h2><?php echo __('Versions') ?></h2>
</caption>
<thead>
<tr>
<th><?php echo __('Name') ?></th>
<th><?php echo __('Description') ?></th>
<th><?php echo __('Status') ?></th>
<th><?php echo __('# Reports') ?></th>
<?php if ( $is_admin ): ?>
<th></th>
<?php endif; ?>
</tr>
</thead>
<tbody>
<?php foreach ( $project['ProjectVersion'] as $ver ): ?>
<tr>
<td><?php echo $this->Html->link($ver['name'], array('controller' => 'project_versions', 'action' => 'view', $ver['id'])) ?></td>
<td><?php echo $this->Html->link(shorten_text($this->Parsedown->text($ver['description']), 50), array('controller' => 'project_versions', 'action' => 'view', $ver['id'])) ?></td>
<td><?php echo $this->Html->link($ver['ProjectVersionStatus']['name'], array('controller' => 'project_versions', 'action' => 'view', $ver['id'])) ?></td>
<td><?php echo $this->Html->link(count($ver['Report']), array('controller' => 'reports', 'action' => 'index', 'filters' => 'Report.target_version_id,'.$ver['id'])) ?></td>
<?php if ( $is_admin ): ?>
<td>
<?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('controller' => 'project_versions', 'action' => 'edit', $ver['id']), array('escape' => false)) ?>
<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove"></span>', array('controller' => 'project_versions', 'action' => 'delete', $ver['id']), array('escape' => false), __('Are you sure to delete the project version %s ?', $ver['name'])) ?>
</td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<?php if ( $is_admin ): ?>
<td colspan="5">
<?php else: ?>
<td colspan="4">
<?php endif; ?>
<nav>
<ul style="padding:0">
<?php if ( $can_file_report ): ?>
<li style="display:inline"><?php echo $this->Html->link(__('File Report'), array('action' => 'file_report', $project['Project']['id'], 'back' => array('controller' => 'projects', 'action' => 'view', $project['Project']['id'])), array('class' => 'btn btn-primary')) ?></li>
<?php endif; ?>
<?php if ( $can_add_version ): ?>
<li style="display:inline"><?php echo $this->Html->link(__('Add Version'), array('controller' => 'project_versions', 'action' => 'create', $project['Project']['id']), array('class' => 'btn btn-default')) ?></li>
<?php endif; ?>
<li style="display:inline"><?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default')); ?></li>
</ul>
</nav>
</td>
</tr>
</tfoot>
</table>
</section>