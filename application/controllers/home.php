<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		$data = array('title'=>'Home');
		$this->load->view("guest/head",$data);
		
		
		$data = array('app'=>'Blog');
		$this->load->view("guest/nav",$data);
		
		$data = array('post' => 'Blog', 'descripcion'=>'Bienvenido a mi pagina Web CodeIgniter');
		$this->load->view("guest/header",$data);

		// $this->load->model('post'); -- Si se va a utilizar el Post en toda la pagina web, se puede cargar en el autoload para que este disponible
		$result= $this->post->getPost();
		
		$data = array('consulta'=>$result);
		
		$this->load->view("guest/content",$data);
		$this->load->view("guest/footer");


		}
	

}
