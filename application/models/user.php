<?php

	class User extends CI_Model
	{
		public function getUser($user = ''){
			
			$result= $this->db->query("SELECT * FROM user WHERE user='" . $user . "'");
			if ($result->num_rows()>0){
				
				return $result->row();
				
			}else {
				
				return null;	
			}
			
		}

		
	}

