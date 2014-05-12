<?php /*$this->Html->script('ckeditor/ckeditor', array('inline' => false))*/ ?>
<?php
echo $this->Form->create('ForumPost', $DEFAULT_FORM_OPTIONS);
$inputs = array(
                        'legend' => __('Reply'),
                        'name' => array('label' => __('Title')),
                        'post' => array('type' => 'textarea', 'label' => false, 'class' => 'form-control', 'rows' => 6, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/')),
                        'tags' => array('type' => 'text'));
if ( $is_first_post ) {
  if ( $is_mod ) {
    $inputs['sticky'] = array('type' => 'checkbox', 'class' => false);
  }
  $inputs['article'] = array('type' => 'checkbox', 'class' => false, 'label' => 'Publish as article');
}
echo $this->Form->inputs($inputs);
echo $this->Form->submit(__('Update'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();
?>