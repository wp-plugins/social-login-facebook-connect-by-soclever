<?php
/*
Plugin Name: Social Login Facebook connect - other Social networks By SoClever
Plugin URI: https://wordpress.org/plugins/social-login-facebook-connect-by-soclever/
Description: This module enables Social Login (Facebook and more), User Profile Data & Social Analytics on your site
Version: 1.2.3
Author: Soclever Team
Author URI: https://www.socleversocial.com/
 */

if(!defined('ABSPATH')) exit;

header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");

ini_set('display_errors','0');
error_reporting(0);

register_activation_hook(__FILE__, 'scsl_activation');
register_deactivation_hook(__FILE__, 'scsl_deactivation');
register_uninstall_hook(__FILE__, 'scsl_uninstall');
function scsl_activation(){
		update_option('scsl_track_url','https://www.socleversocial.com/dashboard/');
        update_option('scsl_valid_domain','0');
        update_option('scsl_site_id','0');
        update_option('scsl_api_key','0');
        update_option('scsl_api_secret','0');
        update_option('scsl_selected_buttons','');
        update_option('scsl_domain','');
        update_option('scsl_network','');
        update_option('scsl_button_style','ic');
        update_option('scsl_button_size','30');
        update_option('scsl_lending_page','2');
        update_option('scsl_add_column','0');
        update_option('scsl_email_notify','0');
        update_option('scsl_email_notify_user','0');
        update_option('scsl_use_avtar','0');
        update_option('scsl_show_comment','0');
        update_option('scsl_comment_auto_approve','0');
        update_option('scsl_show_in_loginform','1');
        update_option('scsl_login_form_redirect','current');
        update_option('scsl_login_form_redirect_url','');
        update_option('scsl_show_in_regpage','0');
        update_option('scsl_reg_page_redirect','current');
        update_option('scsl_reg_page_redirect_url','');
        update_option('scsl_show_if_members_only','1');
        update_option('scsl_module_loaded','0');
        update_option('customlogin_fb','');
        update_option('customlogin_gp','');
        update_option('customlogin_tw','');
        update_option('customlogin_li','');
        update_option('customlogin_yh','');
        update_option('customlogin_ms','');
        update_option('customlogin_pp','');
        update_option('customlogin_ig','');
        
        
}
function scsl_deactivation(){
    delete_option('scs_login_ins');
    
  }  
function scsl_uninstall()
{
        delete_option('scsl_track_url');
        delete_option('scsl_valid_domain');
        delete_option('scsl_site_id');
        delete_option('scsl_api_key');
        delete_option('scsl_api_secret');
        delete_option('scsl_selected_buttons');
        delete_option('scsl_domain');
        delete_option('scsl_network');
        delete_option('scsl_button_style');
        delete_option('scsl_button_size');
        delete_option('scsl_lending_page');        
        delete_option('scsl_add_column');
        delete_option('scsl_email_notify');
        delete_option('scsl_email_notify_user');
        delete_option('scsl_use_avtar');
        delete_option('scsl_show_comment');
        delete_option('scsl_comment_auto_approve');
        delete_option('scsl_show_in_loginform');
        delete_option('scsl_login_form_redirect');
        delete_option('scsl_login_form_redirect_url');
        delete_option('scsl_show_in_regpage');
        delete_option('scsl_reg_page_redirect');
        delete_option('scsl_reg_page_redirect_url');
        delete_option('scsl_show_if_members_only');
        delete_option('scsl_module_loaded');
        delete_option('scs_login_ins');
        delete_option('customlogin_fb');
        delete_option('customlogin_gp');
        delete_option('customlogin_tw');
        delete_option('customlogin_li');
        delete_option('customlogin_yh');
        delete_option('customlogin_ms');
        delete_option('customlogin_pp');
        delete_option('customlogin_ig');
        

}

function scsl_custom_fun($notify_cs)
{
    $agegen=explode("~",$notify_cs);
     setcookie("csag", $agegen[1], strtotime('+30 days'),"/");
     setcookie("csgen", $agegen[2], strtotime('+30 days'),"/");
     setcookie("csrs", $agegen[3], strtotime('+30 days'),"/");
     setcookie("csfbn", $agegen[4], strtotime('+30 days'),"/");
     setcookie("cstfn", $agegen[5], strtotime('+30 days'),"/");
     setcookie("cszip", $agegen[6], strtotime('+30 days'),"/");

}


function scsl_redirect(){
    $redirect_url = $_SERVER['HTTP_REFERER'];
    if(!empty($_REQUEST['redirect_to'])){
        wp_safe_redirect($_REQUEST['redirect_to']);
    } else {
        wp_redirect($redirect_url);
    }
    exit();
}
add_filter('wp_logout','scsl_redirect');

function soclever_login_setup($links, $file)
{
	static $soclever_social_login_plugin = null;

	if (is_null ($soclever_social_login_plugin))
	{
		$soclever_social_login_plugin = plugin_basename (__FILE__);
	}

	if ($file == $soclever_social_login_plugin)
	{
		$settings_link = '<a href="admin.php?page=soclever_login">' . __ ('Setup', 'soclever_login') . '</a>';
		array_unshift ($links, $settings_link);
	}
	return $links;
}
add_filter ('plugin_action_links', 'soclever_login_setup', 10, 2);


add_action('wp_footer', 'scsl_js_footer');

function scsl_js_footer()
{
    update_option('scs_login_ins','1');
    $footer_js="";
    
   if(!get_option('scs_share_ins'))
   {
   $footer_js='<script type="text/javascript">
var sid=\''.get_option('scsl_site_id').'\';(function()
                                                    { var u=((\'https:\'==document.location.protocol)?\'https://\':\'https://\')+\'s3.socleversocial.com/\'; var su=u;var s=document.createElement(\'script\'); s.type=\'text/javascript\'; s.defer=true; s.async=true; s.src=su+\'scs.js\'; var p=document.getElementsByTagName(\'script\')[0]; p.parentNode.insertBefore(s,p); }
                                                    )();  
                                                         
                                           </script>'; 
   $footer_js .=PHP_EOL;
   }
   echo $footer_js;                                        
}	   


function get_cscurl($url)
{
    
if(get_option('scsl_module_loaded')=='1')
{
 return file_get_contents($url);    
}
else
{        
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);  
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);    
curl_setopt($ch, CURLOPT_SSLVERSION,3);
$result_response = curl_exec($ch);
$actual_return=$result_response;
curl_close($ch);
return $actual_return;
}
}



function scsl_comment_approved ($approved)
{
	
	if (empty($approved))
	{
		if (get_option('scsl_comment_auto_approve')=='1')
		{
			$user_id = get_current_user_id ();
			if (is_numeric ($user_id))
			{
				
					$approved = 1;
				
			}
		}
	}
	return $approved;
}
add_action ('pre_comment_approved', 'scsl_comment_approved');


function scsl_filter_comment_form_defaults ($default_fields)
{
    
	
	if (get_option('scsl_show_comment')=='1' && get_option('scsl_show_if_members_only')=='1' && is_array ($default_fields) && comments_open () && !is_user_logged_in ())
	{
		
			if (!isset ($default_fields ['must_log_in']))
			{
				$default_fields ['must_log_in'] = '';
			}
            //update_option('scsl_is_comment','1');
			$default_fields['must_log_in'] .=scsl_get_preview('0','1');
            
		
	}
    

	return $default_fields;
}
add_filter ('comment_form_defaults', 'scsl_filter_comment_form_defaults');



function scsl_login_form_comments()
{
		 echo scsl_render_login_form ('comments');
	
}
 

function scsl_comment_form_login_buttons( $post_id ) {
    
	if (get_option('scsl_show_comment')=='1' && get_option('scsl_show_if_members_only')=='0' && comments_open () && !is_user_logged_in ())
	{
	   //update_option('scsl_is_comment','1');
        echo scsl_get_preview('0','1');
	}
    
}

add_action( 'comment_form_before', 'scsl_comment_form_login_buttons' );

add_filter ('get_avatar', 'scsl_custom_avatar', 10, 5);

function scsl_custom_avatar( $avatar, $id_or_email, $size, $default, $alt )
{
     
     
     if(get_option('scsl_use_avtar')=='1')
     {   
    $user = false;
		
    if ( is_numeric( $id_or_email ) ) {
			
        $id = (int) $id_or_email;
        $user = get_user_by( 'id' , $id );
			
        } elseif ( is_object( $id_or_email ) ) {
			
            if ( ! empty( $id_or_email->user_id ) ) {
                $id = (int) $id_or_email->user_id;
                $user = get_user_by( 'id' , $id );
            }
			
    } else {
        $user = get_user_by( 'email', $id_or_email );	
    }
		
    if ( $user && is_object( $user ) ) {
       
        $csavatar=get_cscurl("https://www.socleversocial.com/dashboard/get_avtars.php?site_id=".get_option('scsl_site_id')."&siteUid=".$user->data->ID."");
        
			
        if ($csavatar!='') {
                
                $avatar = "<img alt='{$alt}' src='{$csavatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
			
    }
    }
    return $avatar;
}


function general_soclever_login($resPonse,$is_from)
{
  
    global $wpdb;
    $fb_data=json_decode($resPonse);    
    
      
  $email=$fb_data->email;
  $member_id=$fb_data->member_id;
  $is_from=$is_from;
  $first_name=$fb_data->first_name;
  $last_name=$fb_data->last_name;
  $select_alreay = "SELECT ID,user_login,user_email,user_status FROM ".$wpdb->prefix."users WHERE user_email='".esc_sql($email)."'  LIMIT 1";
        $data=$wpdb->get_results($select_alreay);            
        if(count($data) > 0)
        {
            
            $id_use=$data[0]->ID;
             $is_new='0';
        }
        else
        {
            $pwd=rand(111111,999999);
        $fname=$first_name;
        $lname=$last_name;
        $creds['user_login'] = $fname.rand(1,100000);
        $creds['user_pass'] =$pwd;
        $creds['user_email'] =$email;
        $creds['user_nicename'] =$fname.' '.$laname;
        $creds['display_name'] =$fname.' '.$laname;
        $creds['first_name'] =$fname;
        $creds['last_name'] =$lname;
        $creds['user_status']='1';
        $ins_data=wp_insert_user($creds);
        $id_use=intval($ins_data);
        $is_new='1';
        }
        
  $select_user="select user_login from ".$wpdb->prefix."users where ID='".sanitize_text_field($id_use)."'";

$row_user=$wpdb->get_results($select_user);
$creds['user_login']=$row_user[0]->user_login;

        wp_set_current_user($id_use,$creds['user_login']);
        wp_set_auth_cookie($id_use);
        do_action('wp_login', $creds['user_login']);
        
        
$notify_cs=get_cscurl("https://www.socleversocial.com/dashboard/track_register_new.php?siteid=".get_option('scsl_site_id')."&action=notifycs&is_new=".$is_new."&is_from=".$is_from."&siteUid=".$id_use."&member_id=".$member_id);

if($notify_cs)
{
    scsl_custom_fun($notify_cs);
    if($is_new=='1' && get_option('scsl_email_notify')=='1')
    {
    scsl_send_reg_email($creds['user_login'],$is_from);    
    
    }
    
    if($is_new=='1' && get_option('scsl_email_notify_user')=='1')
    {
    wp_new_user_notification($id_use,$pwd);    
    
    }
    $isIosChrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false) ? true : false;
    
    if(isset($_GET['lch']))
    {
        $lch=$_GET['lch'];
    }
    else if($_COOKIE['lch'])
    {
        $lch=$_COOKIE['lch'];
    }
    
    
    $ic='0';
    if(isset($_GET['ic']))
    {
        $ic=$_GET['ic'];
    }
    else if($_COOKIE['ic'])
    {
        $ic=$_COOKIE['ic'];
    }
    
  
    if(!$isIosChrome)
    {
        
    
    //$red_url=($_COOKIE['lch']=='l')?get_site_url():$_COOKIE['lch'];
     ?>
     <script type="text/javascript">
     if(opener)
     {
     opener.location.href='<?php echo scsl_redirect_url($lch,$ic); ?>';
     close();
     }
     else
     {
        window.location.href='<?php echo scsl_redirect_url($lch,$ic); ?>';
     }
     </script>
     <?php
     }
     else
     {
     ?>
     <script type="text/javascript">
     window.location.href='<?php echo scsl_redirect_url($_GET['lch'],$_GET['ic']); ?>';
     </script>
     <?php   
     }
     exit;
    //header("location:".scsl_redirect_url()."");
}    
         
  
}
function scsl_menu_settings(){
	include('soclever_login.php');	
	}

add_action( 'admin_menu', 'cs_login_menu');

function cs_login_menu(){
    add_menu_page( 'Login Buttons By SoClever', 'Login Buttons By SoClever', 'manage_options', 'soclever_login', 'scslogin_html_page',plugins_url( 'scsl_css/sc_img.png', __FILE__ ), 82); 
}


function socleverlogin_plugin_parse_request($wp) {
    global $wpdb;
    
    if(isset($_GET['lch']) && $_GET['lch']!='')
{
    setcookie('lch',$_GET['lch'],time()+100,'/');

} 
if(isset($_GET['ic']) && $_GET['ic']!='')
{
    setcookie('ic',$_GET['ic'],time()+100,'/');

} 


    if (array_key_exists('socleverlogin-plugin', $wp->query_vars) 
            && $wp->query_vars['socleverlogin-plugin'] == 'social-login') {


 
require 'openid.php';
 
try
{
   
    
    $openid = new LightOpenID($_SERVER['HTTP_HOST']);
     
   
    if(!$openid->mode)
    {
         
        
        if(isset($_GET['login']))
        {
            
            $openid->identity = 'https://me.yahoo.com';
             
            
            $openid->required = array('contact/email','person/guid','dob','birthDate','namePerson' , 'person/gender' , 'pref/language' , 'media/image/default','birthDate/birthday');
             
            
            
            
            header('Location: ' . $openid->authUrl());
        }
        else
        {
            wp_die("Yahoo! login failed.");
        }
        
        
         
    }
     
    else if($openid->mode == 'cancel')
    {
        wp_die('User has canceled authentication!');
        //redirect back to login page ??
    }
     
    //Echo login information by default
    else
    {
        if($openid->validate())
        {
             $d = $openid->getAttributes();
             $cs_url="https://www.socleversocial.com/dashboard/";
            $strurl=(substr(get_option('siteurl'), -1) == '/' ? '' : '/');
            $cs_siteid=get_option('scsl_site_id');
            $yoursiteurl=get_option('siteurl').$strurl;

            $siteTitle=get_option('blogname');
            
         
         
            
        $request_url="https://www.socleversocial.com/dashboard/track_register_new.php?is_yh=1&siteid=".get_option('scsl_site_id')."&is_from=5&other=".urlencode(json_encode($d));
        $resPonse=get_cscurl($request_url);
        if($resPonse)
        {
           general_soclever_login($resPonse,'5'); 
            
        }    
            
            
             
        }
        else
        {
            //user is not logged in
        }
    }
}
 
catch(ErrorException $e)
{
    echo $e->getMessage();
}
 


        // process the request.
        // For now, we'll just call wp_die, so we know it got processed
        //wp_die('my-plugin ajax-handler!');
    }
}
add_action('parse_request', 'socleverlogin_plugin_parse_request');

function socleverlogin_plugin_query_vars($vars) {
    $vars[] = 'socleverlogin-plugin';
    return $vars;
}
add_filter('query_vars', 'socleverlogin_plugin_query_vars');

add_action('wp_ajax_scsvideo', 'scsl_app_video' );
add_action('wp_ajax_nopriv_scsvideo', 'scsl_app_video' );

function scsl_app_video()
{

 echo'<iframe src="//player.vimeo.com/video/118392066?title=0&byline=0&portrait=0" width="600" height="400" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
 exit;

}

add_action('wp_ajax_scstwlogin', 'scsl_login_tw' );
add_action('wp_ajax_nopriv_scstwlogin', 'scsl_login_tw' );
function scsl_login_tw()
{
  $tw_arr=array();
   if(isset($_GET['lch']) && $_GET['lch']!='')
{
    setcookie('lch',$_GET['lch'],time()+100,'/');

} 
 if(isset($_GET['ic']))
{
    setcookie('ic',$_GET['ic'],time()+100,'/');

}
  $name_arr=explode(" ",sanitize_text_field($_GET['full_name']));
  $tw_arr['email']=sanitize_text_field($_GET['uemail']);
  $tw_arr['first_name']=sanitize_text_field($name_arr[0]);
  $tw_arr['last_name']=sanitize_text_field($name_arr[1]);
  $tw_arr['member_id']=sanitize_text_field($_GET['member_id']);
  general_soclever_login(json_encode($tw_arr),'4');
      
}    

add_action('wp_ajax_scsfblogin', 'scsl_login_fb' );
add_action('wp_ajax_nopriv_scsfblogin', 'scsl_login_fb' );
function scsl_login_fb()
{
    
    global $wpdb;
    
  if(isset($_GET['lch']) && $_GET['lch']!='')
{
    setcookie('lch',$_GET['lch'],time()+100,'/');

} 
 if(isset($_GET['ic']))
{
    setcookie('ic',$_GET['ic'],time()+100,'/');

}
 
   $get_fb=get_cscurl("https://www.socleversocial.com/dashboard/get_fb_data.php?siteid=".get_option('scsl_site_id')."");
   
   if($get_fb!='0')
   {
    $app_arr=explode("~",$get_fb);
   $app_id = $app_arr[0];
   $my_url=admin_url('admin-ajax.php')."?action=scsfblogin";
   $app_secret = $app_arr[1];
   $code = $_REQUEST["code"];
   if(isset($_REQUEST['error']))
   {
    if(isset($_REQUEST['error_reason']) && $_REQUEST['error_reason']=='user_denied'){
        
        echo $_REQUEST['error'];
        echo"<br/><a href='".get_site_ur()."'>Go to site</a>";
       exit;
   }
   }
   if(empty($code)) {
        $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            . $app_id . "&redirect_uri=" . urlencode($my_url)."&scope=email,user_birthday,user_relationships,user_location,user_hometown,user_friends,user_likes&display=popup";

        echo("<script>top.location.href='".$dialog_url."'</script>");
    }

    $token_url = "https://graph.facebook.com/oauth/access_token?client_id="
        . $app_id . "&redirect_uri=" . urlencode($my_url) . "&client_secret="
        . $app_secret . "&code=" . $code;

	$ch = curl_init();
                    	
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	//Get Access Token
	curl_setopt($ch, CURLOPT_URL,$token_url);
	$access_token = curl_exec($ch);
  
	curl_close($ch);
	
	
    $graph_url = "https://graph.facebook.com/v2.2/me?" . $access_token."&fields=id,name,first_name,last_name,timezone,email,picture,gender,locale,birthday,relationship_status,location,hometown,friends.limit%280%29,likes{id,name}";
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $graph_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $temp_user = curl_exec($ch);
    curl_close($ch);
	$fbuser_old = $temp_user;	
	$fbuser=json_decode($fbuser_old);
    if($fbuser_old && $fbuser->email!="")
	{
	   
        $request_url="https://www.socleversocial.com/dashboard/track_register_new.php?app_id=".$app_id."&is_fb=1&friend_data=".$fbuser->friends->summary->total_count."&siteid=".get_option('scsl_site_id')."&other=".urlencode($fbuser_old);
        $resPonse=get_cscurl($request_url);
        if($resPonse)
        {
         general_soclever_login($resPonse,'1');
                      
        }
        
   }         
    wp_die();
   }
   else
   {
    echo "<h3>Login with FB failed.</h3><a href='".get_site_ur()."'></a>";
   }   
    
}

add_action('wp_ajax_scslogin', 'scsl_login' );
add_action('wp_ajax_nopriv_scslogin', 'scsl_login' );
function scsl_login(){
  global $wpdb;
  
  
  if(isset($_GET['lch']) && $_GET['lch']!='')
{
    setcookie('lch',$_GET['lch'],time()+100,'/');

} 

if(isset($_POST['lch']))
    {
        $lch=$_POST['lch'];
        
    }
    else if(isset($_GET['lch']))
    {
        
        $lch=$_GET['lch'];
    }
    else
    {
        $lch="";
    }
    

if(isset($_POST['ic']))
    {
        $ic=$_POST['ic'];
        
    }
    else if(isset($_GET['ic']))
    {
        
        $ic=$_GET['ic'];
    }
    else
    {
        $ic="";
    }


  $email=(isset($_GET['email']))?$_GET['email']:$_POST['email'];
  $member_id=(isset($_GET['member_id']))?$_GET['member_id']:$_POST['member_id'];
  $is_from=(isset($_GET['is_from']))?$_GET['is_from']:$_POST['is_from'];
  $first_name=(isset($_GET['first_name']))?$_GET['first_name']:$_POST['first_name'];
  $last_name=(isset($_GET['last_name']))?$_GET['last_name']:$_POST['last_name'];
  $select_alreay = "SELECT ID,user_login,user_email,user_status FROM ".$wpdb->prefix."users WHERE user_email='".esc_sql($email)."'  LIMIT 1";
        $data=$wpdb->get_results($select_alreay);            
        if(count($data) > 0)
        {
            
            $id_use=$data[0]->ID;
             $is_new='0';
        }
        else
        {
            $pwd=rand(111111,999999);
        $fname=$first_name;
        $lname=$last_name;
        $creds['user_login'] = $fname.rand(1,100000);
        $creds['user_pass'] =$pwd;
        $creds['user_email'] =$email;
        $creds['user_nicename'] =$fname.' '.$laname;
        $creds['display_name'] =$fname.' '.$laname;
        $creds['first_name'] =$fname;
        $creds['last_name'] =$lname;
        $creds['user_status']='1';
        $ins_data=wp_insert_user($creds);
        $id_use=intval($ins_data);
        $is_new='1';
        }
        
  $select_user="select user_login from ".$wpdb->prefix."users where ID='".sanitize_text_field($id_use)."'";

$row_user=$wpdb->get_results($select_user);
/*$length = 8;
$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$string = "";    
for ($p = 0; $p<$length; $p++) {
				$string .= $characters[mt_rand(0, strlen($characters))];
}
$new_pass=$string;
$user_pass = wp_hash_password($new_pass);
$wpdb->update( $wpdb->prefix.'users', array( 'user_pass' =>$user_pass),array('ID'=>$id_use));*/
$creds['user_login']=$row_user[0]->user_login;
/*$creds['user_password']=$new_pass;
$creds['remember'] = true;
*/

wp_set_current_user($user_id,$creds['user_login']);
        wp_set_auth_cookie($id_use);
        do_action('wp_login', $creds['user_login']);
        if($is_new=='1' && get_option('scsl_email_notify_user')=='1')
    {
    wp_new_user_notification($id_use,$pwd);    
    
    }


	

	



//$userlogin=wp_signon($creds,true);




$notify_cs=get_cscurl("https://www.socleversocial.com/dashboard/track_register_new.php?siteid=".get_option('scsl_site_id')."&action=notifycs&is_new=".$is_new."&is_from=".$is_from."&siteUid=".$id_use."&member_id=".$member_id);
if($notify_cs)
{
    scsl_custom_fun($notify_cs);
    $red_url=($_COOKIE['lch']=='l')?get_site_url():$_COOKIE['lch'];
    
    
    if($is_new=='1' && get_option('scsl_email_notify')=='1')
    {
    scsl_send_reg_email($creds['user_login'],$is_from);    
    
    }
    if($is_from=='3' ||$is_from=='2' )
    {
      
        exit(scsl_redirect_url($lch,$ic));
    }
    ?>
    
     <script type="text/javascript">
      window.location.href='<?php echo scsl_redirect_url($lch,$ic); ?>';
      </script>
     
    
    <?php
    
    /*if($is_from=='7')
    {
        
      ?>
      <script type="text/javascript">
      window.location.href='<?php echo scsl_redirect_url(); ?>';
      </script>
      
      
      <?php  
    exit;
        //header("location:".scsl_redirect_url()."");
    


        //header("location:".$redirect_url."");
    }
    else
    {
        ?>
         <script type="text/javascript">
      window.location.href='<?php echo scsl_redirect_url(); ?>';
      </script>
     
        
        <?php
        echo scsl_redirect_url();
        
    }*/
  
 } 
wp_die(); 
}


function scsl_redirect_url($lch='',$ic)
{
    
    
    
    $red_url=($_COOKIE['lch']=='l')?admin_url():$_COOKIE['lch'];

    //$redirect_to = home_url ();
    $redirect_to =$red_url;

	if (!empty ($_GET ['redirect_to']))
	{
	$redirect_to = $_GET ['redirect_to'];
	$redirect_to_safe = true;
	}
	else
	{
	
    	
		if (get_option('scsl_login_form_redirect')!='')
		{
		  //echo strtolower(get_option('scsl_login_form_redirect'));
			switch (strtolower(get_option('scsl_login_form_redirect')))
			{
				case 'current':
                        if(strtolower($lch)=='l')
                        {
                            $lch=admin_url();
                        }
						$redirect_to =($lch!='')?$lch:scsl_login_get_current_url();
                       
					
					break;

				//Dashboard
				case 'dashboard':
					$redirect_to = admin_url();
					break;

				//Custom
				case 'custom':
					if (strlen(get_option('scsl_login_form_redirect_url')) > 0)
					{
						$redirect_to = trim (get_option('scsl_login_form_redirect_url'));
					}
					break;

				//Default/Homepage
				default:
				case 'homepage':
					$redirect_to = home_url ();
					break;
			}
		}
	}

if($ic=='1')
{
    $redirect_to=$lch;
}
	
if(empty($redirect_to))
{
    $redirect_to = home_url ();
}


return rawurldecode($redirect_to);


}

function scsl_login_get_current_url ()
{
	$red_url=($_COOKIE['lch']=='l')?admin_url():$_COOKIE['lch'];
    return $red_url;
}


if(isset($_POST['submit_login']) && $_POST['submit_login']=='Submit' )
{
   update_option("scsl_valid_domain",'0');
   
    
     $res_ponse_str=file_get_contents('https://www.socleversocial.com/dashboard/wp_activate.php?site_id='.sanitize_text_field($_POST['client_id']).'&api_key='.sanitize_text_field($_POST['api_key']).'&api_secret='.sanitize_text_field($_POST['api_secret']).'&ser=5&isnew_login=1');
    if(!$res_ponse_str)
    {
        $res_ponse_str=get_cscurl('https://www.socleversocial.com/dashboard/wp_activate.php?site_id='.sanitize_text_field($_POST['client_id']).'&api_key='.sanitize_text_field($_POST['api_key']).'&api_secret='.sanitize_text_field($_POST['api_secret']).'&ser=5&isnew_login');
    }
    else
    {
        update_option('scsl_module_loaded','1');
    }
   
    if(!$res_ponse_str)
    {
      echo "<h3>Please check your php.ini's setting for FSOCKOPEN or CURL</h2>";
      wp_die();  
    }
    else
    {
        if(get_option('scsl_module_loaded')=='0')
        {
        update_option('scsl_module_loaded','2');
        }
    }
   
    $res_ponse=explode("~~",$res_ponse_str);
    if(sanitize_text_field($_POST['api_key'])==$res_ponse[0] && sanitize_text_field($_POST['api_secret'])==$res_ponse[1] && $res_ponse[0]!='0')
    {
        echo "<h2>Thanks for authenticate with SoCleverSocial.com.....</h2>";
        
        /*echo"<br/><h3>Preview</h3><br/>";
        echo htmlspecialchars_decode($res_ponse[2]);*/
        update_option("scsl_valid_domain",'1');
        update_option("scsl_site_id",sanitize_text_field($_POST['client_id']));
        update_option("scsl_api_key",sanitize_text_field($_POST['api_key']));
        update_option("scsl_api_secret",sanitize_text_field($_POST['api_secret']));
        update_option("scsl_domain",sanitize_text_field($_POST['scsl_domain']));
        ?>
        <script type="text/javascript">
         setTimeout(function(){ window.location='admin.php?page=soclever_login'; }, 3000);
         </script>
        <?php
        exit;
    }
    else
    {
       
        echo"<h2 margin='40px;width:90%;'>Authentication failed.If you have already account then please contact us at support@socleversocial.com.If you haven't socleversocial.com account then <a href='https://www.socleversocial.com/register/?wpd=".base64_encode(get_site_url())."' target='_blank'>Register</a> your account</h2>";
        ?>
        <script type="text/javascript">
         setTimeout(function(){ window.location='admin.php?page=soclever_login'; }, 3000);
         </script>
        <?php
        exit;
    }
   
}

if(get_option('scsl_valid_domain')=='1')
{
 
 add_action('wp_head', 'scsl_login_head_front');
 
 function scsl_login_head_front()
{
    if(!is_user_logged_in() && get_option('scsl_show_comment')=='1')
    {
    echo '<script type="text/javascript" src="https://www.socleversocial.com/dashboard/client_share_js/client_'.get_option('scsl_site_id').'_login.js"></script>
<script type="text/javascript">
                                        csloginjs.init([\''.get_option('scsl_api_key').'\',\''.get_option('scsl_site_id').'\',\''.get_option('scsl_api_secret').'\',\''.get_option('scsl_domain').'\']);
                                            csloginjs.validateCsApi();
                                            
                                        </script>
    ';
   } 
}

    
    add_action('login_head', 'scsl_login_head');

function scsl_login_head()
{
    if(!is_user_logged_in() && get_option('scsl_show_in_loginform')=='1')
    {
    echo '<script type="text/javascript" src="https://www.socleversocial.com/dashboard/client_share_js/client_'.get_option('scsl_site_id').'_login.js"></script>
<script type="text/javascript">
                                        csloginjs.init([\''.get_option('scsl_api_key').'\',\''.get_option('scsl_site_id').'\',\''.get_option('scsl_api_secret').'\',\''.get_option('scsl_domain').'\']);
                                            csloginjs.validateCsApi();
                                            
                                        </script>
    ';
   } 
}




add_action ('sidebar_login_widget_logged_out_content_end', 'scsl_login_buttons_show');





add_filter( 'login_form', 'scsl_login_buttons_show');
function scsl_login_buttons_show()
 {
    if(!is_user_logged_in() && get_option('scsl_show_in_loginform')=='1' )
    {
    $js_buttons=scsl_get_preview('0','0');   
    $display_content .=$js_buttons;
    //update_option('scsl_is_comment','0');
    echo $display_content;
    }
}
}




if(isset($_POST['save_login']) && $_POST['save_login']=='Save' )
{
update_option('scsl_button_style',sanitize_text_field($_POST['scsl_button_style']));
update_option('scsl_button_size',sanitize_text_field($_POST['scsl_button_size']));
if(isset($_POST['scsl_network']))
{
update_option('scsl_network',sanitize_text_field(implode(",",$_POST['scsl_network'])));    
}
update_option('scsl_caption',sanitize_text_field($_POST['scsl_caption']));
update_option('scsl_add_column',sanitize_text_field($_POST['scsl_add_column']));
update_option('scsl_email_notify',sanitize_text_field($_POST['scsl_email_notify']));
update_option('scsl_email_notify_user',sanitize_text_field($_POST['scsl_email_notify_user']));

update_option('scsl_use_avtar',sanitize_text_field($_POST['scsl_use_avtar']));
update_option('scsl_show_comment',sanitize_text_field($_POST['scsl_show_comment']));
update_option('scsl_comment_auto_approve',sanitize_text_field($_POST['scsl_comment_auto_approve']));

update_option('scsl_show_in_loginform',sanitize_text_field($_POST['scsl_show_in_loginform']));
update_option('scsl_login_form_redirect',sanitize_text_field($_POST['scsl_login_form_redirect']));
update_option('scsl_login_form_redirect_url',sanitize_text_field($_POST['scsl_login_form_redirect_url']));


update_option('scsl_show_in_regpage',sanitize_text_field($_POST['scsl_show_in_regpage']));
update_option('scsl_reg_page_redirect',sanitize_text_field($_POST['scsl_reg_page_redirect']));
update_option('scsl_reg_page_redirect_url',sanitize_text_field($_POST['scsl_reg_page_redirect_url']));

update_option('scsl_show_if_members_only',sanitize_text_field($_POST['scsl_show_if_members_only']));

update_option('customlogin_fb',sanitize_text_field($_POST['customlogin_fb']));
update_option('customlogin_gp',sanitize_text_field($_POST['customlogin_gp']));
update_option('customlogin_li',sanitize_text_field($_POST['customlogin_li']));
update_option('customlogin_tw',sanitize_text_field($_POST['customlogin_tw']));
update_option('customlogin_yh',sanitize_text_field($_POST['customlogin_yh']));
update_option('customlogin_ms',sanitize_text_field($_POST['customlogin_ms']));
update_option('customlogin_ig',sanitize_text_field($_POST['customlogin_ig']));
update_option('customlogin_pp',sanitize_text_field($_POST['customlogin_pp']));    
}    

function scsl_send_reg_email($username,$is_from)
{
    $provider_arr=array("1"=>"Facebook","2"=>"LinkedIN","3"=>"Google+","4"=>"Twitter","5"=>"Yahoo","6"=>"Instagram","7"=>"Paypal","8"=>"Microsoft");
	//The blogname option is escaped with esc_html on the way into the database
	$blogname = wp_specialchars_decode (get_option ('blogname'), ENT_QUOTES);

	//Setup Mail Header
	$recipient = get_bloginfo ('admin_email');
	$subject = 'Registration using Social Network - '. $blogname.'';

	//Setup Mail Body
	$body = 'New user registered on your site '.$blogname."\r\n\r\n";
	$body .= 'Username: '.$username."\r\n\r\n";
	$body .= 'Social Network: '.$provider_arr[$is_from]."\r\n";

	
	@wp_mail ($recipient, $subject, $body);
    
}

function scsl_get_preview($is_preview='0',$is_comment)
{
    
    
    
    if($is_comment=='1')
    {
    add_action('init', 'writecookies');
    }
    else
    {
        add_action('init','nowritecookies');
    }
    
    $network=explode(",",get_option('scsl_network'));
    $button_size=get_option('scsl_button_size');
    $btn_style=get_option('scsl_button_style');
    $caption_text=get_option('scsl_caption');
    
    
    if(strtolower($btn_style)=='custom')
    {
      
      $fbpath=get_option('customlogin_fb');
      $gppath=get_option('customlogin_gp');
      $twpath=get_option('customlogin_tw');
      $lipath=get_option('customlogin_li');
      $yhpath=get_option('customlogin_yh');
      $mspath=get_option('customlogin_ms');
      $igpath=get_option('customlogin_ig');
      $pppath=get_option('customlogin_pp');
      
      $previewDiv='';
        $fb_div="";
        if(in_array('2',$network) && !empty($fbpath))
        {
            
            $fb_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$fbpath.'" alt="Login with Facebook" title="Login with Facebook"></div>';
            $previewDiv .=$imgdiv;
            $fb_div .='csbutton.init([\''.$imgdiv.'\',\'100%\' ,\'100%\',\'login\',\'facebook\',\''.$is_comment.'\']);'.PHP_EOL;
            $fb_div .='csbutton.putCsbutton();         
                      </script>';
        
            
        }
        $gp_div="";
        if(in_array('4',$network) && !empty($gppath))
        {
            $gapi=get_cscurl('https://www.socleversocial.com/dashboard/get_fb_data.php?action=gapi&siteid='.get_option('scsl_site_id').'');
            
            $gp_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$gppath.'" alt="Login with Google+" title="Login with Google+"></div>';
            $previewDiv .=$imgdiv;
            $gp_div .='csbutton.init([\''.$imgdiv.'\',\'100%\' ,\'100%\',\'login\',\''.$gapi.'\',\''.$is_comment.'\']);'.PHP_EOL;
            $gp_div .='csbutton.putCsbutton();         
                      </script>';
          
        }
        $li_div="";
        if(in_array('7',$network) && !empty($lipath))
        {
            
            $li_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$lipath.'" alt="Login with LinkedIN" title="Login with LinkedIN"></div>';
            $li_div .='csbutton.init([\''.$imgdiv.'\',\'100%\' ,\'100%\',\'login\',\'li\',\''.$is_comment.'\']);'.PHP_EOL;
            $li_div .='csbutton.putCsbutton();         
                      </script>';
          
        }
        $tw_div="";
        if(in_array('13',$network) && !empty($twpath))
        {
            
            $tw_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$twpath.'" alt="Login with Twitter" title="Login with Twitter"></div>';
            $previewDiv .=$imgdiv;
            $tw_div .='csbutton.init([\''.$imgdiv.'\',\'100%\' ,\'100%\',\'login\',\'twitter\',\''.$is_comment.'\']);'.PHP_EOL;
            $tw_div .='csbutton.putCsbutton();         
                      </script>';
          
        }
        $yh_div="";
        if(in_array('15',$network) && !empty($yhpath))
        {
            
            $yh_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$yhpath.'" alt="Login with Yahoo!" title="Login with Yahoo!"></div>';
            $previewDiv .=$imgdiv;
            $yh_div .='csbutton.init([\''.$imgdiv.'\',\'100%\' ,\'100%\',\'login\',\'yahoo\',\''.$is_comment.'\']);'.PHP_EOL;
            $yh_div .='csbutton.putCsbutton();         
                      </script>';
          
        }
        $ms_div="";
        if(in_array('8',$network) && !empty($mspath))
        {
             
            $ms_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$mspath.'" alt="Login with Microsoft" title="Login with Microsoft"></div>';
            $previewDiv .=$imgdiv;
            $ms_div .='csbutton.init([\''.$imgdiv.'\',\'100%\' ,\'100%\',\'login\',\'microsoft\',\''.$is_comment.'\']);'.PHP_EOL;
            $ms_div .='csbutton.putCsbutton();         
                      </script>';
          
        }
        $pp_div="";
        if(in_array('16',$network) && !empty($pppath))
        {
             
            $pp_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$pppath.'" alt="Login with PayPal" title="Login with PayPal"></div>';
            $previewDiv .=$imgdiv;
            $pp_div .='csbutton.init([\''.$imgdiv.'\',\'100%\' ,\'100%\',\'login\',\'paypal\',\''.$is_comment.'\']);'.PHP_EOL;
            $pp_div .='csbutton.putCsbutton();         
                      </script>';
          
        }
        $ig_div="";
        if(in_array('5',$network) && !empty($igpath))
        {
            
            $ig_div .='<script type="text/javascript">';
            $imgdiv='<div style="display:inline-block;width: 100%; height:100%;"><img src="'.$igpath.'" alt="Login with Instagram" title="Login with Instagram"></div>';
            $previewDiv .=$imgdiv;
            $ig_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'px\',\'login\',\'instagram\',\''.$is_comment.'\']);'.PHP_EOL;
            $ig_div .='csbutton.putCsbutton();         
                      </script>';
          
        }

        
        $login_plugin_start=$login_plugin_end="";
        if($is_preview=='1')
        {
            return $previewDiv;
        }
        else
        {
            
                $login_plugin_start .='<div style="clear:both;margin:10px 0px 10px 0px;">'.PHP_EOL.'<h3 style="line-height:25px;">'.$caption_text.'</h3>'.PHP_EOL;
                $login_plugin_start .='<script type="text/javascript" src="https://www.socleversocial.com/dashboard/client_share_js/csloginbuttons_'.get_option('scsl_site_id').'.js"></script>'.PHP_EOL;
                $login_plugin_end .='<br/><input type="hidden" id="scsl_is_comment" value="'.$is_comment.'"></div>';
            
                return $login_plugin_start.PHP_EOL.$fb_div.PHP_EOL.$gp_div.PHP_EOL.$li_div.PHP_EOL.$tw_div.PHP_EOL.$yh_div.PHP_EOL.$ms_div.PHP_EOL.$pp_div.PHP_EOL.$ig_div.$login_plugin_end;
        }
        }
        else
        {
            
        
        $login_buttons=get_cscurl("https://www.socleversocial.com/dashboard/login_buttons.php?site_id=".get_option('scsl_site_id')."&bsize=".$button_size."&bstyle=".$btn_style."&is_preview=".$is_preview."&caption=".base64_encode($caption_text)."&frm=l&is_comment=".$is_comment."");
    
    
        return $login_buttons;
        
            
            
        }
      
      
                
    
    
}


function writecookies()
{
    
    setcookie("ic",1,time()+100,'/');
}
function nowritecookies()
{
    
    setcookie("ic",0,time()+100,'/');
}

function scslogin_html_page()
{
    
  
	


 wp_register_style( 'scsl-style', plugins_url('scsl_css/scsl_style_login_final.css', __FILE__) );
 wp_enqueue_style( 'scsl-style' );
 
 $loginProviderArray=array("fb"=>"Facebook","gp"=>"Google+","tw"=>"Twitter","li"=>"LinkedIN","yh"=>"Yahoo!","ms"=>"Microsoft","pp"=>"PayPal","ig"=>"Instagram");
 
 ?>
 <script>
 
function show_activate_tab(tab_id)
{
    
    if(tab_id=='2')
    {
        document.getElementById("tab2li").className="active";
        document.getElementById("tab1li").className="";
        document.getElementById("tab2").style.display="inline-block";
        document.getElementById("tab1").style.display="none";
    }
    else
    {
        document.getElementById("tab1li").className="active";
        document.getElementById("tab2li").className="";
        document.getElementById("tab1").style.display="inline-block";
        document.getElementById("tab2").style.display="none";
    }
}

 </script>
<header>
	<div class="main">
    	<div class="logo">
        	<a href="https://www.socleversocial.com/" target="_blank"><img src="<?php echo plugins_url('scsl_css/logo.png', __FILE__); ?>" alt="SoClever Social" /></a>
        </div>
    </div>
</header>
<section>
	<div class="main">
    
    
 <div class="sect-left" style="margin-top: 15px;">
 	<nav>
    <?php if(get_option('scsl_valid_domain')=='0') { ?>
            	<ul>
                	<li class="active" id="tab1li"><a onclick="show_activate_tab('1');" href="javascript:void(0);">Your SoClever API Setting</a></li>
                    <li id="tab2li"><a href="javascript:void(0);"  onclick="show_activate_tab('2');">SoClever Social Login Setting</a></li>
                </ul>
     <?php } else { ?>
     	<ul>
                	
                    <li class="active" style="width: 100%;background-repeat: repeat;"><a>SoClever Social Login Setting</a></li>
                </ul>
     <?php } ?>
                
            </nav>
            
        
<?php if(get_option('scsl_valid_domain')=='0') { ?>
      

    	<div id="tab1">
            <div class="box1 blue_bg api_step">
            	<h2 class="bov-title">
                	Step 1 - Create a SocleverSocial.com account
                </h2>              
              <div class="main-bx1">
               	<p>To get started, register your <span>Soclever Social account</span> and find your <span>API key</span> in the site settings. If you already have an account please log in.</p>
                <p><a href="https://www.socleversocial.com/register/?wpd=<?php echo base64_encode(get_site_url()); ?>" target="_blank" class="butn green">Get your FREE API Key</a>
                <a href="https://www.socleversocial.com/dashboard/" target="_blank" class="butn blue">Login</a></p>
                
              </div>
            </div>
           <form method="post" action="">
  <?php wp_nonce_field('update-options'); ?>
            <div class="box1 blue_bg api_step">
            	<h2 class="bov-title">
                	Step 2 - Enter your API Settings
                </h2>
                
              <div class="main-bx1">
               	<label>Client ID</label>
                <input type="text" placeholder="" name="client_id" class="input-txt">
                </div>
                <div class="main-bx1">
               	<label>API Key</label>
                <input type="text" placeholder="" name="api_key" class="input-txt">
                </div>
                <div class="main-bx1">
               	<label>API Secret</label>
                <input type="text" placeholder="" name="api_secret" class="input-txt">
                </div>
                <div class="main-bx1">
               	<label>Valid Domain</label>
                <input type="text" placeholder="" name="scsl_domain" class="input-txt">
                </div>
                <div class="main-bx1">
                   <label>&nbsp;</label>	
               	  <input type="submit" name="submit_login" id="button" value="Submit" class="butn blue">
                </div>
           	</div>
            </form>
            </div>
<?php } ?>

<!--new html start ---->
    <?php wp_nonce_field('update-options'); ?>
        <div id="tab2" <?php if(get_option('scsl_valid_domain')=='0'){ ?> style="display:none;" <?php } ?> >                
<form class="login-form mt-lg" action="" method="post" name="authosharefrm" enctype="multipart/form-data">

    	
        	
            <div class="box1">
            	<h2 class="bov-title">
                	General Setting
                </h2>
              <div class="main-bx1">
               	<p><strong>Enter Caption text to be shown on top of social login box</strong></p>
                <?php
									$scsl_caption =(get_option('scsl_caption')) ? get_option('scsl_caption') : 'Login with:';
								?>
                <input class="input-txt" type="text" name="scsl_caption" value="<?php echo $scsl_caption; ?>">
                </div>
                
                <div class="main-bx1">
               	<p><strong>Social Login button style</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_style" id="radio3" onclick="show_cscustom_div('none');"  value="ic" <?php echo (get_option('scsl_button_style') == 'ic' ? 'checked="checked"' : ''); ?> />
            	<label for="radio3" class="css-label radGroup2">Square Icons</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_style" id="radio4" onclick="show_cscustom_div('none');"  value="fc" <?php echo (get_option('scsl_button_style') == 'fc' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio4" class="css-label radGroup2">Colored Logos</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_style" id="radio5" onclick="show_cscustom_div('none');"  value="fg" <?php echo (get_option('scsl_button_style') == 'fg' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio5" class="css-label radGroup2">Grey Logos</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_style" id="radio6" onclick="show_cscustom_div('inline-block');" value="custom" <?php echo (get_option('scsl_button_style') == 'custom' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio6" class="css-label radGroup2">Custom</label>
                <script type="text/javascript">
                function show_cscustom_div(show_custom)
                {
                    
                    document.getElementById('custom_styles').style.display=show_custom;                
                    
                  }
                
                
                </script>
                </div>
                <div id="custom_styles" style="<?php echo (get_option('scsl_button_style')!='custom')?'display:none;':'';  ?>" >
                
                <?php 
                foreach($loginProviderArray as $key=>$val)
                {
                ?>
                
                <div class="main-bx1" id="<?php echo $key; ?>custom">
                <p><?php echo $val; ?></p>
                <input class="input-txt" type="text" name="customlogin_<?php echo $key; ?>" value="<?php echo get_option('customlogin_'.$key.''); ?>">
                </div>
                
                <?php    
                    
                }
                
                ?>                
                
                </div>
              </div>
              
              <div class="main-bx1">
               	<p><strong>Social Login button size</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_size" id="radio6" value="30" <?php echo (get_option('scsl_button_size') == '30' ? 'checked="checked"' : ''); ?> />
            	<label for="radio6" class="css-label radGroup2">30px</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_size" id="radio7" value="40" <?php echo (get_option('scsl_button_size') == '40' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio7" class="css-label radGroup2">40px</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_size" id="radio8" value="50" <?php echo (get_option('scsl_button_size') == '50' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio8" class="css-label radGroup2">50px</label>
                </div>
                  <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_size" id="radio9" value="60" <?php echo (get_option('scsl_button_size') == '60' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio9" class="css-label radGroup2">60px</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_button_size" id="radio10" value="65" <?php echo (get_option('scsl_button_size') == '65' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio10" class="css-label radGroup2">65px</label>
                </div>
              </div>  
              
              <div class="main-bx1">
               	<p><strong>Email when new user registers with social network?</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_email_notify" id="radio11" value="1" <?php echo (get_option('scsl_email_notify') == '1' ? 'checked="checked"' : ''); ?> />
            	<label for="radio11" class="css-label radGroup2">Yes</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_email_notify" id="radio12" value="0" <?php echo (get_option('scsl_email_notify') == '0' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio12" class="css-label no radGroup2">No</label>
                </div>
                
              </div>
              
              <div class="main-bx1">
               	<p><strong>Send email to user on registration with social network?</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_email_notify_user" id="radio13" value="1" <?php echo (get_option('scsl_email_notify_user') == '1' ? 'checked="checked"' : ''); ?> />
            	<label for="radio11" class="css-label radGroup2">Yes</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_email_notify_user" id="radio14" value="0" <?php echo (get_option('scsl_email_notify_user') == '0' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio14" class="css-label no radGroup2">No</label>
                </div>
                
              </div>
              
              
              
              <div class="main-bx1">
               	<p><strong>Use user's social network avtar as your site's default avtar?</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_use_avtar" id="radio15" value="0" <?php echo (get_option('scsl_use_avtar') == '0' ? 'checked="checked"' : ''); ?> />
            	<label for="radio15" class="css-label radGroup2">No</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_use_avtar" id="radio16" value="1" <?php echo (get_option('scsl_use_avtar') == '1' ? 'checked="checked"' : ''); ?> />
            	<label for="radio16" class="css-label no radGroup2">Yes, if user's social network has avtar</label>
                </div>
                
              </div>
            </div>
            
            <div class="box1">
            	<h2 class="bov-title">
                	Comment Setting
                </h2>
                
              <div class="main-bx1">
               	<p><strong>Display Social login box on comment area?</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_show_comment" id="radio18" value="1" <?php echo (get_option('scsl_show_comment') == '1' ? 'checked="checked"' : ''); ?> />
            	<label for="radio18" class="css-label radGroup2">Yes</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_show_comment" id="radio19" value="0" <?php echo (get_option('scsl_show_comment') == '0' ? 'checked="checked"' : ''); ?> />
            	<label for="radio19" class="css-label no radGroup2">No</label>
                </div>
                
              </div>
              
              <div class="main-bx1">
               	<p><strong>Show the Social Login buttons in the comment area if comments are disabled for guests?</strong></p>
                <p>The buttons will be displayed below the "You must be logged in to leave a comment" notice.
                </p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_show_if_members_only" id="radio20" value="1" <?php echo (get_option('scsl_show_if_members_only') == 1 ? 'checked="checked"' : ''); ?> />
            	<label for="radio20" class="css-label radGroup2">Yes</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_show_if_members_only" id="radio21" value="0" <?php echo (get_option('scsl_show_if_members_only') == 0 ? 'checked="checked"' : ''); ?> />
            	<label for="radio21" class="css-label no radGroup2">No</label>
                </div>
                
              </div>
              
              <div class="main-bx1">
               	<p><strong>Approve comments automatically for users who login with Social network?</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_comment_auto_approve" id="radio22" value="1" <?php echo (get_option('scsl_comment_auto_approve') == '1' ? 'checked="checked"' : ''); ?> />
            	<label for="radio22" class="css-label radGroup2">Yes</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_comment_auto_approve" id="radio23" value="1" <?php echo (get_option('scsl_comment_auto_approve') == '0' ? 'checked="checked"' : ''); ?> />
            	<label for="radio23" class="css-label no radGroup2">No</label>
                </div>
                
              </div>
           	</div>
            
            <div class="box1">
            	<h2 class="bov-title">
                	Login Setting
                </h2>
                
              <div class="main-bx1">
               	<p><strong>Display login buttons in login form?</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_show_in_loginform" id="radio24" value="1" <?php echo (get_option('scsl_show_in_loginform') == '1' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio24" class="css-label radGroup2">Yes</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_show_in_loginform" value="0" id="radio25" <?php echo (get_option('scsl_show_in_loginform') == '0' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio25" class="css-label no radGroup2">No</label>
                </div>
                
              </div>
              
              <div class="main-bx1">
               	<p><strong>Choose landing page after login</strong></p>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_login_form_redirect" value="current" id="radio26" <?php echo (get_option('scsl_login_form_redirect') == 'current' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio26" class="css-label radGroup2">Current page</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_login_form_redirect" value="homepage" id="radio27" <?php echo (get_option('scsl_login_form_redirect') == 'homepage' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio27" class="css-label no radGroup2">Home page</label>
                </div>
                 <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_login_form_redirect" value="dashboard" id="radio28" <?php echo (get_option('scsl_login_form_redirect') == 'dashboard' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio28" class="css-label radGroup2">Wordpress Dashboard</label>
                </div>
                <div class="lbls radio-danger">
               		 <input type="radio" name="scsl_login_form_redirect" value="custom" id="radio29" <?php echo (get_option('scsl_login_form_redirect') == 'custom' ? 'checked="checked"' : ''); ?>  />
            	<label for="radio29" class="css-label no radGroup2">Following URL:</label>
              <div class="input-txt1">
                	<input class="input-txt" type="text" name="scsl_login_form_redirect_url" value="<?php echo htmlspecialchars (get_option('scsl_login_form_redirect_url')); ?>">
                </div>
                </div>
                
                <div class="bt-txt">
               	  <p class="italic"><span class="bold">Social Networks</span> (Please select Social Networks at your<a class="sky" href="https://www.socleversocial.com/dashboard/wp_login_setting.php"> SoClever dashboard)</span></p>
                    <!--p class="red">Please provide valid Soclever API setting.</p-->
                    
                    <?php 
                    $savedSetting='0';
                    if(get_option('scsl_valid_domain')=='0')
                    {
                        echo'<p calss="red">Please provide valid Soclever API setting.</p>';
                    }
                    else
                    {
                     $savedSetting=get_cscurl("https://www.socleversocial.com/dashboard/wp_login_setting.php?site_id=".get_option('scsl_site_id')."&action=preview&button_style=".get_option('scsl_button_style')."&button_size=".get_option('scsl_button_size')."");
                    if($savedSetting=='0')
                    {
                        echo'<p calss="red">No provider selected on SoCleverSocial Dashboard</font>';
                    }
                    else
                    {
                        echo $savedSetting;
                    }
                    }
                     ?>
                     
                </div>
              </div>
           	</div>
            
             <?php if($savedSetting!='0' && get_option('scsl_valid_domain')=='1')
                    {
                     ?>    
                   
            <div class="btn">
            <input type="submit" id="button" name="save_login"  value="Save"  />
               	  
                </div>
                <?php } ?>
           </form>     
            </div>    
            
            <div class="box1 blue_bg">
            	<h2 class="bov-title">
                	Configuration
                </h2>
                <div class="main-bx1">
                	<p>1. <a class="sky" href="https://www.socleversocial.com/dashboard/" target="_blank">Login</a> to your SoClever account. Or <a class="sky" href="https://www.socleversocial.com/register/?wpd=<?php echo base64_encode(get_site_url()); ?>" target="_blank" >Register</a></span> for free account to generate API Keys.</p>
                    <p>2. Go to Site Settings . Your API key, API secret and site ID will be displayed on this page.</p>
                    <p>3. Configure your API details on API settings tab on your magento Admin Panel.</p>
                    <p>4. To be able to enable Social Login for your site, please create Social Apps on social networks. For more information on how to create Apps for your website please visit our help section on Social Network Set Up.</p>
                    <p>5. Please configure your Social Apps API details on SoClever Authorization page.</p>
                    <p>6. Once you configure Authorization Page, social network buttons will be unlocked to use at Login Settings Page. Please select social networks you want to use for social login and save settings.</p>
                    <p>7. Refresh your admin panel to configure button size, padding gap and buttons style.</p>
                    <p>8. Feel free to contact us for any assistance you may require.</p>
                </div>
                
           	</div>
            </div>
            <div class="sect-right">
        	<div class="orange">
            	<h2 class="sub-tit"><span>Support & FAQ</span></h2>
                <div class="org-sub">
                <p><a href="http://developers.socleversocial.com/how-to-get-api-key-and-secret/" target="_blank">How to get Soclever API key and secret?</a></p>
                <p><a href="http://developers.socleversocial.com/category/social-network-set-up/" target="_blank">Social Network Apps Set Up</a></p>
                <p><a href="https://www.socleversocial.com/about-us/" target="_blank">About Soclever</a></p>                
                <p><a href="http://developers.socleversocial.com/wordpress-social-login/" target="_blank">Wordpress Social Login instructions</a></p>                
                <p><a href="http://developers.socleversocial.com/facebook/" target="_blank">How do I create a Facebook app?</a></p>
                <p><a href="http://developers.socleversocial.com/google/" target="_blank">How do I create a Google+ app?</a></p>
                <p><a href="http://developers.socleversocial.com/twitter/" target="_blank">How do I create a Twitter app?</a></p>
                <p><a href="http://developers.socleversocial.com/linkedin/" target="_blank">How do I create a LinkedIn app?</a></p>                
                </div>
            </div>
            
            <div class="r-video">
            	<p>How to Create Facebook App for Website</p>
            <?php add_thickbox(); ?>
                <a href="<?php echo admin_url('admin-ajax.php')."?action=scsvideo"; ?>?TB_iframe=true&width=600&height=400" class="thickbox">
                  <img src="<?php echo plugins_url('scsl_css/video.png', __FILE__); ?>" alt="How to Create Facebook App for Website"/>       
                </a>
                
            </div>
            
            <div class="reviews">
            	<h2><img src="<?php echo plugins_url('scsl_css/review_heading_icon.png', __FILE__); ?>" alt="" /> We Love Reviews</h2>
                <div class="review_con">
                	<p><img src="<?php echo plugins_url('scsl_css/review_star_img.png', __FILE__); ?>" alt=""/></p>
                    <p>Please click here to leave a review. </p>
                    <p><a href="https://wordpress.org/support/view/plugin-reviews/social-login-facebook-connect-by-soclever" target="_blank">Rate Us</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!--new html end----->
					
<?php  
 
}