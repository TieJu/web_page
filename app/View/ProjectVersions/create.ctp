<?php
/*$this->Html->script('ckeditor/ckeditor', array('inline' => false));*/
echo $this->Form->create('ProjectVersion', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Project Version')
                              ,'name'
                              ,'description' => array('class' => 'form-control', 'rows' => 6, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/'))
                              ,'status_id' => array('label' => __('Status'))
                              ));

echo $this->Form->submit(__('Add Version'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();
?>