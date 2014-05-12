<?php
echo $this->Form->create('ReportStatus', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Report Status')
                              ,'name'
                              ,'property_string' => array('class' => 'form-control', 'rows' => 3, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/')) ) );
echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();