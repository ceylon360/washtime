<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<table class="table table-striped">
    <thead>
    <tr>
        <th></th>
        <th><?php _e( 'Country', 'ab' ) ?></th>
        <th class="text-right"><?php _e( 'Code', 'ab' ) ?></th>
        <th class="text-right"><?php _e( 'Price', 'ab' ) ?></th>
    </tr>
    </thead>
    <tbody id="pricelist">
    <?php if ( $prices ) : ?>
        <?php foreach ( $prices as $price ) : ?>
            <tr><td><i class="flag flag-<?php echo esc_attr( $price->country_iso_code ) ?>"></i></td><td><?php echo $price->country_name ?></td><td class="text-right"><?php echo $price->phone_code ?></td><td class="text-right">$<?php echo rtrim( $price->price, '0' ) ?></td></tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr><td colspan="4" class="text-center"><img src="<?php echo includes_url( 'js/tinymce/skins/lightgray/img/loader.gif' ) ?>" alt="<?php echo esc_attr( __( 'Loading...', 'ab' ) ) ?>" /></td></tr>
    <?php endif ?>
    </tbody>
</table>
<p><?php _e( 'If you do not see your country in the list please contact us at <a href="mailto:support@ladela.com">support@ladela.com</a>.', 'ab' ) ?></p>