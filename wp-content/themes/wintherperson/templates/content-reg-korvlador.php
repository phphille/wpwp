<?php the_content();

function getUsersCompanies($loggedInUserId, $products){
  $productIds = [];
  $productNames = [];
  foreach ($products as $product) {
    $productIds[] = $product->ID;
    $productNames[] = $product->post_title;
  }
  $args = array(
    'author' => $loggedInUserId,
    'post_type' => 'companies',
    'posts_per_page' => -1,
    'post_status' => array('publish', 'pending', 'draft', 'auto-draft')
  );


  wp_reset_postdata();
  $author_posts = new WP_Query( $args );
  $companies = '';
  if( $author_posts->have_posts() ) {
    foreach ($author_posts->posts as $post) {
      $companyMeta = get_post_meta($post->ID);
      $companies .= '
      <div class="panel panel-default">
      <div class="panel-heading">
        <span class="panel-title">'.$post->post_title.'</span>
        <span class="glyphicon glyphicon-chevron-down"></span>
      </div>';
      $companies .= '
      <div class="panel-body" style="display: none;">
        <h5>Beställda korvlådor</h5>';
          foreach ($companyMeta as $key => $value) {
            // var_dump($key);
            if(strpos($key, 'korvlada-') !== false){
              $korvladaId = explode('-', $key)[1];
              if(in_array($korvladaId, $productIds)){
                // dump($value);
                $productName = $productNames[array_search($korvladaId, $productIds)];
                $companies .= '<p><span>'.$productName.'</span> <span>'.$value[0].' st.</span></p>';
              }
            }
          }

        $companies .= '
        <h5>Fakturauppgifter</h5>';
        // dump($companyMeta);
        $companies .= '<p>'.$companyMeta['companyAddress'][0].'</p>';
        $companies .= '<p><span>'.$companyMeta['companyPostalcode'][0].'</span> </span>'.$companyMeta['companyCity'][0].'</span></p>';
        $companies .= '<p>Organisationsnummer</p>';
        $companies .= '<p>'.$companyMeta['companyOrgNbr'][0].'</p>';
        $companies .= '<p>Epost</p>';
        $companies .= '<p>'.$companyMeta['companyMail'][0].'</p>';
        $companies .= '<p>Telefonnummer</p>';
        $companies .= '<p>'.$companyMeta['companyPhone'][0].'</p>';
        $companies .= '
        <h5>Mottagares kontaktuppgifter</h5>';
        $companies .= '<p>'.$companyMeta['companyRecipientName'][0].'</p>';
        $companies .= '<p>'.$companyMeta['companyRecipientPhone'][0].'</p>';
        $companies .= '<p>'.$companyMeta['companyRecipientMail'][0].'</p>';
      $companies .= '
        <a href="#'.$post->ID.'" class="btn btn-default btn-block edit-company">Redigera företagsbeställning</a>
        <a href="#'.$post->ID.'" class="btn btn-default btn-block delete-company">Radera företagsbeställning</a>
      </div>
    </div>';
    // $post->ID
    }
  }

  return $companies;
}





function getUsersPrivateCustomers($loggedInUserId, $products){
  $korvladorPrivateCustomer = '<ul class="private-korvlade-list">';
  foreach ($products as $product) {
    $nbrSoldProduct = get_user_meta($loggedInUserId, 'korvlada-'.$product->ID, true);
    if ($nbrSoldProduct != '' && $nbrSoldProduct != '0') {
      $korvladorPrivateCustomer .= '<li id="private-'.$product->ID.'"><span>'.$product->post_title.'</span> <span>'.$nbrSoldProduct.' st.</span></li>';
    }
  }
  $korvladorPrivateCustomer .= '</ul>';
  return $korvladorPrivateCustomer;
}









$loggedInUserId = get_current_user_id();
$products = [];

 ?>



<div class="row">

  <div class="col-sm-6">
    <h3>Privatkunder</h3>
    <hr>
    <?php $args = array(
    'post_type' => 'products',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    ?>

    <?php if($query->posts): ?>

      <form class="private-customer-form" action="" method="post">
        <?php wp_nonce_field( 'update_sold_korvs','nya-korvar' ); ?>
        <select name="korvlada">
          <option value="" disabled selected>Välj Produkt</option>
          <?php
            foreach ($query->posts as $product) {
              $products[] = $product;
              echo '<option value="'.$product->ID.'">'.$product->post_title.'</option>';
            }
          ?>
        </select>
        <input name="nbrProducts" type="number" value="" disabled>
        <input class="btn btn-default updateSoldKorv" name="add" type="submit" value="Lägg till" disabled>
        <input class="btn btn-default updateSoldKorv" name="remove" type="submit" value="Ta bort" disabled>
      </form>

    <?php else: ?>

      <p>
        finns inte några produkter
      </p>

    <?php endif; ?>

    <?php if($query->posts): ?>
      <h3>Företagskund</h3>
      <hr>
      <form class="company-customer-form" action="" method="post">
        <?php //wp_nonce_field( 'add_new_company','ny-stor-korv' );  ?>

        <input type="hidden" name="company_korven" value="">
        <div class="input-group">
          <label for="">Företagsnamn</label>
          <input type="text" name="company_name" class="form-control" value="">
        </div>


        <br><br>
        <div class="input-group">
          <label>Lägg till korvlådor</label>
          <br>
          <!-- <hr> -->
          <select name="company-korvlada">
            <option value="" disabled selected>Välj Produkt</option>
            <?php
              foreach ($query->posts as $product) {
                $products[] = $product;
                echo '<option value="'.$product->ID.'">'.$product->post_title.'</option>';
              }
            ?>
          </select>
          <input type="number" name="company-korvlada-amount" value=""> st.
          <button type="button" name="add" class="btn btn-default" disabled>Lägg till</button>
          <ul class="new-company-korvlade-list">
          </ul>
        </div>
        <br>
        <br>

        <label>Fakturauppgifter</label>
        <br>
        <div class="input-group">
          <label for="">Gatuadress</label>
          <input type="text" name="company_address" class="form-control" value="">
        </div>
        <div class="input-group">
          <label for="">Postnummer</label>
          <input type="text" name="company_postalcode" class="form-control" value="">
        </div>
        <div class="input-group">
          <label for="">Stad</label>
          <input type="text" name="company_city" class="form-control" value="">
        </div>
        <div class="input-group">
          <label for="">Organistationsnummer</label>
          <input type="text" name="company_orgNbr" class="form-control" value="">
        </div>
        <div class="input-group">
          <label for="">E-post</label>
          <input type="text" name="company_mail" class="form-control" value="">
        </div>
        <div class="input-group">
          <label for="">Telefon</label>
          <input type="text" name="company_phone" class="form-control" value="">
        </div>

        <label>Mottagarens kontaktuppgifter</label>
        <br>
        <div class="input-group">
          <label for="">Namn</label>
          <input type="text" name="company_recipientName" class="form-control" value="">
        </div>
        <div class="input-group">
          <label for="">E-post</label>
          <input type="text" name="company_recipientMail" class="form-control" value="">
        </div>
        <div class="input-group">
          <label for="">Telefon</label>
          <input type="text" name="company_recipientPhone" class="form-control" value="">
        </div>


        <input class="btn btn-default" type="submit" name="do-company" value="Spara företagsbeställning">
        <p class="bg-warning hide">Det gick inte att spara företagsbeställningen</p>

      </form>
    <?php endif; ?>
  </div>



  <div class="col-sm-6">
    <h3>Dina beställningar</h3>
    <hr>
    <div class="col-sm-12">
      <h4>Privatkunder</h4>
      <hr>
      <?php echo getUsersPrivateCustomers($loggedInUserId, $products); ?>
      <h4>Företagskunder</h4>
      <hr>
      <div id="salesperson-company-container">
        <?php echo getUsersCompanies($loggedInUserId, $products); ?>
      </div>
    </div>
  </div>
</div>

<?php wp_reset_postdata(); ?>
