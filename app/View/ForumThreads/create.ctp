<?php /*$this->Html->script('ckeditor/ckeditor', array('inline' => false))*/ ?>
<?php
echo $this->Form->create('ForumThread', $DEFAULT_FORM_OPTIONS);
$inputs =array(
                        'legend' => __('Create New Thread'),
                        'forum_id' => array('type' => 'hidden'),
                        'name' => array('label' => __('Title')),
                        'text' => array('type' => 'textarea', 'class' => 'form-control', 'rows' => 6, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/')),
                        'tags' => array('type' => 'text'),
                        'article' => array('type' => 'checkbox', 'class' => false, 'label' => 'Publish as article'));
if ( $forumPermissions['mod'] ) {
    $inputs['sticky'] = array('type' => 'checkbox', 'class' => false);
}
echo $this->Form->inputs($inputs);

echo $this->Form->submit(__('Create New Thread'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();
?>