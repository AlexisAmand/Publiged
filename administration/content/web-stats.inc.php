<?php
$utilisateur = new Utilisateurs();
$utilisateur->information($pdo, $_SESSION['login']);
?>

<div class="container-fluid px-4">
	
	<h1 class="h3 mt-4"><?php echo HELLO." ".$utilisateur->login; ?>.</h1>
	
		<ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="index.php?page=main"><?php echo DASHBOARD; ?></a></li>
		    <li class="breadcrumb-item"><a href="#"><?php echo BCK_RUB_TITLE_4; ?></a></li>
		    <li class="breadcrumb-item active" aria-current="page"><?php echo ASIDE_ADMIN_11; ?></li>
		</ol>
        
        <div class="row">
        
        	<div class="col-xl-12">
				<div class="card mb-4">
					<div class="card-header">
						<i class="bi bi-graph-up me-2"></i><?php echo ASIDE_ADMIN_11; ?>
					</div>
					<div class="card-body">
					    
					<?php /*TODO : tout le monde peut voir les stats du site */ ?>	

					</div>
				</div>
			</div> 
		</div>
</div>