<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
	protected $table ='packages';
	protected $guarded=[];

	public function image()
	{
		return $this->hasMany('App\PaksageImage');

	}
	public function attibute()
	{

		 return $this->hasMany('App\Attibute');
	}
	public function related()
	{
	
	 return $this->hasMany('App\Related_product');


	}
	public function ticket()
	{
		 return $this->hasMany('App\Ticket','product_id');

	}
	
}
