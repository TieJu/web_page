<?php
/*$this->Html->script('ckeditor/ckeditor', array('inline' => false));*/
echo $this->Form->create('Report', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Edit Report')
                              ,'version_id'
                              ,'target_version_id' => array('options' => $versions)
                              ,'priority_id'
                              ,'assigned_to_id' => array('options' => $users)
                              ,'status_id'
                              ,'name'
                              ,'description' => array('class' => 'form-control', 'rows' => 6, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/'))
                              ));

echo $this->Form->submit(__('Update'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();