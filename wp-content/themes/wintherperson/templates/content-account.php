
<?php
  the_content();
  $userInfo = get_userdata( get_current_user_id() );
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">

        <form class="form_account" action="" method="post">

          <?php  echo (get_current_user_role() == 'manager' ? '<input type="hidden" name="prinskorv" value="lightbulb">' : '');?>


          <div class="input-group">
            <label for="">E-postadress</label>
            <input type="text" name="user_email" class="form-control" value="<?php echo $userInfo->user_email ?>" required>
            <span>E-postadressen är inte giltig</span>
          </div>

          <div class="input-group">
            <label for="">Förnamn</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo $userInfo->first_name ?>" required>
            <span>Förnamnet innehåller icke tillåtna tecken</span>
          </div>
          <div class="input-group">
            <label for="">Efternamn</label>
            <input type="text" name="last_name" class="form-control" value="<?php echo $userInfo->last_name ?>" required>
            <span>Efternamnet innehåller icke tillåtna tecken</span>
          </div>
          <div class="input-group">
            <label for="">Gatuadress</label>
            <input type="text" name="address" class="form-control" value="<?php echo get_user_meta($userInfo->ID, 'address', true); ?>" required>
            <span>Gatuadressen innehåller icke tillåtna tecken</span>
          </div>
          <div class="input-group">
            <label for="">Postnummer</label>
            <input type="text" name="postalcode" class="form-control" value="<?php echo get_user_meta($userInfo->ID, 'zip', true); ?>" required>
            <span>Postnummeret innehåller icke tillåtna tecken</span>
          </div>
          <div class="input-group">
            <label for="">Stad</label>
            <input type="text" name="city" class="form-control" value="<?php echo get_user_meta($userInfo->ID, 'city', true); ?>" required>
            <span>Stadsnamnet innehåller icke tillåtna tecken</span>
          </div>
          <div class="input-group">
            <label for="">Telefonnummer</label>
            <input type="text" name="phone" class="form-control" value="<?php echo get_user_meta($userInfo->ID, 'phone', true); ?>" required>
            <span>Telefonnummeret innehåller icke tillåtna tecken</span>
          </div>

          <div class="input-group">
            <label for="">Lösenord</label>
            <input type="password" name="password" class="form-control" value="">
          </div>
          <div class="input-group">
            <label for="">Repetera lösenord</label>
            <input type="password" name="password2" class="form-control" value="">
            <span>Lösenorden överensstämmer inte</span>
          </div>


          <div class="input-group">
            <label for="">Nuvarande lösenord</label>
            <input type="password" name="passwordCurrent" class="form-control" value="" required>
            <span>Ange ditt lösenord</span>
          </div>


          <input type="submit" value="Uppdatera" class="doUpdateLoggedInUser">
        </form>
    </div>
  </div>
</div>
