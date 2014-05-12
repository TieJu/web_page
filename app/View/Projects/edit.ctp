<?php
/*$this->Html->script('ckeditor/ckeditor', array('inline' => false));*/
echo $this->Form->create('Project', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Project')
                              ,'name'
                              ,'description' => array('class' => 'form-control', 'rows' => 6, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/'))
                              ,PERMISSION_PROJECT_FILE_REPORT => array('type' => 'select', 'multiple' => 'checkbox', 'options' => $groups)
                              ,PERMISSION_PROJECT_EDIT_REPORT => array('type' => 'select', 'multiple' => 'checkbox', 'options' => $groups)
                              ,PERMISSION_PROJECT_COMMENT_REPORT => array('type' => 'select', 'multiple' => 'checkbox', 'options' => $groups)
                              ,PERMISSION_PROJECT_EDIT_PROJECT => array('type' => 'select', 'multiple' => 'checkbox', 'options' => $groups)
                              ));

echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();
?>