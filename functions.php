<?php

/*=========================================
	Enqueue scripts and styles
======================================== */
add_action( 'wp_enqueue_scripts', 're_add_my_stylesheet', 100 ); // high number priority queues them last
function re_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    // wp_register_style( 'prefix-style', get_stylesheet_directory_uri() . '/assets/css/style1.css' );
    // wp_enqueue_style( 'prefix-style' );
    wp_enqueue_style( 'flexslider', get_stylesheet_directory_uri() . '/assets/css/flexslider.css' );
    wp_enqueue_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
    //wp_enqueue_script('smooth-scrolling', get_stylesheet_directory_uri() . '/assets/js/smooth-scrolling.js');
    wp_enqueue_script('flex-slider', get_stylesheet_directory_uri() . '/assets/js/jquery.flexslider-min.js');

}

/*==================================================
	Javascript
================================================= */
add_action('woo_head', 're_header_scripts');

function re_header_scripts () { ?>
<script type="text/javascript" charset="utf-8">
  jQuery(window).load(function() {
  	jQuery('.toggle-box').toggle('slow', function() {
	    // Animation complete.
	  });
    jQuery('.flexslider').flexslider({
    	pauseOnHover: true,
    	slideshowSpeed: 4000
    });
    jQuery('a.toggle').click(function() {
	  jQuery('.toggle-box').toggle('slow', function() {
	    // Animation complete.
	  });
	});
  });
</script>
<?php 
}

/*=========================================
	Widget area above menu
======================================== */
add_action( 'woo_header_inside', 'woo_custom_head_right', 100);

function woo_custom_head_right () { ?>
    <div id="above-menu-widget" class="above-menu-widget fr">
        <?php 
        	// the_field("top_text"); 
        	if (is_page()){
        		echo "We look at your business in a way other accountants don't";
        	} else {
        		echo "We look at your business in a way other accountants don't";
        	}
        ?>
    </div>
<?php 
} 


/*=========================================
	Full width body
======================================== */

// Add body container
add_action( 'woo_content_before', 'body_container_start' );
function body_container_start() { ?>
    <!--#body-container-->
    <div id="body-container">
<?php
}

// Finish body container
add_action( 'woo_content_after', 'body_container_end', 8 );
function body_container_end() { ?>
    </div><!--/#body-container-->
<?php
}


/*=========================================
	Custom widget areas
======================================== */
register_sidebar( array( 'name' => __( 'Above Menu', 'woothemes' ), 'id' => 'abovemenu', 'description' => __( 'Displays above the menu', 'woothemes' ), 'before_widget' => '<div id="%1$s" class="%2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ) );    


/*=========================================
	Visual Editor customisations
======================================== */

// Use editor-style.css style for the editor
add_editor_style();

// Add the Style Dropdown Menu to the second row of visual editor buttons
function my_mce_buttons_2($buttons)
{
	array_unshift($buttons, 'styleselect');
	return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');

// Add the style options
function my_mce_before_init($init_array)
	{
		// Now we add classes with title and separate them with;
		$init_array['theme_advanced_styles'] = "Large text=large-text;Button=woo-sc-button;Box=woo-sc-box normal";
	return $init_array;
}

add_filter('tiny_mce_before_init', 'my_mce_before_init');



/*=========================================
	Change name of Black Studio Tiny MCE
======================================== */

function change_tinymce_widget_title($translation, $text, $domain) {
    if ($text == 'Black Studio TinyMCE')
        $translation = 'Text - Visual Editor';
    return $translation;
}
add_filter('gettext', 'change_tinymce_widget_title', 10, 3);


/*=========================================
	Re-create logo function
======================================== */

if ( ! function_exists( 'woo_logo' ) ) {
function woo_logo () {
	$settings = woo_get_dynamic_values( array( 'logo' => '' ) );
	// Setup the tag to be used for the header area (`h1` on the front page and `span` on all others).
	$heading_tag = 'span';
	if ( is_home() || is_front_page() ) { $heading_tag = 'h1'; }

	// Get our website's name, description and URL. We use them several times below so lets get them once.
	$site_title = get_bloginfo( 'name' );
	$site_url = home_url( '/' );
	$site_description = get_bloginfo( 'description' );
?>
<div id="logo">
<?php
	// Website heading/logo and description text.
	if (get_field('logo')) { ?>
		<img src="<?php the_field('logo'); ?>" alt="<?php echo get_the_title(get_field('logo')) ?>" />
	<?php 
	} elseif ( ( '' != $settings['logo'] ) ) {
		$logo_url = $settings['logo'];
		if ( is_ssl() ) $logo_url = str_replace( 'http://', 'https://', $logo_url );

		echo '<a href="' . esc_url( $site_url ) . '" title="' . esc_attr( $site_description ) . '"><img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $site_title ) . '" /></a>' . "\n";
	} // End IF Statement

	echo '<' . $heading_tag . ' class="site-title"><a href="' . esc_url( $site_url ) . '">' . $site_title . '</a></' . $heading_tag . '>' . "\n";
	if ( $site_description ) { echo '<span class="site-description">' . $site_description . '</span>' . "\n"; }
?>
</div>
<?php
} // End woo_logo()
}

add_action( 'woo_header_inside', 'woo_logo', 10 );

/*=========================================
	Content wrap
======================================== */
add_action( 'woo_post_inside_before', 're_content_wrap', 11);

function re_content_wrap() {
	echo '<div class="col-full content">';
}

add_action( 'woo_post_inside_after', 're_content_wrap_end', 11);

function re_content_wrap_end() {
	echo '</div>';
}


/*=========================================
	ACF Slider
======================================== */

add_action( 'woo_post_inside_before', 're_acf_slider', 10);

function re_acf_slider () { ?>
<?php if(get_field('slides')): ?> 
<div class="flexslider">
	<ul class="slides">
	<?php
		while(has_sub_field('slides')):
	?>
		<li class="slide" style="background-color:<?php the_sub_field('bg_colour'); ?>">
			<div class="col-full">
				<div class="threecol-two image">
					<img class="" src="<?php the_sub_field('image'); ?>">
					<?php if(get_sub_field('overlay_text')): ?>
						<a href="#overlay" style="position:absolute; padding: 15px 0px; width:auto; bottom:10em; right:-10px; background-color:#fff; text-align:center;" class="toggle" title="Show additional info">
							<span class="icon-stack">
								<i class="icon-chevron-left icon-dark"></i>
							</span>
						</a>
						<div class="toggle-box" style="position:absolute; width:100%; top:0; right:0; height:100%; background:<?php the_sub_field('bg_colour'); ?>; opacity:0.9;">
							
						</div>
						<div class="toggle-table toggle-box">
							<div style="padding: 0 8% 0 8%; display: table-cell; vertical-align:middle;"><?php the_sub_field('overlay_text'); ?></div>
						</div>
						<a href="#overlay" style="position:absolute; z-index:99; padding: 15px 0px; width:auto; bottom:10em; right:-10px; background-color:#fff; text-align:center;" class="toggle toggle-box" title="Close additional info">
							<span class="icon-stack">
								<i class="icon-chevron-right icon-dark"></i>
							</span>
						</a>
					<?php endif; ?>
				</div>
				<div class="threecol-one last text">
		 			<h2><?php the_sub_field('title'); ?></h2>
		 			<p class="content"><?php the_sub_field('text'); ?></p>
		 		<?php if(get_sub_field('button_text')): ?>
					<p><a href="<?php the_sub_field('link'); ?>" class="btn btn-<?php the_sub_field('button_colour'); ?>"><?php the_sub_field('button_text'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-chevron-right"></i></a></p>
				<?php endif; ?>
					
				</div>
			</div>
 		</li>
	<?php
		endwhile; 
	?>
	</ul> 

</div>
<?php endif; ?> 
<?php }


/*=========================================
	ACF Page Headings
======================================== */

add_action( 'woo_post_inside_after', 're_acf_page_heading', 9);

function re_acf_page_heading() {
	if (is_page()) {
	$values = get_field('hide_title');
		$hide_title = $values['0'];
		if ($hide_title != 'Yes') {
			/* Taken from Canvas */
			$heading_tag = 'h1';
			if ( is_front_page() ) { $heading_tag = 'h2'; }
			$title_before = '<' . $heading_tag . ' class="title">';
			$title_after = '</' . $heading_tag . '>';
			/* End taken from canvas */
			?>
			<header>
				<?php the_title( $title_before, $title_after ); ?>
				<p class="subtitle"><?php the_field("page_subtitle"); ?></p>
			</header>
<?php
		}
	} 
}


/*=========================================
	ACF Flexible Content
======================================== */

add_action( 'woo_post_inside_after', 're_acf_content', 10);

function re_acf_content () { ?>
<?php while(has_sub_field("content")): ?>
<section class="entry">
	<?php if(get_row_layout() == "paragraph"): // layout: Content ?>
 
		<div>
			<?php the_sub_field("content"); ?>
		</div>
 
	<?php elseif(get_row_layout() == "file"): // layout: File ?>
 
		<div>
			<a href="<?php the_sub_field("file"); ?>" ><?php the_sub_field("name"); ?></a>
		</div>

	<?php elseif(get_row_layout() == "call_to_action"): // layout: Call to action ?>

		<div class="cta"><span class="cta-text text-black"><?php the_sub_field('text'); ?></span><a href="<?php the_sub_field('link'); ?>" class="btn btn-<?php the_sub_field('button_colour'); ?>"><?php the_sub_field('button_text'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon-chevron-right"></i></a></div>

	<?php elseif(get_row_layout() == "featured_posts"): // layout: Featured Posts ?>
 
		<div>
			<h2><?php the_sub_field("title"); ?></h2>
			<?php the_sub_field("content"); ?>
 
			<?php if(get_sub_field("posts")): ?>
				<ul>
				<?php foreach(get_sub_field("posts") as $p): ?>
					<li><a href="<?php echo get_permalink($p->ID); ?>"><?php echo get_the_title($p->ID); ?></a></li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>
 
		</div>
 
	<?php endif; ?>
</section>
<?php endwhile; ?> 
<?php }



/*=========================================
	Below content
======================================== */

add_action( 'woo_main_after', 're_below_content');
function re_below_content() { ?>
	<?php 
	global $post;
	if ( is_page('services') || $post->post_parent == '1595'):?>
    <div class="col-full content below-content">
    	<div class="">
    		<div class="grid single-col">
				<div class="grid-item threecol-one view view-second">
					<a href="<?php echo site_url(); ?>/services/business-essentials/">Business Essentials</a>				
				</div>
				<div class="grid-item threecol-one view view-second">
					<a href="<?php echo site_url(); ?>/services/wealth-creation/">Wealth Creation</a>
				</div>
				<div class="grid-item threecol-one last view view-second">
					<a href="<?php echo site_url(); ?>/services/finance-business-restructuring/">Business Restructuring</a>
				</div>
				<div class="grid-item threecol-one view view-second">
					<a href="<?php echo site_url(); ?>/services/business-consulting/">Business Consulting</a>				
				</div>
				<div class="grid-item threecol-one view view-second">
					<a href="<?php echo site_url(); ?>/services/asset-protection/">Asset Protection</a>
				</div>
				<div class="grid-item threecol-one last view view-second">
					<a href="<?php echo site_url(); ?>/services/self-managed-super-funds/">Self Managed Super</a>
				</div>
				<div class="grid-item threecol-one view view-second">
					<a href="<?php echo site_url(); ?>/services/virtual-cfo/">Virtual CFO</a>				
				</div>
				<div class="grid-item threecol-one view view-second">
					<a href="<?php echo site_url(); ?>/services/property-professionals/">Property Professionals</a>
				</div>
				<div class="grid-item threecol-one last view view-second">
					<a href="<?php echo site_url(); ?>/services/business-coaching/">Business Coaching</a>
				</div>							
			</div>
			<div class="clear"></div>
    	</div>
    </div>
    <?php endif; ?>
<?php
}

/* =====================================================
	Post meta
===================================================== */

if ( ! function_exists( 'woo_post_meta' ) ) {
function woo_post_meta() {
	if ( is_page() ) { return; }

	$post_info = '<span class="small">Published ' . _x( 'on', 'post datetime', 'woothemes' ) . '</span> [post_date]';
printf( '<div class="post-meta">%s</div>' . "\n", apply_filters( 'woo_filter_post_meta', $post_info ) );

} // End woo_post_meta()
}


/* =====================================================
	Posts navigation
===================================================== */

add_action( 'woo_post_inside_after_singular-post', 'woo_post_inside_after_default', 10 );

if ( ! function_exists( 'woo_post_inside_after_default' ) ) {
function woo_post_inside_after_default() {

	// $post_info ='[post_tags before=""]';
	// printf( '<div class="post-utility">%s</div>' . "\n", apply_filters( 'woo_post_inside_after_default', $post_info ) );

} // End woo_post_inside_after_default()
}

add_action( 'woo_post_after', 'woo_postnav', 10 );
function woo_postnav() {
	if ( is_single() ) {
	?>
	<div class="col-full content below-content">
		<div class="grid single-col">
	        <div class="post-entries">
	            <div class="nav-prev fl"><?php previous_post_link( '%link', '<i class="icon-angle-left"></i> %title' ) ?></div>
	            <div class="nav-next fr"><?php next_post_link( '%link', '%title <i class="icon-angle-right"></i>' ) ?></div>
	            <div class="fix"></div>
	        </div>
	    </div>
	</div>

	<?php
	}
}

/* =====================================================
	Breadcrumbs
===================================================== */
// add_action('woo_post_inside_before', 'woo_breadcrumbs', 10);


/* =====================================================
	Post sharing
===================================================== */

add_action( 'woo_post_inside_after', 'pg_post_share', 5);
function pg_post_share() {
	if (is_single()){ ?>
    <!--#body-container-->
    <div class="post-share">
    	<h3>Share this post</h3>
    	<a href="http://www.facebook.com/sharer.php?u=<?php echo get_permalink(); ?>" target="_blank">
			<span class="icon-stack"><i class="icon-circle icon-2x icon-stack-base"></i><i class="icon-facebook icon-2x icon-light"></i></span>
		</a>
		&nbsp;&nbsp;
		<a href="http://twitter.com/share?url=<?php echo get_permalink(); ?>" target="_blank">
			<span class="icon-stack"><i class="icon-circle icon-2x icon-stack-base"></i><i class="icon-twitter icon-2x icon-light"></i></span>
		</a>
		&nbsp;&nbsp;
		<a href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>" target="_blank">
			<span class="icon-stack"><i class="icon-circle icon-2x icon-stack-base"></i><i class="icon-google-plus icon-2x icon-light"></i></span>
		</a>

    </div>
    <?php }
}

?>