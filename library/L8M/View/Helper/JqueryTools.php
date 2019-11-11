<?php

class L8M_View_Helper_JqueryTools
{

    public function jqueryTools()
    {
		$script = '';

		if (Zend_Registry::isRegistered('jQueryUI') &&
    		Zend_Registry::get('jQueryUI') !== FALSE) {

    		throw new L8M_Exception('jQueryUI has to be disabled before using jQueryTools.');
    	}

    	if (Zend_Registry::isRegistered('jQueryTools') &&
    		Zend_Registry::get('jQueryTools') !== FALSE) {

			$jqueryTools = Zend_Registry::get('jQueryTools');

			if (isset($jqueryTools['enable']) &&
				$jqueryTools['enable'] == TRUE &&
				isset($jqueryTools['version']) &&
				isset($jqueryTools['cdn_url']) &&
				isset($jqueryTools['type'])) {

				$jqueryToolsType = $jqueryTools['type'];
				$jqueryToolsCdnUrl = $jqueryTools['cdn_url'];
				$jqueryToolsVersion = $jqueryTools['version'];

				if (isset($jqueryTools['types'][$jqueryToolsType]['url'])) {
					$jqueryToolsUrl = $jqueryTools['types'][$jqueryToolsType]['url'];

					$script = '<script type="text/javascript" src="' . $jqueryToolsCdnUrl . $jqueryToolsVersion . '/' . $jqueryToolsUrl . '"></script>';
				}


			}
    	}
        return $script;
    }
}
