<?php
echo $this->Form->create('ReportPriority', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Report Priority')
                              ,'name'
                              ));
echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();