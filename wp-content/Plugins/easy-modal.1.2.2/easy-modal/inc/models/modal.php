<?php
class Model_EM_Modal
{
    protected $_data = array(
		'id' => '',
		'name'	=> 'change_me',
		'sitewide' => false,
		'title' => '',
		'content' => '',
		
		'theme' => 1,
		
		'size' => 'normal',
		'userHeight' => 0,
		'userHeightUnit' => 0,
		'userWidth' => 0,
		'userWidthUnit' => 0,
		
		'animation' => 'fade',
		'direction' => 'bottom',
		'duration' => 350,
		'overlayClose' => false,
		'overlayEscClose' => false
	);
	
	public function get($key)
	{
		if(array_key_exists($key, $this->_data))
		{
			return $this->_data[$key];
		}
		else
		{
			return NULL;
		}
	}
	public function set($key, $value)
	{
		if(array_key_exists($key, $this->_data))
		{
			$this->_data[$key] = stripslashes_deep($value);
		}
		return $this;
	}
	
	public function __construct($id)
	{
		$modal = get_option('EasyModal_Modal-'.$id);
		if(is_array($modal))
		{
			$this->set_fields($modal);
		}
		return $this;
	}
	public function validate()
	{
		foreach($this->_data as $key => $val)
		{
			switch($key)
			{
				case 'name':
				case 'title':
					$this->_data[$key] = sanitize_text_field($val);
					break;
				case 'content':
					$this->_data[$key] = balanceTags(wp_kses_post($val));
					break;
				case 'sitewide':
				case 'overlayClose':
				case 'overlayEscClose':
					$this->_data[$key] = ($val === true || $val === 'true') ? true : false;
					break;
				case 'duration':
				case 'userHeight':
				case 'userWidth':
					if(is_numeric($val))
					{
						$this->_data[$key] = intval($val);
					}
					break;
				case 'size':
					if(in_array($val,array('','tiny','small','medium','large','xlarge','custom')))
					{
						$this->_data[$key] = $val;
					}
					break;
				case 'animation':
					if(in_array($val,array('fade','fadeAndSlide','grow','growAndSlide')))
					{
						$this->_data[$key] = $val;
					}
					break;
				case 'direction':
					if(in_array($val,array('top','bottom','left','right','topleft','topright','bottomleft','bottomright','mouse')))
					{
						$this->_data[$key] = $val;
					}
					break;
				case 'userHeightUnit':
				case 'userWidthUnit':
					if(in_array($val,array('px','%','em','rem')))
					{
						$this->_data[$key] = $val;
					}
					break;
			}
		}
	}
	
	public function set_fields(array $fields)
	{
		if(!is_array($fields))
		{
			return false;
		}
		$fields = stripslashes_deep($fields);
		foreach($fields as $key => $val)
		{
			if(array_key_exists($key, $this->_data))
			{
				$this->_data[$key] = $val;
			}
		}
		return $this;
	}
	public function save()
	{
		if($this->validate() === true)
		{
			update_option('EasyModal_Modal-'.$this->_data['id'], $settings);
		}
		return $this;
	}
    /**
     * Check if transaction id is already registered
     *
     * @param $transaction_id
     * @param $payment_status
     * @return bool
     */
    public function is_unique($transaction_id, $payment_status)
    {
        return ! (bool) DB::select(array(DB::expr('COUNT("*")'), 'total_count'))
            ->from($this->_table_name)
            ->where('transaction_id', '=', $transaction_id)
            ->and_where('payment_status', '=', $payment_status)
            ->execute($this->_db)
            ->get('total_count');
    }
}