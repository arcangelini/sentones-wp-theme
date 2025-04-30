<?php
/**
 * Title: Hero Section
 * Slug: sent-ones-wp/hero
 * Categories: sent-ones-wp
 * Keywords: hero, header, welcome
 * Block Types: core/cover
 * Description: A hero section with a background image, heading, text, and a button.
 */
?>

<!-- wp:cover {"url":"https://picsum.photos/seed/picsum/1600/900","dimRatio":50,"overlayColor":"secondary","align":"full","style":{"spacing":{"padding":{"top":"6rem","bottom":"6rem"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:6rem;padding-bottom:6rem"><span aria-hidden="true" class="wp-block-cover__background has-secondary-background-color has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="https://picsum.photos/seed/picsum/1600/900" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
    <!-- wp:group {"layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group">
        <!-- wp:heading {"textAlign":"center","level":1,"textColor":"background","fontSize":"x-large"} -->
        <h1 class="wp-block-heading has-text-align-center has-background-color has-text-color has-x-large-font-size">Welcome to SentOnes</h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","textColor":"background"} -->
        <p class="has-text-align-center has-background-color has-text-color">A custom WordPress block theme designed for modern websites.</p>
        <!-- /wp:paragraph -->

        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
        <div class="wp-block-buttons">
            <!-- wp:button {"className":"is-style-fill"} -->
            <div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button">Learn More</a></div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
    </div>
    <!-- /wp:group -->
</div></div>
<!-- /wp:cover --> 