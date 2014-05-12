<?php
App::uses('AppModel', 'Model');
class Setting
extends AppModel {
	private static $_defaults = array();

	private static $_cache = array();

	public function getValue($key, $defaultValue = null, $useCache = true) {
		if ( isset(Setting::$_cache[$key]) && $useCache ) {
			$res = Setting::$_cache[$key];
		} else {
			$res = $this->find('first', array('conditions' => array('Setting.item_key' => $key)));

			if ( !empty($res) ) {
				$res = unserialize($res['Setting']['item_value']);
			} else {
				if ( isset(Setting::$_defaults[$key]) ) {
					$res = Setting::$_defaults[$key];
				} else {
					$res = $defaultValue;
                }
			}

			if ($useCache) {
				Setting::$_cache[$key] = $res;
      }
		}

		return $res;
	}

	public function setValue($key, $value) {
		$this->create();
		$res = $this->find('first', array('conditions' => array('Setting.item_key' => $key)));

		if ( !empty($res) ) {
			$this->id = $res['Setting']['id'];
    }

		if ( $this->save(array('Setting' => array('item_key' => $key,'item_value' => serialize($value)))) ) {
            Setting::$_cache[$key] = $value;
			return true;
		} else {
			return false;
		}
	}
};