<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_model
{
	
	public function __construct()
	{
		parent::__construct();
	}
	//query
	public function colors(){
		// $colors = ['#ff0000', '#0000cc', '#00cc00', '#ff00ff', '#e6e600', '#008080', '#00cc66', 
		// 			'#333300', '#8a8a5c', '#00e6b8', '#c44dff', '#003300', '#336600', '#b38600',
		// 			'#ff6600', '#008000', '#669999'];

		$colors = array('#ff0000', '#0000cc', '#00cc00', '#ff00ff', '#e6e600', '#008080', '#00cc66', 
					'#333300', '#8a8a5c', '#00e6b8', '#c44dff', '#003300', '#336600', '#b38600',
					'#ff6600', '#008000', '#669999');
		
		return $colors;
	}

	//misc
}