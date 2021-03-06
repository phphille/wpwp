<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php', // Theme customizer
  'lib/nav.php', //bootstrap nav
  'lib/dashboard.php',
  'lib/userroles.php',
  'lib/userprofiles.php',
  'lib/postRequests.php',
  'lib/admin_custom-post-functions.php',
  'lib/admin_products.php',
  'lib/admin_companies.php',
  'lib/admin_lock-orders.php',
  'lib/admin_welcome.php',
  'lib/admin_faq.php',
  'lib/korvlador.php',
  'lib/PHPExcel-1.8/Classes/PHPExcel.php'
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


function dump($array) {
  echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}

function get_current_user_role() {
  $currentUser = new WP_User( get_current_user_id());
  return $currentUser->roles[0];
}


function checkOrgNbr($nummer) { // $nummer ska vara på formen YYMMDDXXXK

   // Dela upp $nummer i ensiffriga delar
   $i = 0;
   while ($i < 9) {
      $delsiffra[$i] = substr($nummer, $i, 1);
      $i++;
   }

   $given_kontrollsiffra = substr($nummer, 9, 1);

   /************************************************************
    Om delsiffra 2 är 1 och delsiffra 3 större än 2 så är det
    inte ett korrekt person- eller organisationsnummer.
    Gäller även om delsiffra 2 är 0 och delsiffra 3 är mindre
    än 1. Alltså:
    Personnummer har månad...: 01-12
    Organ.nummer har "månad".: 20-99
   ************************************************************/
   if ($delsiffra[2] == 0 && $delsiffra[3] < 1 || $delsiffra[2] == 1 && $delsiffra[3] > 2) {
      return false;
   }

   // Dubblera var annan delsiffra
   $j = 0;
   while ($j < 9) {
      $delsiffra[$i] *= 2;
      $j += 2;
   }


   /************************************************************
    Summera varje delsiffra, notera att man summerar tal större
    än 9 genom att dela det i dess ingående värden och adderar
    dessa var för sig.
    T.ex. 12 + 4 + 8 + 10 osv. räknar samman genom att skriva
    om det till: 1 + 2 + 4 + 8 + 1 + 0 osv.
   ************************************************************/
   $summa = 0;
   for ($i = 0; $i < 9; $i++) {
      if ($i%2 == 0) {
         $delsumma = $delsiffra[$i] - (($delsiffra[$i] % 10) / 10);
         $delsumma += $delsiffra[$i] % 10;
         $summa += $delsumma;
      } else {
         $summa += $delsiffra[$i];
      }
   }
   $utraknad_kontrollsiffra = (10 - ($summa % 10)) % 10;
   if ($utraknad_kontrollsiffra == $given_kontrollsiffra) {
      return true;
   } else {
      return false;
   }
}


class Products_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'products_widget', // Base ID
			'Korvlådor', // Name
			array('description' => __( 'Visar alla korvlådor'))
			 );
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
		return $instance;
	}

	
	function widget($args, $instance) {
			extract( $args );
			echo $before_widget;
			$this->getRealtyListings();
			echo $after_widget;
	}
	
	function getRealtyListings() { //html
		global $post;
		add_image_size( 'realty_widget_size', 85, 45, false );
		$args = array('orderby'=>'title');
		$listings = new WP_Query(array(
            'post_type'=>'products',
            'orderby'=>'title',
            'order'=>'ASC'
    ));
		//$listings->query('post_type=products');
		if($listings->found_posts > 0) {
			$postno = 0;
			$row_open = true;
			
			echo '<div id="korvlador"><div class="korv_row">';
				while ($listings->have_posts()) {
					$postno++;
					$listItem ="";
					if (!$row_open) {
						$listItem .='<div class="korv_row">';
						$row_open = true;
					}
					$listings->the_post();
					$image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'realty_widget_size') : '';
					$listItem .= '<div class="korv_item panel-grid-cell"><div class="korv_child">' . $image;
					$listItem .= '<h4>'. get_the_title() . '</h4>';
					$listItem .= get_the_content();
					$listItem .= '</div></div>';
					if (($postno % 3) == 0) {
						$listItem .='</div>';
						$row_open = false;
					}
					echo $listItem;
				}
				if ($row_open) {
						$listItem .='</div>';
				}
			echo '</div>';
			wp_reset_postdata();
		}else{
			echo '<p style="padding:25px;">Inga korvlådor</p>';
		}
}

	
} //end class Products_Widget
register_widget('Products_Widget');


function my_enqueue($hook) {
    // if ( 'edit.php' != $hook ) {
    //     return;
    // }
  wp_register_style(
          'jquery-ui-datepicker',
          'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/pepper-grinder/jquery-ui.min.css'
      );
  wp_enqueue_style( 'jquery-ui-datepicker' );
  wp_enqueue_script( 'datepicker',  '/wp-content/themes/wintherperson/assets/scripts/admin-scripts/admin.js', wp_enqueue_script('jquery-ui-datepicker') );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue' );
