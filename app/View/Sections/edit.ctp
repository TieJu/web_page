<?php
echo $this->Form->create('Section', $DEFAULT_FORM_OPTIONS);
echo $this->Form->inputs(array('legend' => __('Page Section')
                              ,'name'
                              ,'controller'
                              ,'action'
                              ,'params' => array('class' => 'form-control', 'rows' => 3, 'after' => $this->Html->link('Markdown syntax', 'http://www.markdown.de/'))
                              ,'ordering'
							                ,'parent_id'
						                  ,'right' => array('type' => 'checkbox', 'class' => false)
                              ));
echo $this->Form->submit(__('Save'), array('class' => 'btn btn-primary'));
echo $this->Html->link(__('Back'), $back_url, array('class' => 'btn btn-default'));
echo $this->Form->end();