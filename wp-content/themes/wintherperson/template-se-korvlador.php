<?php
/**
 * Template Name: se korvlador
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content', 'se-korvlador'); ?>
<?php endwhile; ?>
