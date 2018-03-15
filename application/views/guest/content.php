   <!-- Main Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
          <?php 
			foreach ($consulta->result() as $fila) {
		  ?>	  
			  <div class="post-preview">
				<a href="<?= base_url()?>article/post/<?= $fila->post;?>">
				  <h2 class="post-title">
					<?= $fila->post;?>
				  </h2>
				  <h3 class="post-subtitle">
					<?= $fila->descripcion;?>
				  </h3>
				</a>
				<p class="post-meta">Posted by
				  <a href="#">Start Bootstrap</a>
				  on 
				  <?= $fila->fecha;?>
				  </p>
			  </div>
			  <hr>
          <?php 
		  
		  }
		  ?>        
          <!-- Pager -->
          <div class="clearfix">
            <a class="btn btn-primary float-right" href="#">Older Posts &rarr;</a>
          </div>
        </div>
      </div>
    </div>

    <hr>
