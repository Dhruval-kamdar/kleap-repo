<?php if ( $data['referrals'] ):?>
    <?php global $indeed_db;?>
    <?php foreach ( $data['referrals'] as $referralObject ):?>
        <div><?php echo '<b>Affiliate user: </b><a href="'.admin_url('admin.php?page=ultimate_affiliates_pro&tab=user_profile&affiliate_id='.$referralObject->affiliate_id).' " target="_blank">' . $indeed_db->get_username_by_wpuid( $indeed_db->get_uid_by_affiliate_id( $referralObject->affiliate_id ) ).'</a>';?></div>
        <div><?php echo "<b>Amount: </b>" . uap_format_price_and_currency( $referralObject->currency, $referralObject->amount );?></div>
        <div><?php echo "<b>Client Username: </b>" .  $indeed_db->get_username_by_wpuid( $referralObject->refferal_wp_uid );?></div>
        <div><?php echo "<b>Description: </b>" . $referralObject->description;?></div>
        <div><?php echo "<b>Referral Status: </b>";
          switch ($referralObject->payment){
            case 0:
              _e('Unpaid', 'uap');
              break;
            case 1:
              _e('Pending', 'uap');
              break;
            case 2:
              _e('Complete', 'uap');
              break;
          }?>
        </div>
    <?php endforeach;?>
<?php else :?>
    <?php _e( 'No referrals for this order.', 'uap' );?>
<?php endif;?>