<?php

/* ---------------------------------------- */
/* Cette class permet des stats sur la base */
/* ---------------------------------------- */

class BasesDeDonnees
	{

	/*	Cette méthode retourne la liste des titres des articles */
		
	public function ListeTitreArticle($pdo2)
		{
		  	$sql = "SELECT * FROM articles ORDER BY date DESC";
		  	$req = $pdo2->prepare($sql);
		  	$req->execute();
		  	$row = $req->fetchAll();
		  	return $row;	    
		}
				
	/* Cette méthode retourne le "top" pour la page de stats */

	public function Top($pdo2)
		{
			$req_top = "SELECT * FROM configuration WHERE nom = 'top'";
			$res_top = $pdo2->prepare($req_top);
			$res_top->execute();	
			$row = $res_top->fetch(PDO::FETCH_ASSOC);
			return $row['valeur'];
		}

	/* Cette méthode retourne le nombre d'individus présents dans la base de données */

	public function NombreIndividu($pdo2)
		{
			$requete = "SELECT * FROM individus";
			$req = $pdo2->prepare ( $requete );
			$req->execute ();
			return $req->rowCount ();	
		}	

	/* Cette méthode retourne le nombre de patronymes présents dans la base de données */

	public function NombrePatro($pdo2)
		{
			$req_nb_patro = "SELECT distinct surname FROM individus";
			$req = $pdo2->prepare ( $req_nb_patro );
			$req->execute ();
			return $req->rowCount ();
		}	

	/* Cette méthode retourne le nombre total d'hommes dans la base de données */

	public function NombreHommes($pdo2)
		{
			$req_nb_patro = "SELECT * FROM individus WHERE sex LIKE '%M%'";
			$req = $pdo2->prepare ( $req_nb_patro );
			$req->execute ();
			return $req->rowCount ();
		}

	/* Cette méthode retourne le nombre total de femmmes dans la base de données */

	public function NombreFemmes($pdo2)
		{
			$req_nb_patro = "SELECT * FROM individus WHERE sex LIKE '%F%'";
			$req = $pdo2->prepare ( $req_nb_patro );
			$req->execute ();
			return $req->rowCount ();
		}

	/* Cette méthode retourne le nombre total d'évenements dans la base de données */

	public function NombreEvenements($pdo2)
		{
			$req_nb_eve = "SELECT * FROM evenements";
			$req = $pdo2->prepare ( $req_nb_eve );
			$req->execute ();
			return $req->rowCount ();
		}

	/* Cette méthode retourne le nombre total d'évenements dans la base de données */

	public function NombreSources($pdo2)
		{
			$req_nb_src = "SELECT * FROM sources";
			$req = $pdo2->prepare ( $req_nb_src );
			$req->execute ();
			return $req->rowCount ();
		}	

	/* Cette méthode retourne le nombre total d'enfants dans la base de données */

	public function NombreEnfants($pdo2)
		{
			$req_nb_enfant = "SELECT distinct enfant FROM familles";
			$req = $pdo2->prepare ( $req_nb_enfant );
			$req->execute ();
			return $req->rowCount ();
		}
		
	/* Cette méthode retourne le nombre total de couples dans la base de données */

	public function NombreCouples($pdo2)
		{
			$req_nb_couple = "SELECT distinct pere, mere FROM familles";
			$req = $pdo2->prepare ( $req_nb_couple );
			$req->execute ();
			return $req->rowCount ();	
		}

	/* Cette méthode retourne le nombre total de lieux dans la base de données */

	public function NombreLieux($pdo2)
		{
			$req_nb_lieu = "SELECT * FROM lieux GROUP BY ville";
			$req = $pdo2->prepare ( $req_nb_lieu );
			$req->execute ();
			return $req->rowCount ();
		}

	/* Cette méthode retourne le nombre de Famille dans la base de données */

	public function NombreFamilles($pdo2)
		{
			$req_famille = "SELECT * FROM familles group by pere,mere";
			$req = $pdo2->prepare ( $req_famille );
			$req->execute ();
			return $req->rowCount ();
		}

	}

class pages 
	{
	public $nom;
	public $titre;
	public $description;
	public $rubrique;
	
	public function __construct($pdo2)
		{
		
		/* Si le param page est pas là redirection sur index */
		if (!isset($_GET ['page']))
			{
			header('Location: index.php?page=blog');
			} 	
		else
			{
			$this->nom = $_GET['page'];	
			}
			
		$sql = "select * from pages where nom = :nom";
		$resultat = $pdo2->prepare ( $sql );
		$resultat->bindParam ( ':nom', $_GET ['page'] );
		$resultat->execute (); 	
		$nb = $resultat->rowCount (); 	
		
		if ($nb != 0)
			{
			/* récup les infos de la page 
			 * avec un seul fetch comme dans afficherMeta()
			 */	
			
			$row = $resultat->fetch ();
			
			$this->nom = $row['nom'];
			$this->titre = $row['titre'];
			
			/* rubrique pour le fil d'ariane, entre les deux / */
			
			switch($this->nom)
				{
				case "stats" :
				case "sources" :
				case "images" :
				case "anniversaires" :
					$this->rubrique = ASIDE_2;
					break;
				case "lieux" :
				case "cartographie" :
				case "patrolieux" :
					$this->rubrique = ASIDE_3;
					break;
				case "patro" :
				case "eclair" :
				case "sosa" :
				case "fiche" :
				case "lieuxpatro" :
				case "liste_patro" :
					$this->rubrique = ASIDE_4;
					break;
				case "eve" :
					$this->rubrique = ASIDE_5;
					break;
				case "categories" :
					$this->rubrique = ASIDE_BLOG_2;
					break;
				case "search" :
					$this->rubrique = MENU_RESULT;
					break;
				case "blog" :
					$this->rubrique = ASIDE_BLOG_1;
					break;
				case "credits" :
					$this->rubrique = "Divers";
					
					/* TODO : Ajouter dans le fichier fr.php */
					
					break;
				case "article" :
					$this->rubrique = ARTICLES;
					break;
					/*
					 *  default:
					 *  $page->rubrique = PILLMENU_2;
					 */
				} 
			
			
							
			}
		else 
			{
			/* le param n'a pas été trouvé dans la BD... donc redirection */
			header('Location: index.php?page=blog');
			}
		
		}

	/* cette méthode récupére le title et la meta description pour le HEAD */
	
	public function AfficherMeta($pdo2)
		{	
		$sqlMeta = $pdo2->prepare("select * from pages where nom = :page");
		$sqlMeta->bindParam ( ':page', $_GET['page'] ); 	
		$sqlMeta->execute();
		$data = $sqlMeta->fetch();
		
		/* affichage du title */
		echo "<title>".$data['titre']." | ".$GLOBALS['NomduSite']."</title>\n";
		/* affichage de la meta description */
		echo "<meta name='description' content='".$data['description']."'>\n";
		}

	/* cette méthode récupére le template perso */

	public function AfficherCSS($pdo2)
		{
		$sqlHeader = $pdo2->prepare("SELECT * FROM configuration WHERE nom = 'template'");
		$sqlHeader->execute();
		$data = $sqlHeader->fetch();

		/* affichage du template perso */

		echo '<link href="templates/'.$data['valeur'].'/bootstrap.min.css" rel="stylesheet">';

		/* récupération des feuilles de styles obligatoires */

		echo '<link href="templates/system/css/commons.css" rel="stylesheet">';
		echo '<link href="js/datatables/datatables/css/dataTables.bootstrap4.min.css" rel="stylesheet">';

		/* TODO : récupération et affichage de favicon perso */

		$req = $pdo2->prepare("SELECT * FROM configuration WHERE nom='favicon'");
		$req->execute();
		$rowFavicon = $req->fetch();
		$NomDuFavicon = $rowFavicon['valeur'];
		// echo '/templates/'.$data['valeur'].'/images/'.$NomDuFavicon; 
		echo '<link rel="icon" href="/templates/'.$data['valeur'].'/images/'.$NomDuFavicon.'">'; 
		// echo '<link rel="icon" href="img/icon.jpg">';
		// echo '<link rel="icon" type="image/gif" href="img/icon.jpg" />';
		}

	/* Cette méthode affiche le pied de page du site */

	public function AfficherPillmenu() 
		{
		include ('include/pillmenu.inc');
		}

	public function AfficherHeader() 
		{
		include ('include/header.inc');
		}

	/* Cette méthode affiche le aside et le contenu du site*/

	public function AfficherContenu($pdo2) 
		{		
		$balise = ($GLOBALS['aside']==1)?'<article class="col-md-9">':'<article class="col-md-12">';
		echo $balise;			
		include ('content/' . $this->nom . '.php');
		echo '</article>';
		}
	
	public function AfficherAside($pdo2) 
		{

		/* Par défaut, on supppose que le aside est affiché */	
		
		$GLOBALS['aside'] = '1';
			
		$sqlPages = "select * from pages";
		$reqPages = $pdo2->prepare($sqlPages);
		$reqPages->execute();
		
		/* TODO: ici un test si l'user veut le menu à droite ou à gauche, selon le cas il me semble qu'il existe un class bootstrap qui permet de choisir d'autre des colonnes (aside et article, ou article puis aside. */
				
		$balise = ($GLOBALS['aside']==1)?'<aside class="col-md-3">':'<aside class="col-md-12">';		
		echo $balise;
					
		while($row = $reqPages->fetch())
			{
				
			if ($row['nom'] == $this->nom)	
				{
				switch ($row['section'])
					{
					
					/* $aside est une sorte de booléen. Si = 1, il y a un menu à mettre dans l'aside, si = 0, il n'y a pas de menu à afficher, comme dans contact par exemple */	
						
					case 'blog':
						include ('include/sidebar-blog.inc');
						$GLOBALS['aside'] = 1;
						break;
					case 'genealogie':
						include ('include/sidebar-genealogie.inc');
						$GLOBALS['aside'] = 1;
						break;
					case 'search':
						include ('include/sidebar-search.inc');
						$GLOBALS['aside'] = 1;
						break;
					default:
						$GLOBALS['aside'] = 0;
						/* TODO: pas de sidebar, on peut afficher l'article sur toute la largeur de la page */
					}
				}
			}
		
		echo '</aside>';
						
		}

	/* Cette méthode affiche le pied de page du site */

	public function AfficherFooter() 
		{
		include ('include/footer.inc');
		}

	/* Todo : Je ne suis pas sûr que cette méthode serve encore */
	
	public function Titre() 
		{
		return SITE_TITLE . " - " . $this->titre;
		}
}

/* classes de partie généalogie */

class evenements 
	{
	public $ref;
	public $nom;
	public $note;
	public $source;
	public $evenement;
	public $date;
	public $lieu;
	public $type;
	}

/* classes de la partie blog */

class commentaires 
	{
	public $article;
	public $nom_auteur;
	public $email_auteur;
	public $url_auteur;
	public $contenu;
	public $today;
	
	/* affiche un lien vers les commentaires */
	
	public function AfficheLien($id,$pdo3)
		{		
		$sqlCommentaires = "select * from commentaires where id_article='{$id}'";
		$reqCommentaires = $pdo3->prepare($sqlCommentaires);
		$reqCommentaires->execute();
		
		echo "<div class='row'>";
			echo "<div class='col-md-12' id='commentaires'>";
			echo "<a href='index.php?page=article&id=".$id."'>[".SEECOMS."] (".$reqCommentaires->rowCount().")</a>";
			echo "</div>";
		echo "</div>";
		}
		
	/* affiche les commentaires */
	
	public function AfficheCommentaire($id,$pdo3)
		{
			$sqlCommentaires = "select * from commentaires where id_article='{$id}' ORDER BY date_com DESC";
			$reqCommentaires = $pdo3->prepare($sqlCommentaires);
			$reqCommentaires->execute();
			
			echo "<div class='row'>";
			echo "<div class='col-md-12' id='commentaires'>";
			
			while ($data = $reqCommentaires->fetch(PDO::FETCH_ASSOC))
				{
				echo "<div class='card card-alert'>";
					echo "<div class='card-header'>";
					$DateTemp = date("Y-m-d", strtotime($data['date_com']));
					$DateCommentaire = explode("-" , $DateTemp);
					echo  $data ['nom_auteur'].COMMENTS.$DateCommentaire[2]." ". MoisEnLettres($DateCommentaire[1])." ". $DateCommentaire[0];
					echo "</div>";
					echo "<div class='card-body'>";
					echo $data ['contenu'];
					echo "</div>";
				echo "</div>";
				}
			
			echo '</div>';
			echo '</div>';
			
			echo '<div class="col-md-6 col-xs-12 col-sm-6">';
			
			echo '<form action="index.php?page=article&id='.$id.'" method="POST" class="form-vertical">';
			
			echo '<div class="form-group">';
			echo '<label for="pseudo">Pseudo</label> <input id="pseudo" type="text" name="pseudo" class="form-control" />';
			echo '</div>';
			
			echo '<div class="form-group">';
			echo '<label for="site">Site (ou blog)</label> <input id="site" type="text" name="site" class="form-control" />';
			echo '</div>';
			
			echo '<div class="form-group">';
			echo '<label for="email">Email</label> <input id="email" type="text" name="email" class="form-control" />';
			echo '</div>';
			
			echo '<div class="form-group">';
			echo '<label for="email">Commentaire</label>';
			echo '<textarea id="commentaire" name="message" class="form-control"></textarea>';
			echo '</div>';
			
			echo '<input type="submit" class="btn btn-default">';
			
			echo '</form>';
			
			echo '</div>';
			
		}
	}
	
class articles 
	{
	public $ref;
	public $auteur;
	public $titre;
	public $date;
	public $categorie;
	public $contenu;
		
	/* Cette méthode récupére la liste de tous les articles */

	/*
	
	public function RecupererLesArticles($pdo3)
		{
			$res = $pdo3->prepare ("SELECT * FROM articles");
			$res->execute ();
			return  $res->fetchAll(PDO::FETCH_ASSOC);
		}
	
	*/
	
	/* Cette méthode affiche un article */	

	public function Afficher($pdo3)
		{
		
		/* Titre de l'article */
			
		echo "<div class='row mt-5'>";			
			echo "<div class='col-md-12'>";
			echo "<h3><a href='index.php?page=article&id=".$this->ref."'>".html_entity_decode ($this->titre)."</a></h3>"; 
			echo "</div>";		
		echo "</div>";
		
		echo "<div class='row'>";
		
		/* Auteur et date de l'article */
		
		echo "<div class='col-md-10'>";		
			echo "<p>".AUTHOR.$this->auteur;
			echo DATE.$this->date;
			echo RUBRIC;
			echo "<a href='index.php?page=categories&id=".$this->categorie."'>".get_category_name($pdo3, $this->categorie)."</a>";		
		echo "</div>";
		
		/* affichage des boutons d'export : pdf, mail, print */
		
		echo "<div class='col-md-2'>";
			echo "<p>";
			echo "<a href='pdf.php?page=categories&id=".$this->ref."'><i class='far fa-file-pdf fa-1x'></i></a>&nbsp;&nbsp;";
			echo "<a href='print.php?id=".$this->ref."'><i class='fas fa-print fa-1x'></i></a>&nbsp;&nbsp;";
			echo "<a href='#'><i class='fas fa-envelope-square fa-1x'></i></a>&nbsp;&nbsp;";
			echo "</p>";
		echo "</div>";
		
		echo "</div>";

		/* Contenu de l'article */
		
		echo "<div class='row'>";
			echo "<div class='col-md-12'>";
			echo "<p>".$this->contenu."</p>";		
			echo "</div>";
		echo "</div>";
		
		}
	}

/* classes pour la lecture du gedcom */
	
class logiciels 
	{
	public $nom;
	public $nomcomplet;
	}

class uploaders 
	{
	public $name;
	public $mail;
	public $adresse;
	public $www;
	}
	
class famille 
	{
	public $ref;
	public $husb;
	public $wife;
	public $chil;
	}
	
class individus 
	{
	public $ref;
	public $nom;
	public $prenom;
	public $surname;
	public $sexe;
	public $note;
	}
	
class evenement 
	{
	public $indiv;
	public $nom;
	public $date;
	public $type;
	public $place;
	public $source;
	public $note;
	}
	
class lieu 
	{
	public $ville;
	public $cp;
	public $dep;
	public $region;
	public $pays;
	public $continent;
	}
	
class source 
	{
	public $ref;
	public $titre;
	public $nom;
	public $origine;
	public $media;
	}
	
class gedfichiers 
	{
	public $source;
	public $nom;
	public $lieu;
	}
	
class medias 
	{
	public $ref;
	public $format;
	public $fichier;
	public $source; /* numéro de la source dont le média dépend */
	}

?>