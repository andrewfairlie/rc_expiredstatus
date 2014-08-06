<?php
	class Rc_expiredstatus_ext {
	
	  var $name       = 'Red Carrot Expired Status';
	  var $version        = '1.0';
	  var $description    = 'Sets status of expired entries to "Expired" and refreshes this on each CP login';
	  var $settings_exist = 'n';
	  var $docs_url       = '';
	
    var $settings       = array();

    function __construct($settings = '')
    {
        $this->settings = $settings;
    }
    
    function activate_extension()
		{
		    $this->settings = array(
		        'new_status'   => 'Expired'
		    );
				
		    $data = array(
		        'class'     => __CLASS__,
		        'method'    => 'sweep_expired_entries',
		        'hook'      => 'cp_member_login',
		        'settings'  => serialize($this->settings),
		        'priority'  => 10,
		        'version'   => $this->version,
		        'enabled'   => 'y'
		    );
		
		    ee()->db->insert('extensions', $data);
		}
		
		function update_extension($current = '')
		{
		    if ($current == '' OR $current == $this->version)
		    {
		        return FALSE;
		    }
		
		    if ($current < '1.0')
		    {
		        // Update to version 1.0
		    }
		
		    ee()->db->where('class', __CLASS__);
		    ee()->db->update(
		                'extensions',
		                array('version' => $this->version)
		    );
		}


		function disable_extension()
		{
		    ee()->db->where('class', __CLASS__);
		    ee()->db->delete('extensions');
		}
		
		
		/* On control panel login update all entries that have expired to have a status of "Expired" */
		function sweep_expired_entries($hook_data)
		{
	    $rightNow = time();
      $entries = ee()->db->update('channel_titles', array('status'=>$this->settings['new_status']), array('expiration_date <'=>$rightNow, 'expiration_date !='=>''));
      return;
		}
		

	}
	// END CLASS

?>