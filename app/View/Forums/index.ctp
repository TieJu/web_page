<section class="well well-lg">
<header>
<h1>
<?php
if ( !is_null($forum['Parent']['name']) ) {
  echo __('%s &raquo; %s', $forum['Parent']['name'], $forum['Forum']['name']);
} else {
  echo $forum['Forum']['name'];
}
?>
</h1>
</header>
<?php if ( count($forum['SubForum']) > 0 ): ?>
<table class="table table-hover">
<?php if ( !is_null($forum['Forum']['id']) ): ?>
<caption><h2><?php echo __('Subforums') ?></h2></caption>
<?php endif; ?>
<thead>
<tr>
<th><?php echo __('Name') ?></th>
<th><?php echo __('Threads') ?><th>
</tr>
</thead>
<tbody>
<?php foreach ( $forum['SubForum'] as $subForum ): ?>
<tr>
<td><?php echo $this->Html->link($subForum['name'], array('action' => 'index', $subForum['id'])) ?></td>
<td><?php echo count($subForum['ForumThread']); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
<?php if ( count($forum['ForumThread']) ) : ?>
<table class="table table-hover">
<caption>
<h2><?php echo __('Threads') ?></h2>
</caption>
<thead>
<tr>
<th><?php echo __('Name') ?></th>
<th><?php echo __('Posts') ?></th>
<th><?php echo __('Last Post By') ?></th>
<th></th>
</tr>
</thead>
<tbody>
<?php foreach ( $forum['ForumThread'] as $forumThread ) : ?>
<tr>
<td>
<?php if ( !empty($forumThread['sticky']) && 1 == $forumThread['sticky'] ): ?>
<span class="glyphicon glyphicon-pushpin" title="<?php echo __("Sticky") ?>"></span>
<?php endif ?>
<?php if ( !empty($forumThread['article']) && 1 == $forumThread['article'] ): ?>
<span class="glyphicon glyphicon-bullhorn" title="<?php echo __("Article") ?>"></span>
<?php endif ?>
<?php if ( !empty($forumThread['locked']) && 1 == $forumThread['locked'] ): ?>
<span class="glyphicon glyphicon-lock" title="<?php echo __("Locked") ?>"></span>
<?php endif ?>
<?php echo $this->Html->link($forumThread['name'], array('controller' => 'forum_threads', 'action' => 'view', $forumThread['id'])); ?>
</td>
<td><?php echo count($forumThread['ForumPost']); ?></td>
<td><?php echo $forumThread['ForumPost'][count($forumThread['ForumPost'])- 1]['Author']['username'] ?> </td>
<?php if ( isset($forumPermissions['mod']) ): ?>
<td>
<?php echo $this->Html->link('<span class="glyphicon glyphicon-lock" title="' . __("Toggle Lock") .'"></span>', array('controller' => 'forum_threads', 'action' => 'toggle_lock', $forumThread['id']), array('escape' => false)); ?>
<?php echo $this->Html->link('<span class="glyphicon glyphicon-remove" title="' . __("Delete") .'"></span>', array('controller' => 'forum_threads', 'action' => 'delete', $forumThread['id']), array('escape' => false), __('Are you soure to delete the forum thread %s', $forumThread['name'])); ?>
</td>
<?php else: ?>
<td></td>
<td></td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>
<table>
<tfoot>
<tr>
<td colspan="3">
<?php if ( isset($forumPermissions['write']) ) : ?>
<?php echo $this->Html->link(__('Create New Thread'), array('controller' => 'forum_threads', 'action' => 'create', $forum['Forum']['id']), array('class' => 'btn btn-default')); ?>
<?php endif; ?>
</td>
</tr>
</tfoot>
</table>
<?php if ( $forum['Forum']['id'] != 0 ) : ?>
<?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-primary')) ?>
<?php endif; ?>
</sectuib>