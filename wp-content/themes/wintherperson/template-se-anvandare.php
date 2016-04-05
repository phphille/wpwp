<?php
/**
 * Template Name: Lista användare
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'list-user'); ?>
<?php endwhile; ?>
