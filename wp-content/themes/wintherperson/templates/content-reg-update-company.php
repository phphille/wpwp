<?php the_content();?>


<?php
$id = '';
$company_name = '';
$company_org_nbr = '';
$company_address = '';
$company_city  = '';
$company_postalcode = '';
$company_contactMail = '';
$company_contactFirstname = '';
$company_contactLastname = '';
$company_contactPhone = '';
$company_deliveryFirstname = '';
$company_deliveryLastname = '';
$company_deliveryPhone = '';
$company_want_delivery = '';
$buttonVal = 'Skapa';

if(isset($_GET['id']) && is_numeric($_GET['id'])){
  $post = get_post($_GET['id']);

  if($post->post_author == get_current_user_id()){
    $id = $post->ID;
    $company_name = $post->post_title;
    $company_org_nbr = get_post_meta($id,'companyOrgNbr',true);
    $company_address = get_post_meta($id,'companyAddress',true);
    $company_city  = get_post_meta($id,'companyCity',true);
    $company_postalcode = get_post_meta($id,'companyPostalcode',true);
    $company_contactMail = get_post_meta($id,'companyContactMail',true);
    $company_contactFirstname = get_post_meta($id,'companyContactFirstname',true);
    $company_contactLastname = get_post_meta($id,'companyContactLastname',true);
    $company_contactPhone = get_post_meta($id,'companyContactPhone',true);
    $company_deliveryFirstname = get_post_meta($id,'companyDeliveryFirstname',true);
    $company_deliveryLastname = get_post_meta($id,'companyDeliveryLastname',true);
    $company_deliveryPhone = get_post_meta($id,'companyDeliveryPhone',true);
    $company_want_delivery = get_post_meta($id,'companyWantDelivery',true) == 'ja' ? 'checked' : '';
    $buttonVal = 'Uppdatera';
  }
}

?>

<form class="" action="" method="post">
  <?php wp_nonce_field( 'add_new_company','ny-stor-korv' );  ?>

  <input type="hidden" name="korven" value="<?php echo $id; ?>">
  <h4>Företag</h4>
  <div class="input-group">
    <label for="">Företagsnamn</label>
    <input type="text" name="company_name" class="form-control" value="<?php echo $company_name; ?>">
  </div>
  <div class="input-group">
    <label for="">Organistationsnummer</label>
    <input type="text" name="company_org-nbr" class="form-control" value="<?php echo $company_org_nbr; ?>">
  </div>
  <div class="input-group">
    <label for="">Adress</label>
    <input type="text" name="company_address" class="form-control" value="<?php echo $company_address; ?>">
  </div>
  <div class="input-group">
    <label for="">Stad</label>
    <input type="text" name="company_city" class="form-control" value="<?php echo $company_city; ?>">
  </div>
  <div class="input-group">
    <label for="">Postnummer</label>
    <input type="text" name="company_postalcode" class="form-control" value="<?php echo $company_postalcode; ?>">
  </div>
  <div class="input-group">
    <label for="">E-post</label>
    <input type="text" name="company_contactMail" class="form-control" value="<?php echo $company_contactMail; ?>">
  </div>

  <p>Kontaktuppgifter</p>
  <div class="input-group">
    <label for="">Förnamn</label>
    <input type="text" name="company_contactFirstname" class="form-control" value="<?php echo $company_contactFirstname; ?>">
  </div>
  <div class="input-group">
    <label for="">Efternamn</label>
    <input type="text" name="company_contactLastname" class="form-control" value="<?php echo $company_contactLastname; ?>">
  </div>
  <div class="input-group">
    <label for="">Telefon</label>
    <input type="text" name="company_contactPhone" class="form-control" value="<?php echo $company_contactPhone; ?>">
  </div>


  <p>Kontaktuppgifter för leverans</p>
  <div class="input-group">
    <label for="">Förnamn</label>
    <input type="text" name="company_deliveryFirstname" class="form-control" value="<?php echo $company_deliveryFirstname; ?>">
  </div>
  <div class="input-group">
    <label for="">Efternamn</label>
    <input type="text" name="company_deliveryLastname" class="form-control" value="<?php echo $company_deliveryLastname; ?>">
  </div>
  <div class="input-group">
    <label for="">Telefon</label>
    <input type="text" name="company_deliveryPhone" class="form-control" value="<?php echo $company_deliveryPhone; ?>">
  </div>

  <p>Utkörning</p>
  <div class="checkbox">
    <label>
      <input type="checkbox" name="company_want-delivery" <?php echo $company_want_delivery ?> value="1"> Vill ha utkörning
    </label>
  </div>


  <input class="btn btn-default" type="submit" name="do-company" value="<?php echo $buttonVal; ?>">
</form>
