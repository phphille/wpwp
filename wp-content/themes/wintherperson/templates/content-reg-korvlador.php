<?php the_content(); ?>


<?php

$korvlador = '';
$company = '';
$sum = '';
$sumAntalPrivKorvlador = 0;
$sumPrisPrivKorvlador = 0;
$sumForetag = 0;

$args = array(
'post_type' => 'products',
'post_status' => 'publish',
'posts_per_page' => -1,
);

$query = new WP_Query($args);


// dump($query->posts);
if($query->posts){
  $korvlador ='
    <form class="" action="" method="post">';
  $korvlador .= wp_nonce_field( 'update_sold_korvs','nya-korvar' );

    foreach ($query->posts as $product) {
      $artikelnamn = get_post_meta($product->ID,'artikelnamn',true);
      $produktPris = get_post_meta($product->ID,'pris',true);
      $nbrSoldProduct = get_user_meta(get_current_user_id(), $artikelnamn, true);
      $korvlador .='
      <div class="input-group">
        <label for="">'.$product->post_title.'</label>
        <input type="number" name="'.$artikelnamn.'" class="form-control" value="'.$nbrSoldProduct.'">
      </div>';
      $sumAntalPrivKorvlador += $nbrSoldProduct;
      $sumPrisPrivKorvlador += (floatval($produktPris) * floatval($nbrSoldProduct));
    }
  $korvlador .='
      <input class="btn btn-default updateSoldKorv" name="update_sold_korv" type="submit" value="Uppdatera">
    </form>';


$args = array(
  'author' => get_current_user_id(),
  'post_type' => 'companies',
  'posts_per_page' => -1,
  'post_status' => array('publish', 'pending', 'draft', 'auto-draft')
);

$company = '<a href="foretagskund">Registrera nytt företag</a>';

$author_posts = new WP_Query( $args );

if( $author_posts->have_posts() ) {
  foreach ($author_posts->posts as $post) {
    $company .= '<div class="row"><div class="col-sm-12">';
    $company .= '<a href="foretagskund/?id='.$post->ID.'">Företag: '.$post->post_title.'</a>';
    $company .= '</div></div>';
    $sumForetag++;
  }
}


  $sum = '<p>Lådor privat: '.$sumAntalPrivKorvlador.'</p>';
  $sum .= '<p>Summa lådor privat: '.$sumPrisPrivKorvlador.' kr</p>';
  $sum .= '<p>Antal företagskunder: '.$sumForetag.'</p>';

}

// echo $html;
 ?>



<div class="row">

<div class="col-sm-3">
<?php if($query->posts): ?>

  <?php echo $korvlador; ?>

<?php else: ?>

  <p>
    finns inte några produkter
  </p>

<?php endif; ?>

</div>

<div class="col-sm-5">
  <?php echo $company; ?>
</div>



<div class="col-sm-4">
  <?php echo $sum; ?>
</div>
</div>
