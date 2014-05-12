<?php

class ProjectUserGroupPermission
extends AppModel {
  public $belongsTo = array( 'Project', 'UserGroup' );
};