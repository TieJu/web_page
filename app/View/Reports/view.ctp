<section class="well well-lg">
<h1><?php echo $report['Report']['name'] ?></h1>
<p><?php echo $this->Parsedown->text($report['Report']['description']) ?></p>
<p><?php echo __('Status: %s', $report['Status']['name']) ?></p>
<p><?php echo __('Priority: %s', $report['Priority']['name']) ?></p>
<p><?php echo __('Author: %s', $report['Author']['username']) ?></p>
<p><?php echo __('Version: %s', $report['Version']['name']) ?></p>
<?php if ( !is_null($report['TargetVersion']['id']) ) : ?>
<p><?php echo __('Target Version: %s', $report['TargetVersion']['name']) ?></p>
<?php endif; ?>
<?php if ( !is_null($report['AssignedTo']['id']) ): ?>
<p><?php echo __('Assigned to: %s', $report['AssignedTo']['username']) ?></p>
<?php endif; ?>
<section>
<?php foreach ( $report['ReportComment'] as $comment ):?>
<section>
<h3><?php echo __('Comment by %s', $comment['Author']['username']) ?></h3>
<p><?php echo $this->Parsedown->text($comment['comment']) ?></p>
</section>
<?php endforeach; ?>
</section>
<?php if ( $can_edit):?>
<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $report['Report']['id']), array('class' => 'btn btn-default'))?>
<?php endif; ?>
<?php if ( $can_comment ):?>
<?php
/*$this->Html->script('ckeditor/ckeditor', array('inline' => false));*/
echo $this->Form->create('ReportComment');
echo $this->Form->inputs(array('legend' => __('Write A Comment')
                              ,'comment'=> array('class' => 'form-control', 'label' => false, 'rows' => 6, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/'))));
echo $this->Form->end(array('label' => __('Comment'), 'class' => 'btn btn-primary'));
?>
<?php endif; ?>
<?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default')); ?>
</section>