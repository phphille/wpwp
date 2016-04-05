<?php
/**
 * Template Name: Registrera sålda korvlådor
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'reg-korvlador'); ?>
<?php endwhile; ?>
