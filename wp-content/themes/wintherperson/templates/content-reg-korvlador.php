<?php the_content(); ?>


<?php


$args = array(
'post_type' => 'products',
'post_status' => 'publish',
'posts_per_page' => -1,
);

$query = new WP_Query($args);

$html = 'Finns inte några produkter';
// dump($query->posts);
if($query->posts){
  $html ='
    <form class="" action="" method="post">';

    foreach ($query->posts as $product) {
      $artikelnamn = get_post_meta($product->ID,'artikelnamn',true);
      $html .='
      <div class="input-group">
        <label for="">'.$product->post_title.'</label>
        <input type="number" name="'.$artikelnamn.'" class="form-control" value="'.get_user_meta(get_current_user_id(), $artikelnamn, true).'">
      </div>';
    }



  $html .='
      <input class="btn btn-default doUpdateSales" type="submit" value="Uppdatera">
    </form>';


  $html .='
  <form class="" action="" method="post">
    <h4>Företag</h4>
    <div class="input-group">
      <label for="">Företag</label>
      <input type="text" name="company-name" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Organistationsnummer</label>
      <input type="text" name="custom[org-nbr]" class="form-control" value="">
    </div>

    <input class="btn btn-default doAddCompany" type="submit" value="Uppdatera">
  </form>';
}

echo $html;
 ?>
