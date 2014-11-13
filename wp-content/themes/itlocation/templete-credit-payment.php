<?php

/*

  Template Name: Credit Payment

 */

if (is_user_logged_in()):

    wp_redirect(get_site_url());

endif;

?>

<?php @get_header(); ?>
<style>
*{margin:0; padding:0;}
	.s2member-pro-paypal-submit
	{
	background: none repeat scroll 0 0 #9ecb26;
    border: medium none;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    margin-bottom:30px;
    padding: 8px 62px;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
    cursor: pointer;
    border-radius: 4px;
    width: 100%;
     padding-left: 5px;
    padding-right: 5px;
    /*width: 33.3333%;*/
	}
	.s2member-pro-paypal-country
	{
		width: 56%!important;
	}
        .s2member-pro-paypal-username
	{
		width: 63%!important;
	}
        .s2member-pro-paypal-card-verification
	{
		width: 63%!important;
	}
        .s2member-pro-paypal-card-number
	{
		width: 83%!important;
	}
        .s2member-pro-paypal-checkout-form-description-div
	{
  	   font-family: "Roboto",sans-serif!important;
   	   font-size: 22px!important;
    	   line-height: 1.42857;
            color: #007fb1;
	}
.container-align{position:relative; margin:0 auto; width:1150px;}

</style>
<div class="container-align">
<?php echo do_shortcode('[s2Member-Pro-PayPal-Form level="1" ccaps="" desc="Please fill you details here" ps="paypal" lc="" cc="USD" dg="0" ns="1" custom="dev.itlocator.com" ta="0" tp="0" tt="D" ra="0.01" rp="1" rt="M" rr="1" rrt="" rra="2" accept="visa,mastercard,amex,discover,maestro,solo" accept_via_paypal="paypal" coupon="" accept_coupons="0" default_country_code="" captcha="0" modify="1" /]'); ?>
</div>
<?php get_footer(); ?>