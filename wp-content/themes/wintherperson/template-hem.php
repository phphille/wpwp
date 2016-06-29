<?php
/**
 * Template Name: Hem användare
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'home-user'); ?>
<?php endwhile; ?>
