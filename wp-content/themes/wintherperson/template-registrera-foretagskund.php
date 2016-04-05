<?php
/**
 * Template Name: Registrera företagskund
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'reg-company'); ?>
<?php endwhile; ?>
