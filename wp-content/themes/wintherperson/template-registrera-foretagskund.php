<?php
/**
 * Template Name: Registrera/uppdatera företagskund
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'reg-update-company'); ?>
<?php endwhile; ?>
