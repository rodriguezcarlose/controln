<?php

	class Post extends CI_Model
	{
		public function getPost(){
			return $this->db->get('post');
		}

		public function getPostByName($Name=''){
			
			$result= $this->db->query("SELECT * FROM post WHERE post='" . $Name . "'");
			
			return $result->row();
		}		
	}

