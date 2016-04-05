<?php
/**
 * Template Name: Konto
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'account'); ?>
<?php endwhile; ?>
