<section class="well well-lg">
<?php
$s_status = array();
$total = 0;
$done = 0;
foreach ( $data['Report'] as $report ) {
  if ( !isset($s_status[$report['Status']['name']])) {
    $s_status[$report['Status']['name']] = 1;
  } else {
    ++$s_status[$report['Status']['name']];
  }
  if ( strstr($report['Status']['property_string'], 'is_closed') !== false ) {
    ++$done;
  }
  ++$total;
}
if ( $total < 1 ) {
  $total = $done = 1;
}
?>
<header>
<h1><?php echo __('%s version %s', $data['Project']['name'], $data['ProjectVersion']['name']) ?></h1>
</header>
<?php echo $this->Parsedown->text($data['ProjectVersion']['description']) ?>
<section>
<header>
<h3><?php echo __('Progress') ?></h3>
</header>
<div class="progress progress-striped active" style="text-align:center">
<span style="position:absolute;right:0;left:0"><?php echo __('%3.2f%%', $done / $total * 100)?></span>
<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $done / $total * 100?>%">
</div>
</div>
<footer>
<?php echo __('Version Status is %s', $data['ProjectVersionStatus']['name'])?>
</footer>
</section>
<table class="table table-hover">
<caption>
<h4><?php echo __('Report Summary by Status')?></h4>
</caption>
<thead>
<tr>
<?php foreach ( $s_status as $name => $count ): ?>
<th><?php echo $name ?></th>
<?php endforeach; ?>
</tr>
</thead>
<tbody>
<tr>
<?php foreach ( $s_status as $name => $count ): ?>
<td><?php echo __('%d / %3.2f%%', $count, $count / $total * 100) ?></td>
<?php endforeach; ?>
</tr>
</tbody>
</table>
<footer>
<ul style="padding:0">
<?php if ( $can_file_report ): ?>
<li style="display:inline"><?php echo $this->Html->link(__('File Report'),array('controller' => 'projects', 'action' => 'file_report', $data['ProjectVersion']['project_id'], 'back' => array('controller' => 'project_versions', 'action' => 'view', $data['ProjectVersion']['id'])), array('class' => 'btn btn-primary')) ?></li>
<?php endif; ?>
<li style="display:inline"><?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default')) ?></li>
</ul>
</footer>
</section>