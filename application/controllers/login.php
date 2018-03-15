<?php

	class Login extends CI_Controller
	{
		public function index (){
		
			$user= $this->input->post('user');
			$password = $this->input->post('password');
			
			$this->load->model('user');
			$fila=$this->user->getUser($user);
			
			if($fila != null){
				if ($fila->password == $password){
					$data = array(
							'user'=> $fila->user,
							'id'=> $fila->id,
							'login'=> true
					);
					$this->session->set_userdata($data);
					header("Location: " . base_url()); // redirigir a otra pagina cuando hace el login
				}else{
					header("Location: " . base_url());
				}
			}else{
					header("Location: " . base_url());
				}
		}
		
		public function logout (){
			$this->session->sess_destroy();
			header("Location: " . base_url());
		}
		
		
	}

?>