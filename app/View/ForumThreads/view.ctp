<?php /*$this->Html->script('ckeditor/ckeditor', array('inline' => false))*/ ?>
<?php $hlevel = 1; ?>
<section class="well well-lg">
<?php foreach ( $thread['ForumPost'] as $post ) : ?>
<article class="well">
<header>
<h<?php echo $hlevel ?>><?php echo $post['name'] ?></h<?php echo $hlevel ?>>
<?php $hlevel = 5;?>
</header>
<?php echo $this->Parsedown->text($post['post']) ?>
<footer>
<?php echo __('by %s on %s', $post['Author']['username'], CakeTime::niceShort($post['created'])) ?>
<?php if ( isset($forumPermissions[PERMISSION_FORUM_MODERATE]) || $user['id'] === $post['author_id'] ):?>
<?php foreach ( $post['Tag'] as $tag): ?>
<?php $tag_set[] = $this->Html->link($tag['name'], array('controller' => 'tags', 'action' => 'threads', $tag['id'])) ?>
<?php endforeach; ?>
<?php if ( isset($tag_set) ): ?>
<nav>
<header><?php echo __('Tags') ?></header>
<ul style="list-style:none;padding:0">
<li style="display:inline;padding-right:0.25em">
<?php echo implode(', </li><li style="display:inline;padding-right:0.25em">', $tag_set); unset($tag_set) ?>
</ul>
</nav>
<?php endif;?>
<nav>
<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $post['id']), array('class' => 'btn btn-default')) ?>
</nav>
<?php endif; ?>
</footer>
</article>
<?php endforeach; ?>
<?php if ( isset($forumPermissions['write']) && !$thread['ForumThread']['locked'] ) : ?>
<?php
echo $this->Form->create('ForumPost', $DEFAULT_FORM_OPTIONS);
$inputs = array(
                        'legend' => __('Reply'),
                        'name' => array('label' => __('Title')),
                        'post' => array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/')),
                        'tags' => array('type' => 'text'));
echo $this->Form->inputs($inputs);

echo $this->Form->end(array('class' => 'btn btn-primary'));
?>
<?php endif; ?>
<?php echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default')) ?>
</section>