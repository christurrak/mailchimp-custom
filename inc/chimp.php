<?php

// Load Subscriber Lists
function get_lists(){
  module_load_include('php', 'mailchimp', 'MCAPI.class');
  $api = new MCAPI(variable_get('mailchimp_api_key', ''));

  $retval = $api->lists(variable_get('mailchimp_api_key', ''));
  return $retval;
}

// Load Custom Templates
function get_templates(){
  module_load_include('php', 'mailchimp', 'MCAPI.class');
  $api = new MCAPI(variable_get('mailchimp_api_key', ''));

  $types = array('user'=>'true');
  $retval = $api->CampaignTemplates((variable_get('mailchimp_api_key', '')));
  return $retval;
}

// Load Custom Campaigns
function get_campaigns(){
  module_load_include('php', 'mailchimp', 'MCAPI.class');
  $api = new MCAPI(variable_get('mailchimp_api_key', ''));

  $retval = $api->campaigns();
  // dsm($retval);
  return $retval;

}

// Campaign generieren und senden
function create_campaign($list_id,$template_id,$subject,$name,$mail,$content_mail_header,$content_mail){

  module_load_include('php', 'mailchimp', 'MCAPI.class');

  $api = new MCAPI(variable_get('mailchimp_api_key', ''));

  $type = 'regular';

  $opts['list_id'] = $list_id;
  $opts['template_id'] = $template_id;
  $opts['subject'] = $subject;
  $opts['from_email'] = 'cturrak@yahoo.de';
  $opts['from_name'] = $name;

  $opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);

  $opts['authenticate'] = true;

  $opts['title'] = $title;

//  $content = array('html'=> $content_mail,
//		  'text' => $content_mail
//		);

$content = array(
     'html_MAIN'=> $content_mail,
		 'html_SIDECOLUMN' => 'side column',
		 'html_HEADER' => $content_mail_header,
		 'html_FOOTER' => 'the footer',
		 'text' => $content_mail
		);


$retval = $api->campaignCreate($type, $opts, $content);
//
if ($api->errorCode){
  drupal_set_message("Erstellen der Campaign fehlgeschlagen!");
  drupal_set_message("\n\tCode=".$api->errorCode);
  drupal_set_message("\n\tMsg=".$api->errorMessage."\n");
  return false;

}
else {
 drupal_set_message("Campaign erstellt. ID:".$retval."\n");
  return $retval;

  }

}

function campaign_preview($campID){

  module_load_include('php', 'mailchimp', 'MCAPI.class');

  $api = new MCAPI(variable_get('mailchimp_api_key', ''));
  $retval = $api->campaignContent($campID,false);

  if ($api->errorCode){
    drupal_set_message($api->errorMessage);

}
else {

// dsm($retval);

  return $retval['html'];

}
}

function campaign_send($campaign_ID){
 $api = new MCAPI(variable_get('mailchimp_api_key', ''));

 $retval = $api->campaignSendNow($campaign_ID);

if ($api->errorCode){
   drupal_set_message("Senden des Newsletters fehlgeschlagen!");
   drupal_set_message("\n\tCode=".$api->errorCode);
   drupal_set_message("\n\tMsg=".$api->errorMessage."\n");
  return 0;
 }
else {
  drupal_set_message(t('Newsletter gesendet!'));
  return $retval;
}


}

function campaign_send_test($campaign_ID){
 $api = new MCAPI(variable_get('mailchimp_api_key', ''));

 $retval = $api->campaignSendTest($campaign_ID);

if ($api->errorCode){
  drupal_set_message ("Senden des Newsletters fehlgeschlagen!");
  drupal_set_message("\n\tCode=".$api->errorCode);
  drupal_set_message(print "\n\tMsg=".$api->errorMessage."\n");
 }
else {
  drupal_set_message(t('Newsletter gesendet!'));
}


}