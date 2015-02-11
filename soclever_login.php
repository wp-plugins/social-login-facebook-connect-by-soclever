<?php
/*
Plugin Name: Social Login Facebook connect - other Social networks By SoClever
Plugin URI: https://wordpress.org/plugins/social-login-facebook-connect-by-soclever/
Description: This module enables Social Login (Facebook and more), User Profile Data & Social Analytics on your site
Version: 1.0.0
Author: Socleve Team
Author URI: https://www.socleversocial.com/
 */

if(!defined('ABSPATH')) exit;

header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");

register_activation_hook(__FILE__, 'scsl_activation');
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
        
  $select_user="select user_login from ".$wpdb->prefix."users where ID='".mysql_real_escape_string($id_use)."'";

$row_user=$wpdb->get_results($select_user);
$length = 8;
$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$string = "";    
for ($p = 0; $p<$length; $p++) {
				$string .= $characters[mt_rand(0, strlen($characters))];
}
$new_pass=$string;
$user_pass = wp_hash_password($new_pass);
$wpdb->update( $wpdb->prefix.'users', array( 'user_pass' =>$user_pass),array('ID'=>$id_use));
$creds['user_login']=$row_user[0]->user_login;
$creds['user_password']=$new_pass;
$creds['remember'] = true;

$userlogin=wp_signon($creds,true);

$notify_cs=file_get_contents("https://www.socleversocial.com/dashboard/track_register_new.php?siteid=".get_option('scsl_site_id')."&action=notifycs&is_new=".$is_new."&is_from=".$is_from."&siteUid=".$id_use."&member_id=".$member_id);
if($notify_cs)
{
    $red_url=($_COOKIE['lch']=='l')?get_site_url():$_COOKIE['lch'];
    header("location:".$red_url."");
}    
         
  
}
function scsl_menu_settings(){
	include('soclever_login.php');	
	}

add_action( 'admin_menu', 'cs_login_menu' );

function cs_login_menu(){
    add_menu_page( 'Login Buttons By SoClever', 'Login Buttons By SoClever', 'manage_options', 'soclever_login', 'scslogin_html_page',plugins_url( 'scsl_css/sc_img.png', __FILE__ ), 82); 
}


function socleverlogin_plugin_parse_request($wp) {
    global $wpdb;
    
    if(isset($_GET['lch']) && $_GET['lch']!='')
{
    setcookie('lch',$_GET['lch'],time()+100,'/');

} 

    if (array_key_exists('socleverlogin-plugin', $wp->query_vars) 
            && $wp->query_vars['socleverlogin-plugin'] == 'social-login') {


 
require 'openid.php';
 
try
{
   
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID($_SERVER['HTTP_HOST']);
     
    //Not already logged in
    if(!$openid->mode)
    {
         
        //do the login
        if(isset($_GET['login']))
        {
            //The google openid url
            $openid->identity = 'https://me.yahoo.com';
             
            //Get additional google account information about the user , name , email , country
            $openid->required = array('contact/email','person/guid','dob','birthDate','namePerson' , 'person/gender' , 'pref/language' , 'media/image/default','birthDate/birthday');
             
            //start discovery
            
            
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
        $resPonse=file_get_contents($request_url);
        if($resPonse)
        {
           general_soclever_login($resPonse,'5'); 
            
        }    
            
            
            exit("<img src='https://www.socleversocial.com/dashboard/images/pw.gif' alt='Please wait!' title='Please wait'>"); 
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

add_action('wp_ajax_scsfblogin', 'scsl_login_fb' );
add_action('wp_ajax_nopriv_scsfblogin', 'scsl_login_fb' );
function scsl_login_fb()
{
    
    global $wpdb;
  if(isset($_GET['lch']) && $_GET['lch']!='')
{
    setcookie('lch',$_GET['lch'],time()+100,'/');

}  
   $get_fb=file_get_contents("https://www.socleversocial.com/dashboard/get_fb_data.php?siteid=".get_option('scsl_site_id')."");
   
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
            . $app_id . "&redirect_uri=" . urlencode($my_url)."&scope=email,user_birthday,user_relationships,user_location,user_hometown,user_friends,user_likes";

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
        $resPonse=file_get_contents($request_url);
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
        
  $select_user="select user_login from ".$wpdb->prefix."users where ID='".mysql_real_escape_string($id_use)."'";

$row_user=$wpdb->get_results($select_user);
$length = 8;
$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$string = "";    
for ($p = 0; $p<$length; $p++) {
				$string .= $characters[mt_rand(0, strlen($characters))];
}
$new_pass=$string;
$user_pass = wp_hash_password($new_pass);
$wpdb->update( $wpdb->prefix.'users', array( 'user_pass' =>$user_pass),array('ID'=>$id_use));
$creds['user_login']=$row_user[0]->user_login;
$creds['user_password']=$new_pass;
$creds['remember'] = true;



	

	



$userlogin=wp_signon($creds,true);



$notify_cs=file_get_contents("https://www.socleversocial.com/dashboard/track_register_new.php?siteid=".get_option('scsl_site_id')."&action=notifycs&is_new=".$is_new."&is_from=".$is_from."&siteUid=".$id_use."&member_id=".$member_id);
if($notify_cs)
{
    $red_url=($_COOKIE['lch']=='l')?get_site_url():$_COOKIE['lch'];
    if($is_from=='7')
    {
        
        
    
    header("location:".$red_url."");
    


        //header("location:".$redirect_url."");
    }
    else
    {
        echo $red_url;
    }
  
 } 
wp_die(); 
}


if(isset($_POST['submit_login']) && $_POST['submit_login']=='Submit' )
{
   
    
    $res_ponse_str=file_get_contents('https://www.socleversocial.com/dashboard/wp_activate.php?site_id='.mysql_real_escape_string($_POST['client_id']).'&api_key='.mysql_real_escape_string($_POST['api_key']).'&api_secret='.mysql_real_escape_string($_POST['api_secret']).'');
    $res_ponse=explode("~~",$res_ponse_str);
    if(mysql_real_escape_string($_POST['api_key'])==$res_ponse[0] && mysql_real_escape_string($_POST['api_secret'])==$res_ponse[1] && $res_ponse[0]!='0')
    {
        echo "<h2>Thanks for authenticate with SoCleverSocial.com.....</h2>";
        
        /*echo"<br/><h3>Preview</h3><br/>";
        echo htmlspecialchars_decode($res_ponse[2]);*/
        update_option("scsl_valid_domain",'1');
        update_option("scsl_site_id",mysql_real_escape_string($_POST['client_id']));
        update_option("scsl_api_key",mysql_real_escape_string($_POST['api_key']));
        update_option("scsl_api_secret",mysql_real_escape_string($_POST['api_secret']));
        update_option("scsl_domain",mysql_real_escape_string($_POST['scsl_domain']));
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
    if(!is_user_logged_in() && get_option('comment_registration')=='1' && (is_single() || is_page()) )
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
    if(!is_user_logged_in())
    {
    echo '<script type="text/javascript" src="https://www.socleversocial.com/dashboard/client_share_js/client_'.get_option('scsl_site_id').'_login.js"></script>
<script type="text/javascript">
                                        csloginjs.init([\''.get_option('scsl_api_key').'\',\''.get_option('scsl_site_id').'\',\''.get_option('scsl_api_secret').'\',\''.get_option('scsl_domain').'\']);
                                            csloginjs.validateCsApi();
                                            
                                        </script>
    ';
   } 
}



/*function wporg_more_comments( $post_id ) {
	echo '<p class="comment-form-more-comments"><label for="more-comments">' . __( 'More Comments', 'your-theme-text-domain' ) . '</label> <textarea id="more-comments" name="more-comments" cols="45" rows="8" aria-required="true"></textarea></p>';
}

add_action( 'comment_form', 'wporg_more_comments' );
*/
//add_action('comment_form','scsl_comment_login');

add_filter( 'the_content', 'scsl_comment_login_post' ); 
 
 function scsl_comment_login_post( $content ) {
   
   
    if((is_single() || is_page() ) && !is_user_logged_in() && get_option('comment_registration')=='1')
    { 
    $display_content='<div style="clear:both;margin:10px 0px 10px 0px;">';
    $display_content .='<p>Login with your social profile</p>';
    $display_content .='<script type="text/javascript" src="https://www.socleversocial.com/dashboard/client_share_js/csloginbuttons_'.get_option('scsl_site_id').'.js"></script>'.PHP_EOL;
    //$js_buttons=file_get_contents("https://www.socleversocial.com/dashboard/wp_login_setting.php?site_id=".get_option('scsl_site_id')."");
    $js_buttons=scsl_get_preview('0');   
    $display_content .=$js_buttons;
    $display_content .='<br/><span style="clear:both;">Powered by <a href="https://www.socleversocial.com/" target="_blank">SoCleverSocial.com</a></span>';
    $display_content .='</div>';
    
    
        $content .=$display_content;
		
	}

    return $content;
}

/*add_action( 'comment_form','scsl_comment_login');
function scsl_comment_login($post_id) {
     
    $display_content='<div style="clear:both;margin:10px 0px 10px 0px;">';
    $display_content='<h3 style="line-height:25px;">Login with your social profile to post comment</h3>';
    $display_content .='<script type="text/javascript" src="https://www.socleversocial.com/dashboard/client_share_js/csloginbuttons_'.get_option('scsl_site_id').'.js"></script>'.PHP_EOL;
    //$js_buttons=file_get_contents("https://www.socleversocial.com/dashboard/wp_login_setting.php?site_id=".get_option('scsl_site_id')."");
    $js_buttons=scsl_get_preview('0');   
    $display_content .=$js_buttons;
    $display_content .='<br/><span style="clear:both;">Powered by <a href="https://www.socleversocial.com/" target="_blank">SoCleverSocial.com</span>';
    $display_content .='<div/>';
    
    
    echo $display_content;
        
    
}*/


add_filter( 'login_form', 'scsl_login_buttons_show' );
function scsl_login_buttons_show()
 {
    if(!is_user_logged_in())
    {
    $display_content='<div style="clear:both;margin:10px 0px 10px 0px;">';
    $display_content='<h3 style="line-height:25px;">Login with your social profile</h3>';
    $display_content .='<script type="text/javascript" src="https://www.socleversocial.com/dashboard/client_share_js/csloginbuttons_'.get_option('scsl_site_id').'.js"></script>'.PHP_EOL;
    //$js_buttons=file_get_contents("https://www.socleversocial.com/dashboard/wp_login_setting.php?site_id=".get_option('scsl_site_id')."");
    $js_buttons=scsl_get_preview('0');   
    $display_content .=$js_buttons;
    $display_content .='<br/><span style="clear:both;">Powered by <a href="https://www.socleversocial.com/" target="_blank">SoCleverSocial.com</span>';
    $display_content .='<div/>';
    
    echo $display_content;
    }
}
}




if(isset($_POST['save_login']) && $_POST['save_login']=='Save' )
{
update_option('scsl_button_style',mysql_real_escape_string($_POST['scsl_button_style']));
update_option('scsl_button_size',mysql_real_escape_string($_POST['scsl_button_size']));
update_option('scsl_lending_page',mysql_real_escape_string($_POST['scsl_lending_page']));
update_option('scsl_network',mysql_real_escape_string(implode(",",$_POST['scsl_network'])));

    
}    

function scsl_get_preview($is_preview='0')
{
    $network=explode(",",get_option('scsl_network'));
    $button_size=get_option('scsl_button_size');
    $btn_style=get_option('scsl_button_style');
    
    if($btn_style=="ic") {
        $btn_width=$button_size;
    }
    else if($btn_style=="fc" || $btn_style=="fg")
    {
        if($button_size=="30") {$btn_width="78"; }
        if($button_size=="40") {$btn_width="104"; }
        if($button_size=="50") {$btn_width="130"; }
        if($button_size=="60") {$btn_width="156"; }
        if($button_size=="65") {$btn_width="169"; }
    }

$img='social_login_'.$btn_style.'_'.$button_size.'.png';
$previewDiv='';
    $fb_div="";
if(in_array('2',$network))
{
    $bg_position=$btn_width;
    $fb_div .='<script type="text/javascript">';
    $imgdiv='<div style="display:inline-block;width: '.$btn_width.'px; height: '.$button_size.'px; background-image: url(https://www.socleversocial.com/dashboard/img/social_icon/'.$img.'); background-position: -'.$bg_position.'px 0px;"></div>';
    $previewDiv .=$imgdiv;
    $fb_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'\',\'login\']);'.PHP_EOL;
    $fb_div .='csbutton.putCsbutton();         
              </script>';

    
}
$gp_div="";
if(in_array('4',$network))
{
    $row_site['gplus_app_id']=file_get_contents("https://www.socleversocial.com/dashboard/wp_login_setting.php?site_id=".get_option('scsl_site_id')."&action=getGpapi");
    $bg_position=((3)*$btn_width);
    $gp_div .='<script type="text/javascript">';
    $imgdiv='<div style="display:inline-block;width: '.$btn_width.'px; height: '.$button_size.'px; background-image: url(https://www.socleversocial.com/dashboard/img/social_icon/'.$img.'); background-position: -'.$bg_position.'px 0px;"></div>';
    $previewDiv .=$imgdiv;
    $gp_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'px\',\'login\',\''.$row_site['gplus_app_id'].'\']);'.PHP_EOL;
    $gp_div .='csbutton.putCsbutton();         
              </script>';
  
}
$li_div="";
if(in_array('7',$network))
{
    $bg_position=((6)*$btn_width);
    $li_div .='<script type="text/javascript">';
    $imgdiv='<div style="display:inline-block;width: '.$btn_width.'px; height: '.$button_size.'px; background-image: url(https://www.socleversocial.com/dashboard/img/social_icon/'.$img.'); background-position: -'.$bg_position.'px 0px;"></div>';
    $li_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'px\',\'login\',\'li\']);'.PHP_EOL;
    $li_div .='csbutton.putCsbutton();         
              </script>';
  
}
$tw_div="";
if(in_array('13',$network))
{
    $bg_position=((12)*$btn_width);
    $tw_div .='<script type="text/javascript">';
    $imgdiv='<div style="display:inline-block;width: '.$btn_width.'px; height: '.$button_size.'px; background-image: url(https://www.socleversocial.com/dashboard/img/social_icon/'.$img.'); background-position: -'.$bg_position.'px 0px;"></div>';
    $previewDiv .=$imgdiv;
    $tw_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'px\',\'login\',\'twitter\']);'.PHP_EOL;
    $tw_div .='csbutton.putCsbutton();         
              </script>';
  
}
$yh_div="";
if(in_array('15',$network))
{
    $bg_position=((14)*$btn_width);
    $yh_div .='<script type="text/javascript">';
    $imgdiv='<div style="display:inline-block;width: '.$btn_width.'px; height: '.$button_size.'px; background-image: url(https://www.socleversocial.com/dashboard/img/social_icon/'.$img.'); background-position: -'.$bg_position.'px 0px;"></div>';
    $previewDiv .=$imgdiv;
    $yh_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'px\',\'login\',\'yahoo\']);'.PHP_EOL;
    $yh_div .='csbutton.putCsbutton();         
              </script>';
  
}
$pp_div="";
if(in_array('16',$network))
{
    $bg_position=((15)*$btn_width); //change when image is added 
    $pp_div .='<script type="text/javascript">';
    $imgdiv='<div style="display:inline-block;width: '.$btn_width.'px; height: '.$button_size.'px; background-image: url(https://www.socleversocial.com/dashboard/img/social_icon/'.$img.'); background-position: -'.$bg_position.'px 0px;"></div>';
    $previewDiv .=$imgdiv;
    $pp_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'px\',\'login\',\'paypal\']);'.PHP_EOL;
    $pp_div .='csbutton.putCsbutton();         
              </script>';
  
}
$ig_div="";
if(in_array('5',$network))
{
    $bg_position=((4)*$btn_width);
    $ig_div .='<script type="text/javascript">';
    $imgdiv='<div style="display:inline-block;width: '.$btn_width.'px; height: '.$button_size.'px; background-image: url(https://www.socleversocial.com/dashboard/img/social_icon/'.$img.'); background-position: -'.$bg_position.'px 0px;"></div>';
    $previewDiv .=$imgdiv;
    $ig_div .='csbutton.init([\''.$imgdiv.'\',\''.$btn_width.'px\' ,\''.$button_size.'px\',\'login\',\'instagram\']);'.PHP_EOL;
    $ig_div .='csbutton.putCsbutton();         
              </script>';
  
}
if($is_preview=='1')
{
    return $previewDiv;
}
else
{
    return PHP_EOL.$fb_div.PHP_EOL.$gp_div.PHP_EOL.$li_div.PHP_EOL.$tw_div.PHP_EOL.$yh_div.PHP_EOL.$ig_div.PHP_EOL.$pp_div;
}
}

function scslogin_html_page()
{
 wp_register_style( 'scsl-style', plugins_url('scsl_css/scsl-style.css?ver='.time().'', __FILE__) );
 wp_enqueue_style( 'scsl-style' );
 ?>
 <header class="scsl-clearfix">
    <h1>
	<a href="https://www.socleversocial.com/" target="_blank">
        <img src="https://www.socleversocial.com/dashboard/img/logo.png" alt="SoClever Social" />
	</a>
    </h1>

   
</header>

 <?php
if(get_option('scsl_valid_domain')=='1')
{
    
        
        

    ?>
  
<h2>SoClever Social Login Setting</h2>
<?php wp_nonce_field('update-options'); ?>
                        
<form class="login-form mt-lg" action="" method="post" name="authosharefrm" enctype="multipart/form-data">
                            <table class="table" style="margin:20px;font-size:1em;">
                                
                                
                    
                    <?php //echo file_get_contents('https://www.socleversocial.com/dashboard/wp_preview.php?site_id='.mysql_real_escape_string(get_option('scss_site_id')).'&api_key='.mysql_real_escape_string(get_option('scss_api_key')).'&api_secret='.mysql_real_escape_string(get_option('scss_api_secret')).''); ?>
 
                    <tr>
                    <th align="left">Button Style</th>
                    </tr>
                    <tr>                    
                    <td>
                    <select name="scsl_button_style" id="scsl_button_style">
                    <option value="ic" <?php if(get_option('scsl_button_style')=='ic') { echo "selected='selected'"; } ?>>Icon</option>
                    <option value="fc"<?php if(get_option('scsl_button_style')=='fc') { echo "selected='selected'"; } ?>>Full Coloured Logos</option>
                    <option value="fg" <?php if(get_option('scsl_button_style')=='fg') { echo "selected='selected'"; } ?>>Full Grey Logos</option>
                    </select>
                    </td>
                    </tr>
                    <tr>
                    <th align="left">Button Size</th>
                    </tr>
                    <tr>                    
                    <td>
                    <select name="scsl_button_size" id="scsl_button_size">
                    <option value="30" <?php if(get_option('scsl_button_size')=='30') { echo "selected='selected'"; } ?>>30px</option>
                    <option value="40" <?php if(get_option('scsl_button_size')=='40') { echo "selected='selected'"; } ?>>40px</option>
                    <option value="50" <?php if(get_option('scsl_button_size')=='50') { echo "selected='selected'"; } ?>>50px</option>
                    <option value="60" <?php if(get_option('scsl_button_size')=='60') { echo "selected='selected'"; } ?>>60px</option>
                    <option value="65" <?php if(get_option('scsl_button_size')=='65') { echo "selected='selected'"; } ?>>65px</option>                    
                    </select>
                    </td>
                    </tr>
                    <tr>
                    <th align="left">Providers</th>
                    </tr>
                    <tr>                    
                    <td>
                    <?php  $savedSetting=file_get_contents("https://www.socleversocial.com/dashboard/wp_login_setting.php?site_id=".get_option('scsl_site_id')."&action=preview&button_style=".get_option('scsl_button_style')."&button_size=".get_option('scsl_button_size')."");
                    if($savedSetting=='0')
                    {
                        echo"<font color='#ff0000'>No provider selected on SoCleverSocial Dashboard</font>";
                    }
                    else
                    {
                        echo $savedSetting;
                    }
                    
                     ?>      
                    </td>
                    </tr>
                    <tr>
                    <th align="left">After Login, Lending Page</th>
                    </tr>
                    <tr>                    
                    <td>
                    <select name="scsl_lending_page" id="scsl_lending_page">
                    <option value="1" <?php if(get_option('scsl_lending_page')=='1') { echo "selected='selected'"; } ?>>Edit Profile</option>
                    <option value="2" <?php if(get_option('scsl_lending_page')=='2') { echo "selected='selected'"; } ?>>Home</option>
                    </select>
                    </td>
                    </tr>
                    <?php if($savedSetting!='0')
                    {
                     ?>    
                    <tr>
                                    <td>
                                        <div class="clearfix">
                                            <div class="btn-toolbar pull-right">                                            
                                                <input type="submit" name="save_login" class="scslbutton" value="Save" />
                                            </div>
                                        </div>
                                    </td>
                      </tr>
                      <?php } ?>
                            </table>
                        
                        

</form>


  
    <?php
}
else
{    
?>
<div>
<table id="cssteps">
        <thead>
            <tr valign="top">
                <th>
                <h1>Step 1 - Create a SocleverSocial.com account</h1>
                <p>To get started, register your Soclever Social account and find your API key in the site settings. If you already have an account please log in. </p>
                </th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
        <tfoot>
            <tr valign="top">
                <td>
                    <a href="https://www.socleversocial.com/register/?wpd=<?php echo base64_encode(get_site_url()); ?>" target="_blank" class="scslbutton">Register</a> 
                    <a href="https://www.socleversocial.com/dashboard/" target="_blank" class="scslbutton">Login</a></p>
                </td>
            </tr>
            <tr valign="top" align="left">
                <th>
                <h1>Step 2 - Enter your API Settings</h1>                
                </th>
            </tr>
  <form method="post" action="">
  <?php wp_nonce_field('update-options'); ?>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
<tr valign="middle">
<th width="20%" scope="row">Client ID</th>
<td>
<input type="text" name="client_id" id="client_id" width="10" />
 
</td>
</tr>
<tr valign="middle">
<th width="20%" scope="row">API Key</th>
<td>
<input type="text" name="api_key" id="api_key"  width="40"/>
 
</td>
</tr>
<tr valign="middle">
<th width="20%" scope="row">API Secret</th>
<td>
<input type="text" name="api_secret" id="api_secret"  width="40"/>
 
</td>
</tr>
<tr valign="middle">
<th width="20%" scope="row">Valid Domain</th>
<td>
<input type="text" name="scsl_domain" id="scsl_domain"  width="100"/> 
 
</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td>
<input type="submit" name="submit_login" id="submit_login" class="scslbutton"  value="Submit"/>
 
</td>
</tr>
</table>
  </form>
  </table>  
  
<?php
}
?>
<div style="background: none repeat scroll 0 0 #fff;border: 1px solid #eee;margin-bottom: 30px;width:95%;">
					<h4 style=" border-bottom: 1px solid #eee;margin-bottom: 10px;padding: 10px 0;text-align: center;">Configuration</h4>
					<div style="padding: 10px 10px 30px 0px;">
						1. <a target="_blank" href="https://www.socleversocial.com/dashboard/login.php">Login</a> to your SoClever account. Or <a target="_blank" href="https://www.socleversocial.com/pricing/">Register</a> for free account to generate API Keys.<br>
			           2. Go to <a target="_blank" href="https://www.socleversocial.com/dashboard/billing_profile_setting.php">Site Settings</a> . Your API key, API secret and site ID will be displayed on this page.<br>
			           3. Configure your API details on API settings tab on your magento Admin Panel.<br>
			           4. To be able to enable Social Login for your site, please create Social Apps on social networks. For more information on how to create Apps for your website please visit our help section on <a target="_blank" href="http://developers.socleversocial.com/category/social-network-set-up/">Social Network Set Up</a>.<br>
			           5. Please configure your Social Apps API details on SoClever <a target="_blank" href="https://www.socleversocial.com/dashboard/authorization_setting.php">Authorization page</a>.<br>
			           6. Once you configure Authorization Page, social network buttons will be unlocked to use at <a target="_blank" href="https://www.socleversocial.com/dashboard/social_login_setting.php">Login Settings Page</a>. Please select social networks you want to use for social login and save settings.<br>
			           7. Refresh your admin panel to configure button size, padding gap and buttons style.<br>
			           8. Feel free to <a target="_blank" href="https://www.socleversocial.com/contact-us/">contact us</a> for any assistance you may require.
					</div>
				</div>
<div style="background: none repeat scroll 0 0 #fff;border: 1px solid #eee;margin-bottom: 30px;width:95%;">
					<h4 style=" border-bottom: 1px solid #eee;margin-bottom: 10px;padding: 10px 0;text-align: center;">Help</h4>
					<div style="padding: 10px 10px 30px 0px;">
						<a style="display:block;margin-left:10px;" href="http://developers.socleversocial.com/how-to-get-api-key-and-secret/" target="_blank">
							How to get Soclever API key and secret?</a>
						<a style="display:block;margin-left:10px;" href="http://developers.socleversocial.com/" target="_blank">
							Social Network Apps Set Up</a>
						<a style="display:block;margin-left:10px;" href="https://www.socleversocial.com/about-us/" target="_blank">
							About Soclever</a>	
							<b> How to create Facebook App for your website</b><br />
<iframe src="//player.vimeo.com/video/118392066?title=0&byline=0&portrait=0" width="800" height="481" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
					</div>
				</div>
					
<?php    
}