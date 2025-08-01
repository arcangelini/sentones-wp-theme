<?php
/**
 * Title: header
 * Slug: sent-ones-wp/header
 * Inserter: no
 */
?>
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:group {"metadata":{"name":"Header wrapper"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide is-layout-flex wp-block-group-is-layout-flex" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)">

<div class="wp-sent-ones-logo"><a rel="home" title="Sent Ones" href="<?php echo home_url(); ?>"><?php echo file_get_contents( esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ) ); ?></a></div>

<!-- wp:navigation {"ref":4,"style":{"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","justifyContent":"right"}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->