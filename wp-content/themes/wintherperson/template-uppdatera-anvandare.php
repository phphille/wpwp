<?php
/**
 * Template Name: Uppdatera användare
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'create-update-user'); ?>
<?php endwhile; ?>
