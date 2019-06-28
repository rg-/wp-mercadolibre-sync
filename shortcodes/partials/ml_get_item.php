<div class="col-12 gmb-1">
	<div class="card">
		<div class="card-body">
      <div class="row">
      	<div class="col-md-2 text-center d-flex align-items-center justify-content-center">
      		<img src="<?php echo $thumbnail; ?>" class="img-thumbnail" alt="...">
      	</div>
      	<div class="col-md-4 d-flex align-items-start justify-content-start">
      		<div>
        		<div class="card-text"><small class="text-muted">#<?php echo $id;?></small></div>
        		<h5 class="card-title"><?php echo $title;?></h5> 
		       </div>
      	</div>
      	<div class="col-md-3 d-flex align-items-center justify-content-center">
      		<div>
        		<p class="gmb-0">Price: <?php echo $body->currency_id; ?> <?php echo $body->price; ?></p>
        		<p class="gmb-0">Quantity: <?php echo $body->available_quantity; ?></p>
        	</div>
      	</div>
      	<div class="col-md-3 d-flex align-items-center justify-content-center"> 
      		<a target="_blank" href="<?php echo $permalink;?>" class="btn btn-primary">Ver ML</a>
      	</div>
      </div>
    </div>
	</div>
</div>