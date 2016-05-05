<?php the_content(); ?>


<?php

$korvlador = '';
$company = '';
$sum = '';


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

    foreach ($query->posts as $product) {
      $artikelnamn = get_post_meta($product->ID,'artikelnamn',true);
      $korvlador .='
      <div class="input-group">
        <label for="">'.$product->post_title.'</label>
        <input type="number" name="'.$artikelnamn.'" class="form-control" value="'.get_user_meta(get_current_user_id(), $artikelnamn, true).'">
      </div>';
    }



  $korvlador .='
      <input class="btn btn-default doUpdateSales" type="submit" value="Uppdatera">
    </form>';


  $company = '
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      ...
    </div>
  </div>
</div>

  <form class="" action="" method="post">
    <h4>Företag</h4>
    <div class="input-group">
      <label for="">Företagsnamn</label>
      <input type="text" name="name" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Organistationsnummer</label>
      <input type="text" name="org-nbr" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Adress</label>
      <input type="text" name="address" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Stad</label>
      <input type="text" name="city" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Postnummer</label>
      <input type="text" name="postalcode" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">E-post</label>
      <input type="text" name="contactMail" class="form-control" value="">
    </div>

    <p>Kontaktuppgifter</p>
    <div class="input-group">
      <label for="">Förnamn</label>
      <input type="text" name="contactFirstname" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Efternamn</label>
      <input type="text" name="contactLastname" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Telefon</label>
      <input type="text" name="contactPhone" class="form-control" value="">
    </div>


    <p>Kontaktuppgifter för leverans</p>
    <div class="input-group">
      <label for="">Förnamn</label>
      <input type="text" name="deliveryFirstname" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Efternamn</label>
      <input type="text" name="deliveryLastname" class="form-control" value="">
    </div>
    <div class="input-group">
      <label for="">Telefon</label>
      <input type="text" name="deliveryPhone" class="form-control" value="">
    </div>

    <p>Utkörning</p>
    <div class="checkbox">
      <label>
        <input type="checkbox"> Vill ha utkörning
      </label>
    </div>


    <input class="btn btn-default doAddCompany" type="submit" value="Uppdatera">
  </form>';
}

// echo $html;
 ?>



<div class="row">

<div class="col-md-3">
<?php if($query->posts): ?>

  <?php echo $korvlador; ?>

<?php else: ?>

  <p>
    finns inte några produkter
  </p>

<?php endif; ?>

</div>

<div class="col-md-5">
  <?php echo $company; ?>
</div>



<div class="col-md-4">
  <?php echo $sum; ?>
</div>
</div>
