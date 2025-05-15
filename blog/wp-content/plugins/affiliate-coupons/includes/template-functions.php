<?php
/**
 * Get template file
 *
 * @param $template
 * @param string $type
 *
 * @return string
 */

function affcoups_get_template_file( $template, $type = '' ) {

	$template_file = AFFCOUPS_PLUGIN_DIR . 'templates/' . $template . '.php';

	$template_file = apply_filters( 'affcoups_template_file', $template_file, $template, $type );

	if ( file_exists( $template_file ) ) {
		return $template_file;
	}

	return ( 'widget' === $type ) ? AFFCOUPS_PLUGIN_DIR . 'templates/widget.php' : AFFCOUPS_PLUGIN_DIR . 'templates/standard.php';
}

/**
 * Template loader
 *
 * @param $template
 */
function affcoups_get_template( $template, $wrap = false ) {

	// Get template file
	$file = affcoups_get_template_file( $template );

	if ( file_exists( $file ) ) {

		if ( $wrap ) {
			echo esc_attr( '<div class="affcoups">' );
		}

		include $file;

		if ( $wrap ) {
			echo esc_attr( '</div>' );
		}

	} else {
		echo esc_attr( '<p>' . __( 'Template not found.', 'affiliate-coupons' ) . '</p>' );
	}
}

/**
 * Output template wrapper start html
 *
 * @depreacted
 *
 * Html wrapper was moved into the templates directly
 */
function affcoups_tpl_the_wrapper_start() {

	global $affcoups_template_args;

	?>
    <div class="affcoups">
	<?php
}

/**
 * Output template wrapper end html
 *
 * @depreacted
 *
 * Html wrapper was moved into the templates directly
 */
function affcoups_tpl_the_wrapper_end() {

	global $affcoups_template_args;

	?>
    </div><!-- /.affcoups -->
	<?php
}

/**
 * Output the coupon image markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_image( $coupon ) {

    $coupon->the_image();
}

/**
 * Output the coupon discount markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_discount( $coupon ) {

    if ( $coupon->get_discount() ) { ?>
        <span class="affcoups-coupon__discount"><?php echo esc_attr( $coupon->get_discount() ); ?></span>
    <?php
    }
}

/**
 * Output the coupon title markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_title( $coupon ) {

    global $affcoups_template_args;
    $options = affcoups_get_options();
    if(($options['title_display']=='hide') || ($affcoups_template_args['title_display']=='hide'))       
    return;

    $coupon_title = $coupon->get_title();
    $coupon_title = affcoups_cleanup_html_attribute( $coupon_title );

    $coupon_url = esc_url( $coupon->get_url() );

    $options = affcoups_get_options();
    $title_linked = ( isset( $options['title_linked'] ) && '1' === $options['title_linked'] ) ? true : false;

    if ( $title_linked && ! empty( $coupon_url ) ) {
        ?>
        <a class="affcoups-coupon__title" href="<?php echo $coupon_url; ?>" title="<?php echo $coupon_title; ?>" target="_blank" rel="nofollow"><?php echo $coupon_title; ?></a>
        <?php
    } else {
        ?>
        <span class="affcoups-coupon__title"><?php echo $coupon_title; ?></span>
        <?php
    }
}

/**
 * Output the coupon types markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_types( $coupon ) {

    if ( $coupon->get_types() ) { ?>
        <div class="affcoups-coupon__types">
            <?php $coupon->the_types(); ?>
        </div>
    <?php }
}

/**
 * Output the coupon description markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_description( $coupon ) {

    ?>
    <div class="affcoups-coupon__description">
        <?php echo $coupon->get_description(); ?>
    </div>
    <?php
}

/**
 * Output the coupon description with excerpt markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_description_with_excerpt( $coupon ) {

     global $affcoups_template_args;
    $options = affcoups_get_options();
    if(($options['description']=='hide') || ($affcoups_template_args['description']=='hide'))
            return;           
    ?>
    <div class="affcoups-coupon__description">
        <div class="affcoups-coupon__description-excerpt">
            <?php $coupon->the_excerpt(); ?>
        </div>
        <div class="affcoups-coupon__description-full">
            <?php echo $coupon->get_description(); ?>
            <a href="#" class="affcoups-toggle-desc" data-blub="true" data-affcoups-toggle-desc="true"><?php _e('Show Less', 'affiliate-coupons' ); ?></a>
        </div>
    </div>
    <?php
}

/**
 * Output the coupon code markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_code( $coupon,$show_code=false ) {
   // $coupon_meta=get_post_meta($Coupon->post->ID);
   // if ($coupon_meta['affcoups_click_to_reveal_disable'][0] )
   // return $button_html;
   if ( !affcoups_is_pro_version() | $show_code){?>
   <div class="affcoups-coupon__code">
   <?php $coupon->the_code(); ?>
    </div> 
    <?php
    return;

   }
   
   $display_code_instu="false";
   $user_logged_in="false";
   $ask_login = "false";
   $display_code_currentwindow_popup="false";
   $options = affcoups_get_options();

   if(affcoups_get_option('reveal_code_option')==2){
    $display_code_instu="true";
   }
   if(affcoups_get_option('reveal_code_option')==0){
    $display_code_currentwindow_popup="true";
    
   }
   if(affcoups_get_option("login_to_see_code")==1){
    $ask_login="true";
   }
   if(is_user_logged_in()){
    $user_logged_in="true";
   }

   
   $login_msg = ( ! empty( $options['login_msg'] ) ) ? esc_attr( trim( $options['login_msg'] ) ) : __( 'Login to see the code!!! ', 'affiliate-coupons-pro' );
   
   if ( $coupon->show_code() || $coupon->is_click_to_reveal_disable()) { ?>
        <div class="affcoups-coupon__code">
            <?php $coupon->the_code(); ?>
        </div>
    <?php }
    else if(!is_user_logged_in() && ($ask_login=="true")){ 
        ?>

        <div class="affcoups-coupon__code" id="affcoups-coupon__code_show_<?php echo $coupon->get_id(); ?>" data-ask_login=<?php echo $ask_login ?> data-user_logged_in="false" data-login_msg="<?php echo $login_msg;?>" data-display_code_instu="<?php echo $display_code_instu ?>" data-display_code_currentwindow_popup="<?php echo $display_code_currentwindow_popup ?>" style="display:none">
        <a href="<?php echo $options['login_url'] ?>" target="_blank" rel= "nofollow" class="affcoups-clipboard affcoups-clipboard__text" style="color:red" >  <?php echo $login_msg; ?>  </a>
        </div>
    <?php }
        else{ ?>
            <div class="affcoups-coupon__code" id="affcoups-coupon__code_show_<?php echo $coupon->get_id(); ?>" data-ask_login="<?php echo $ask_login ?>" data-user_logged_in="<?php echo $user_logged_in;?>" data-display_code_instu="<?php echo $display_code_instu ?>" data-display_code_currentwindow_popup="<?php echo $display_code_currentwindow_popup ?>" style="display:none">
             <?php $coupon->the_code(); ?>
            </div>
            
            <?php 
            $CouponExtended = affcoups_pro_setup_coupon_extended( $coupon ); ?>

            <div id="affcoups-coupon__button_<?php echo $coupon->get_id(); ?>" style="display:none"> <?php $CouponExtended->the_button_instu(); ?> </div>
        <?php }
}

/**
 * Output the coupon valid dates markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_valid_dates( $coupon ) {


    if ( $coupon->show_valid_dates() ) { ?>
        <div class="affcoups-coupon__valid-dates">
            <?php $coupon->the_valid_dates(); ?> 
        </div>
    <?php } 
}

/**
 * Output the coupon expire counter markup
 *
 * @param Affcoups_Coupon $coupon
 */
function affcoups_tpl_the_coupon_expire_counter( $coupon ) {
    $valid_until = get_post_meta( $coupon->id, AFFCOUPS_PREFIX . 'coupon_valid_until', true );
    $valid_from = get_post_meta( $coupon->id, AFFCOUPS_PREFIX . 'coupon_valid_from', true );


    if ($valid_until && (time()>$valid_from) && affcoups_is_pro_version())
    if ( $coupon->show_expiry_countdown() ) { ?>
    <?php if(time()<$valid_until){ ?>
        <div class="affcoups-coupon__expiry-counter">
            <p class="expires-in" title="<?php echo $valid_until; ?>" > <?php echo esc_html__("Expires in", 'affiliate-coupons' ) ?> <span class='expiressed-in'> hgjhgjh</span></p>
       </div>
       <?php }else{ ?>
       <p class="affcoups-coupon__expired_msg" ><?php echo esc_html__("Expired!", 'affiliate-coupons' ) ?></p>
     
    <?php } }
}

function affcoups_tpl_the_coupon_discounted_value( $coupon ) {

if(affcoups_is_pro_version() ){ 
    $original_price = get_post_meta( $coupon->id, AFFCOUPS_PREFIX . 'coupon_original_price', true );
    $discounted_price = get_post_meta( $coupon->id, AFFCOUPS_PREFIX . 'coupon_discounted_price', true );
    ?>

    <div class="affcoups-coupon__cost-compare" style="text-align: center; "> <a class="affcoups-coupon__discounted_price"><?php echo $discounted_price." "?></a><a class="affcoups-coupon__original_price"><?php echo $original_price ?></a></div>
  <?php }
}
/**
 * Output the coupon button markup
 *
 * @param Affcoups_Coupon $coupon
 * @param array $args
 */
function affcoups_tpl_the_coupon_button( $coupon, $args = array() ) {

    $coupon->the_button( $args );
}


/**
 * Output the Social share button markup
 *
 * @param Affcoups_Coupon $coupon
 * @param array $args
 */
function affcoups_tpl_the_coupon_social_share_button( $coupon, $args = array() ) {

    if ( !affcoups_is_pro_version() )
       return;
    $options = affcoups_get_options();
    $disable_social_share = get_post_meta($coupon->id, AFFCOUPS_PREFIX.'social_share_disable' , true);
    if($disable_social_share){
        return;
    }
    if(isset($options['social_share'])){
        $share_url=get_permalink();
        if(empty($share_url))
            $share_url = $_SERVER['HTTP_REFERER']; 
        $shared_url = urlencode($share_url."?affcoups-coupon=".$coupon->id);
        $msg = str_replace(' ', '%20', $options['social_share_msg']);
    ?>
        <div class=" affcoups-coupon__social-share" >
        <a id="affcoups_share_on_whatsapp" style="text-decoration:none;align-content: right;" href= <?php echo "https://api.whatsapp.com/send?text=$msg$shared_url" ?> title="Share by whatsapp"><span class="dashicons dashicons-whatsapp"></span></a>
        <a id="affcoups_share_on_facebook" style="text-decoration:none; " href= <?php echo "https://www.facebook.com/sharer/sharer.php?u=$shared_url"; ?> title="Share by facebook"><span class="dashicons dashicons-facebook"></span></a>
        <a id="affcoups_share_on_twitter" style="text-decoration:none; " href= <?php echo "https://twitter.com/share?url=$shared_url&text=$msg" ;?> title="Share by twitter"><span class="dashicons dashicons-twitter"></span></a>
        <a id="affcoups_share_on_email" style="text-decoration:none; " href= <?php echo "mailto:?subject=$msg&body=$msg:$shared_url" ; ?> title="Share by email"><span class="dashicons dashicons-email"></span></a>
        </div>

    
    <?php
    }
    }

    /**
 * Output the coupon button markup
 *
 * @param Affcoups_Coupon $coupon
 * @param array $args
 */
function affcoups_tpl_the_vendor_logo( $coupon, $args = array() ) {     
    if(!array_key_exists('vendor_logo', $coupon->options))
    return;
    if(!($coupon->options['vendor_logo']=='show'))
        return;
    $vendor_id=$coupon->vendor_id;
    $Vendor = new Affcoups_Vendor( $vendor_id );
    $image = $Vendor->get_image() ; 
    if($image){ ?>
     <img style="height:100%; object-fit:contain;" src="<?php echo $image['url']; ?>" >

<?php     
return;
}   
}
