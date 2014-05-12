<?php
/*$this->Html->script('ckeditor/ckeditor', array('inline' => false));*/
echo $this->Form->create('ProjectVersion', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Project Version')
                              ,'name'
                              ,'description' => array('class' => 'form-control', 'rows' => 6, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/'))
                              ,'status_id' => array('label' => __('Status'))
                              ));
echo $this->Html->link(__('Add Version Status Type'), array('controller' => 'project_version_statuses', 'action' => 'edit', 'back' => array('controller' => 'project_versions', 'action' => 'edit', $id)), array('class' => 'btn btn-default'));
echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();
?>