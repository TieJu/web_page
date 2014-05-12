<section class="well well-lg">
<?php /* TODO: add filter building code here (will come in v2?) */?>
<table class="table table-hover">
<caption>
<h1><?php echo __('Reports') ?></h1>
</caption>
<thead>
<tr>
<th><?php echo __('ID') ?></th>
<th><?php echo __('Project') ?></th>
<th><?php echo __('Name') ?></th>
<th><?php echo __('Author') ?></th>
<th><?php echo __('Version') ?></th>
<th><?php echo __('Status') ?></th>
<th><?php echo __('Priority') ?></th>
<th><?php echo __('Target Version') ?></th>
<th><?php echo __('Assigned To') ?></th>
<th><?php echo __('Created') ?></th>
<th><?php echo __('Last Change') ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ( $reports as $report ): ?>
<tr>
<td><?php echo $this->Html->link(__('#%08X', $report['Report']['id']), array('action' => 'view', $report['Report']['id'])) ?></td>
<td><?php echo $this->Html->link($report['Version']['Project']['name'], array('controller' => 'projects', 'action' => 'view', $report['Version']['project_id'])) ?></td>
<td><?php echo $this->Html->link($report['Report']['name'], array('action' => 'view', $report['Report']['id'])) ?></td>
<td><?php echo $report['Author']['username'] ?></td>
<td><?php echo $this->Html->link($report['Version']['name'], array('controller' => 'project_versions', 'action' => 'view', $report['Version']['id'])) ?></td>
<td><?php echo $report['Status']['name'] ?></td>
<td><?php echo $report['Priority']['name'] ?></td>
<td><?php echo $this->Html->link($report['TargetVersion']['name'], array('controller' => 'project_versions', 'action' => 'view', $report['TargetVersion']['id'])) ?></td>
<td><?php echo $report['AssignedTo']['username'] ?></td>
<td><?php echo $report['Report']['created'] ?></td>
<td><?php echo $report['Report']['modified'] ?></td>
<td>
<?php
if ( isset($proj_perms[$report['Version']['project_id']]) ) {
  $perms = $proj_perms[$report['Version']['project_id']];
  $can_edit = isset($perms[PERMISSION_PROJECT_EDIT_REPORT]);
} else {
  $can_edit = false;
}

if ( $can_edit ) {
  echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span>', array('action' => 'edit', $report['Report']['id'], 'back' => array('action' => 'index', 'filters' => $filters)), array('escape' => false));
}
?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</section>