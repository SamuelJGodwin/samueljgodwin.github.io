<?php
/**
 * Template for Temporary Login Notification Email.
 *
 * @var WP_User $wtlwp_user          The user object for the temporary login.
 * @var string  $expiry_time The expiry time for the temporary login.
 */

if ( ! isset( $wtlwp_user, $expiry_time ) ) {
	return;
}
?>
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
	<h2 style="font-size: 1.5rem; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px;">
		<?php echo esc_html__( 'Temporary User Login Activity', 'temporary-login-without-password' ); ?>
	</h2>
	<p style="font-size: 1rem; color: #4b5563; margin-bottom: 15px;">
		<?php echo esc_html__( 'Hello Admin,', 'temporary-login-without-password' ); ?>
	</p>
	<p style="font-size: 1rem; color: #4b5563; margin-bottom: 15px;">
		<?php echo esc_html__( 'A user has login using temporary login created with the following details:', 'temporary-login-without-password' ); ?>
	</p>
	<ul style="list-style-type: disc; padding-left: 20px; color: #4b5563; font-size: 0.95rem; margin-bottom: 20px;">
		<li><strong><?php echo esc_html__( 'Username:', 'temporary-login-without-password' ); ?></strong> <?php echo isset($wtlwp_user->user_login) ? esc_html( $wtlwp_user->user_login ):''; ?></li>
		<li><strong><?php echo esc_html__( 'Email:', 'temporary-login-without-password' ); ?></strong> <?php echo isset($wtlwp_user->user_email) ? esc_html( $wtlwp_user->user_email ) : ''; ?></li>
		<li><strong><?php echo esc_html__( 'Expires In:', 'temporary-login-without-password' ); ?></strong> <?php echo isset($expiry_time) ? esc_html( $expiry_time ) : ''; ?></li>
	</ul>
</div>
