<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<tr id="<?php echo $coupon->id ?>" class="<?php echo $row_class ?>">

    <td class="code editable-cell">
        <?php if ( $coupon->code ) : ?>
            <div class="displayed-value"><?php echo esc_html( $coupon->code ) ?></div>
            <input class="form-control value ab-value" type="text" name="code" value="<?php echo esc_attr( $coupon->code ) ?>" style="display: none" />
        <?php else : ?>
            <div class="displayed-value" style="display: none"></div>
            <input class="form-control value ab-value" type="text" name="code" />
        <?php endif; ?>
    </td>

    <td align='right' class="editable-cell discount">
        <div class="displayed-value ab-rtext"><?php echo $coupon->discount ?></div>
        <input class="form-control value ab-text-focus" type="number" min="0" max="100" step="any" name="discount" value="<?php echo esc_attr( $coupon->discount ) ?>" style="display: none" />
    </td>

    <td align='right' class="editable-cell deduction">
        <div class="displayed-value ab-rtext"><?php echo $coupon->deduction ?></div>
        <input class="form-control value ab-text-focus" type="number" min="0" name="deduction" value="<?php echo esc_attr( $coupon->deduction ) ?>" style="display: none" />
    </td>

    <td class="allow editable-cell">
        <div class="displayed-value"><?php echo esc_html( $coupon->usage_limit ) ?></div>
        <input class="form-control value ab-value" type="number" min="1" name="usage_limit" value="<?php echo esc_attr( $coupon->usage_limit ) ?>" style="display: none" />
    </td>

    <td>
        <div class="displayed-value"><?php echo $coupon->used ?></div>
    </td>

    <td class="last">
        <input type="checkbox" class="row-checker" />
    </td>
</tr>