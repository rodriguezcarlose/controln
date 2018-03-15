<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand" href="index.html"><?= $app ?></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact</a>
            </li>
			
			<?php if ($this->session->userdata('login')) { ?>
			<li class="nav-item">
              <a class="nav-link" href="<?= base_url() ?>login/logout">Logout</a>
            </li>				
			<?php }else{ ?>
			<li class='dropdown'>
				  <a class='dropdown-toggle' href='#' data-toggle='dropdown' style="background: none;">Login<strong class='caret'></strong></a>
				  <div class='dropdown-menu' style='padding: 10px; padding-bottom: 0px; background: none; width: 200px;'>
					<form action='<?= base_url() ?>login' method='post' accept-charset='UTF-8' role="form">
					  <div class='form-group'>
						<input class='form-control large' style='text-align: center;' type='text' name='user' placeholder='usuario'/>
					  </div>
					  <div class='form-group'> 
						<input class='form-control large' style='text-align: center;' type='password' name='password' placeholder='contraseña' />
					  </div>
					  <div class='form-group'>
						<button class='btn btn-primary' style='width: 180px;' type='submit'>Ingresar</button>
					  </div>
					  </form>
				  </div>
            </li>
			<?php } ?>
          </ul>
        </div>
      </div>
    </nav>