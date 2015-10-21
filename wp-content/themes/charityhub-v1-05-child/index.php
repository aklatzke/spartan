<!-- hs index -->
<?php get_header(); ?>
<div class="gdlr-content">

	<?php 
		global $gdlr_sidebar, $theme_option;
		$gdlr_sidebar = array(
			'type'=>'no-sidebar',
			'left-sidebar'=>'', 
			'right-sidebar'=>''
		); 
		$gdlr_sidebar = gdlr_get_sidebar_class($gdlr_sidebar);
	?>
	<div class="with-sidebar-wrapper">
		<div class="with-sidebar-container container">
			<div class="with-sidebar-left <?php echo $gdlr_sidebar['outer']; ?> columns">
				<div class="with-sidebar-content <?php echo $gdlr_sidebar['center']; ?> gdlr-item-start-content columns">
					<?php		
						// set the excerpt length
						if( !empty($theme_option['archive-num-excerpt']) ){
							global $gdlr_excerpt_length; $gdlr_excerpt_length = 55;
							add_filter('excerpt_length', 'gdlr_set_excerpt_length');
						} 

						global $wp_query, $gdlr_post_settings;
						$gdlr_lightbox_id++;
						$gdlr_post_settings['excerpt'] = 55;
						$gdlr_post_settings['thumbnail-size'] = 'full';			
						$gdlr_post_settings['blog-style'] = 'blog-full';							
					
						echo '<div class="blog-item-holder">';
						if($gdlr_post_settings['blog-style'] == 'blog-full'){
							$gdlr_post_settings['blog-info'] = array('author', 'date', 'category', 'comment');
							echo gdlr_get_blog_full($wp_query);
						}else{
							$gdlr_post_settings['blog-info'] = array('date', 'comment');
							$gdlr_post_settings['blog-info-widget'] = true;
							
							$blog_size = str_replace('blog-1-', '', $theme_option['archive-blog-style']);
							echo gdlr_get_blog_grid($wp_query, $blog_size, 
								$theme_option['archive-thumbnail-size'], 'fitRows');
						}
						echo '<div class="clear"></div>';
						echo '</div>';
						remove_filter('excerpt_length', 'gdlr_set_excerpt_length');
						
						$paged = (get_query_var('paged'))? get_query_var('paged') : 1;
						echo gdlr_get_pagination($wp_query->max_num_pages, $paged);													
					?>
				</div>
				<?php get_sidebar('left'); ?>
				<div class="clear"></div>
			</div>
			<?php get_sidebar('right'); ?>
			<div class="clear"></div>
		</div>				
	</div>				

</div><!-- gdlr-content -->
<?php get_footer(); ?>
<!-- //hs index -->

<script type="text/javascript">
    adroll_adv_id = "4CQCJ6Z6UZCHLPUNOEFLHT";
    adroll_pix_id = "IZLBRUOOFNDUDCJO4M4Y5I";
    (function () {
        var _onload = function(){
            if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}
            if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/j/roundtrip.js";
            ((document.getElementsByTagName('head') || [null])[0] ||
                document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        };
        if (window.addEventListener) {window.addEventListener('load', _onload, false);}
        else {window.attachEvent('onload', _onload)}
    }());
</script>


<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 936996114;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/936996114/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
