<?php 
/*========================================================================*
  Shortcode wrapper for horizontal scrolling element
 *========================================================================*/


function CornerstoneCarouselElement_Shortcode( $atts, $content = null ) {

	extract( shortcode_atts( array(
    'id'               => '',
    'class'            => '',
    'style'            => '',
    'maxitems'         => '',
    'pauseonhover'     => '',
    'navigation'       => '',
    'auto_valign'      => '',
    'pagination_type'  => ''
  ), $atts, 'cornerstone-carousel' ) );

  // Setup variables

  $id           = ( $id       != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
  $class        = ( $class    != '' ) ? 'class="cornerstone-carousel-wrap ' . esc_attr( $class ) . '"' : 'class="cornerstone-carousel-wrap"';
  $style        = ( $style    != '' ) ? 'style="' . $style . '"' : '';
  $maxitems     = ( $maxitems != '' ) ? $maxitems : 3;
  $pauseonhover = ( $pauseonhover == 'true' ) ? 'true' : 'false';
  $navigation   = ( $navigation   == 'true' ) ? 'true' : 'false';
  // $auto_valign  = ( $auto_valign  == 1 ) ? 'true' : 'false';


  if ( $pagination_type == 'dots' ) {
    $dots = 'true';
    $nums = 'false';
  } elseif ( $pagination_type == 'numbers' ) {
    $dots = 'true';
    $nums = 'true';
  } else {
    $dots = 'false';
    $nums = 'false';
  }

  $randnum = rand(0,5000); // doing this for now to namespace separate elements on the same page. TODO: use a session var, transient or something else.


  // the element

  $output = "<div {$id} {$class} {$style}>"
              . "<div class='owl-" . $randnum . "'>" . do_shortcode( $content ) . "</div>"
          . "</div>";

  // vertical align ?

  if ( $auto_valign ) {
    $output .= "<style>" .
                ".owl-" . $randnum . " .owl-item { " .
                  "display:flex; 
                  align-items:center; 
                  justify-content:center;" .
                "}" .
                "</style>";
  }

  // JS to make it happen

  $output .= "<script type=\"text/javascript\">/* <![CDATA[ */" .
               "jQuery(document).ready(function($){" .
                 "$('.owl-" . $randnum . "').owlCarousel({
                    autoPlay: true,
                    items: {$maxitems},
                    navigation: {$navigation},
                    pagination: {$dots},
                    paginationNumbers: {$nums},
                    stopOnHover: {$pauseonhover}
                  });\n";

  // TODO: Fix how this is parsing
  if ( $auto_valign ) {
    $output .=    "var currentOwl = '.owl-" . $randnum . "';" .
      "var owlHeight = $(currentOwl + ' .owl-wrapper').height();" .
       "$(currentOwl + ' .owl-item').css('height', owlHeight + 'px' );";
  }

  $output .= "});" .
             "/* ]]> */</script>";



  return $output;
}
add_shortcode( 'cornerstone-carousel', 'CornerstoneCarouselElement_Shortcode' );




/* The individual items */



function CornerstoneCarouselElement_Item_Shortcode( $atts, $content = null ) {
	
	extract( shortcode_atts( array(
    'id'    => '',
    'class' => '',
    'style' => '',
    'auto_valign' => 1
  ), $atts, 'cornerstone-carousel-item' ) );

  if ( $auto_valign === 'true' ) {
    $flex = "display:flex; align-items:center; justify-content:center; ";
  } else {
    $flex = '';
  }

  $id    = ( $id    != '' ) ? 'id="' . esc_attr( $id ) . '"' : '';
  $class = ( $class != '' ) ? 'class="' . esc_attr( $class ) . '"' : '';
  $style = ( $style != '' ) ? 'style="' . $flex . $style . '"' : 'style="' . $flex . '"';


  $output = "<div {$id} {$class} {$style}>" . do_shortcode( $content ) . "</div>";

  return $output;
}
add_shortcode( 'cornerstone-carousel-item', 'CornerstoneCarouselElement_Item_Shortcode' );
