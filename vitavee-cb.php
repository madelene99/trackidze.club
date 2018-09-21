<?php

// Script written by Vita Vee for affiliates who are
// NOT using the best funnel builder and tracker out there (ie Funnel Flux)
// and want a way to have Clickbank report conversions to their tracker.
// This script only reports initial sales and upsells.
// If you were using Funnel Flux instead, then rebills and refunds
// would also be tracked automatically as Funnel Flux is fully integrated with Clickbank.
//
// Instructions: You will edit the 4 parameters defined
// in the "SETTINGS TO EDIT" section below.
//
// 1/ Replace the value of $postbackURL by the postback URL given to you by
//    your tracker BUT in that URL keep only the part that is before the ?
//
// 2/ Replace the value of $cidParameterName by the name of the click-id
//    parameter (example: "cid" for Voluum)
//
// 3/ Replace the value of $payoutParameterName by the name of the parameter
//    that allows you to track your commissions for a conversion (example: "payout" for Voluum)
//
// 4/ Replace the value of $transactionParameterName by the name of the parameter
//    that allows you to track upsells (example: "txid" for Voluum)
//
// Only $postbackURL and $cidParameterName are mandatory.
// If you do not want or can't fill in the other two, then make them empty like this:
// $payoutParameterName = ""
// $transactionParameterName = ""

//-----------------------------------------------------
// SETTINGS TO EDIT
//-----------------------------------------------------
$postbackURL = "http://track-c.onversion.club/postback?cid=REPLACE&payout=OPTIONAL&txid=OPTIONAL";
$cidParameterName = "cid";
$payoutParameterName = "payout";
$transactionParameterName = "txid";
//-----------------------------------------------------

//-----------------------------------------------------
// DO NOT TOUCH ANYTHING BELOW
//-----------------------------------------------------
$tid    = filter_input(INPUT_GET, "trackingCodes");
$payout = filter_input(INPUT_GET, "affiliateCommission");
$txid   = filter_input(INPUT_GET, "receipt");

$aParams = array();

if( $tid !== null && !empty($cidParameterName) )
    $aParams[$cidParameterName] = $tid;

if( $payout !== null && !empty($payoutParameterName) )
    $aParams[$payoutParameterName] = $payout;

if( $txid !== null && !empty($transactionParameterName) )
    $aParams[$transactionParameterName] = $txid;

$aParts = explode("?", trim($postbackURL));
$finalPostbackURL = $aParts[0]."?".http_build_query($aParams);

if( $finalPostbackURL )
{
    $ch = curl_init($finalPostbackURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
}


?>