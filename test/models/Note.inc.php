<?php
	class Note extends ActiveRecord{
		protected static s_table='notes';
		protected static s_field=array('title','excerpt','content','user_id','user_like','user_dislike','updated_at','created_at');
		
		protected function initField(){

		}
	}
?>