<?php

// return an array with the Section Background ACF field group data
function section_background() {
	
	$bg = array();
	
	$bg_style = get_sub_field( 'background_style' );
	
	if( $bg_style == 'image' ):
		$bg_image = get_sub_field( 'background_image' );
		$bg['style'] = 'background-image:url('.esc_url($bg_image['url']).');';
		$bg['class'] = 'bg-img';
		return $bg;
	elseif( $bg_style == 'color' ):
		$bg_color = get_sub_field( 'background_color' );
		$bg['style'] = 'background-color:'.$bg_color.';';
		$bg['class'] = 'bgcolor';
		return $bg;
	elseif( $bg_style == 'gradient' ):
		$bg_gradient_color = get_sub_field( 'background_gradient_color' );
		$bg['style'] = '';
		$bg['class'] = 'bgcolor gradient '.$bg_gradient_color;
		return $bg;
	else:
		$bg['image'] = '';
		$bg['style'] = '';
		$bg['class'] = '';
		return $bg;
	endif;
}

// buttons
// get the button acf fields
function get_button_fields() {
	global $post;
	$post_id = $post->ID;
	
	$btn = array();
	
	$button_styles = get_sub_field( 'button_styles' );
	$button_color = $button_styles['button_color'];
	$background_type = $button_styles['background_type'];
	$button_size = $button_styles['button_size'];
	
	$button_text = get_sub_field( 'button_text' );
	
	$button_link = get_sub_field( 'button_link' );
	$button_link_type = $button_link['button_link_type'];
	$button_target = $button_link['button_link_target'];
	$button_link = $button_link["button_link_$button_link_type"];
	if($button_link_type == "internal"):
		$button_link = get_permalink( $button_link->ID );
	endif;
	
	$button_class = "btn-$button_color btn-$background_type btn-$button_size";
	
	$btn['link'] = esc_url($button_link);
	$btn['class'] = esc_attr($button_class);
	$btn['target'] = esc_attr($button_target);
	$btn['text'] = esc_html($button_text);
	
	return $btn;
}

// echo single CTA button
function dynamic_button() {
	$btn = get_button_fields();
	echo "<a href='{$btn['link']}' class='btn {$btn['class']}' target='{$btn['target']}'>{$btn['text']}</a>";
}

// echo multiple CTA buttons
function dynamic_buttons( $field_name, $post_id ) {
	if( have_rows( $field_name ) ):
		while( have_rows( $field_name ) ): the_row();
			$btn = get_button_fields( $post_id );
			echo "<a href='{$btn['link']}' class='btn {$btn['class']}' target='{$btn['target']}'>{$btn['text']}</a>";
		endwhile;
	endif;
}

// Simple Link
// return the simple link acf fields in an array
function get_simple_link_fields() {
	
	$link = array();
	
	$link_type = get_sub_field( 'link_type' );
	$link_target = get_sub_field( 'link_target' );
	$link_url = get_sub_field( 'link_'.$link_type.'' );
	if( $link_type == 'internal' ):
		$link_url = get_permalink( $link_url->ID );
	endif;
	
	$link['link'] = esc_url($link_url);
	$link['target'] = esc_attr($link_target);
	
	return $link;
}

// ACF oEmbed
function acf_video_embed( $video ) {
	// use preg_match to find iframe src
	preg_match('/src="(.+?)"/', $video, $matches);
	$src = $matches[1];
	// Add extra parameters to src and replcae HTML.
	$params = array(
	    'controls'  => 0,
	    'hd'        => 1,
	    'autohide'  => 1
	);
	$new_src = add_query_arg($params, $src);				
	$video = str_replace($src, $new_src, $video);
	// add extra attributes to iframe html
	$attributes = 'frameborder="0"';
	$video = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $video);
	// Display customized HTML.
	if($video) :
    echo '<div class="video-wrapper">'.$video.'</div>';
	endif;
}

// adds a custom ID. works with the custom ID acf field group.
function custom_section_id() {
	if( get_sub_field( 'add_custom_id' ) == true ):
		echo 'id="'.get_sub_field( 'custom_id' ).'"';
	endif;
}