<h3><?php echo $GLOBALS['Page']->titre; ?></h3>

<?php

if (VerifGedcom( $pdo2 ) == "1") 
	{
	echo '<p>'.SOON.'</p>';
	} 
else 
	{
	echo NO_GEDCOM;
	}

?>