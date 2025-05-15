<?php
/**
 * Grid template
 *
 * @package AffiliateCoupons\Templates
 *
 * @var Affcoups_Coupon $coupon
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Check if coupons were found
if ( ! isset( $coupons ) ) {
    return;
}

if ( ! isset( $args ) ) {
    $args = array();
}

// Default values
if ( ! isset ( $grid_size ) ) {
    $grid_size = '3';
}

do_action( 'affcoups_template_begin', $coupons, $args ); ?>

<div class="affcoups-coupons-grid affcoups-coupons-grid--col-<?php echo esc_attr( $grid_size ); ?>">

    <?php if ( sizeof( $coupons ) > 0 ) {

        foreach( $coupons as $coupon ): ?>            
            <div class="affcoups-coupons-grid__item">
                <div class="<?php $coupon->the_classes('affcoups-coupon' ); ?>"<?php $coupon->the_container(); ?>>

                    <div class="affcoups-coupon__header">
                        <div class="affcoups-vendor__logo_grid"> <?php affcoups_tpl_the_vendor_logo( $coupon);  ?> </div>
                        <?php affcoups_tpl_the_coupon_image( $coupon ); ?> 
                        <?php affcoups_tpl_the_coupon_discount( $coupon ); ?>

                    </div>

                    <div class="affcoups-coupon__content">

                        <?php affcoups_tpl_the_coupon_title( $coupon ); ?>
                        <?php affcoups_tpl_the_coupon_types( $coupon ); ?>
                        <?php affcoups_tpl_the_coupon_description_with_excerpt( $coupon ); ?>
                        <?php affcoups_tpl_the_coupon_code( $coupon ); ?>
                        <?php affcoups_tpl_the_coupon_valid_dates( $coupon ); ?>
                        <?php affcoups_tpl_the_coupon_expire_counter( $coupon ); ?>
                        <?php affcoups_tpl_the_coupon_discounted_value( $coupon ); ?>

                    </div>
                    <div >
                        <?php affcoups_tpl_the_coupon_social_share_button($coupon); ?>
                    </div>
                    <div class="affcoups-coupon__footer">
                        <?php affcoups_tpl_the_coupon_button( $coupon ); ?>
                        <?php do_action( 'affcoups_add_after_button' ); ?>

                    </div>

                </div>

            </div>

        <?php endforeach;
    } else {
        esc_html_e( 'No coupons found.', 'affiliate-coupons-pro' );
    } ?>

</div>

<?php do_action( 'affcoups_after_template', $coupons, $args ); ?>