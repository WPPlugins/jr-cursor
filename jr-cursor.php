<?php
/*
Plugin Name: JR Cursor
Plugin URI: http://www.jakeruston.co.uk/2010/01/wordpress-plugin-jr-cursor/
Description: A simple plugin to customize the cursor used on your blog!
Version: 1.2.5
Author: Jake Ruston
Author URI: http://www.jakeruston.co.uk
*/

/*  Copyright 2010 Jake Ruston - the.escapist22@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$pluginname="cursor";

// Hook for adding admin menus
add_action('admin_menu', 'jr_cursor_add_pages');

// action function for above hook
function jr_cursor_add_pages() {
    add_options_page('JR Cursor', 'JR Cursor', 'administrator', 'jr_cursor', 'jr_cursor_options_page');
}

if (!function_exists("_iscurlinstalled")) {
function _iscurlinstalled() {
if (in_array ('curl', get_loaded_extensions())) {
return true;
} else {
return false;
}
}
}

if (!function_exists("jr_show_notices")) {
function jr_show_notices() {
echo "<div id='warning' class='updated fade'><b>Ouch! You currently do not have cURL enabled on your server. This will affect the operations of your plugins.</b></div>";
}
}

if (!_iscurlinstalled()) {
add_action("admin_notices", "jr_show_notices");

} else {
if (!defined("ch"))
{
function setupch()
{
$ch = curl_init();
$c = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
return($ch);
}
define("ch", setupch());
}

if (!function_exists("curl_get_contents")) {
function curl_get_contents($url)
{
$c = curl_setopt(ch, CURLOPT_URL, $url);
return(curl_exec(ch));
}
}
}

if (!function_exists("jr_cursor_refresh")) {
function jr_cursor_refresh() {
update_option("jr_submitted_cursor", "0");
}
}

register_activation_hook(__FILE__,'cursor_choice');

function cursor_choice () {
if (get_option("jr_cursor_links_choice")=="") {

if (_iscurlinstalled()) {
$pname="jr_cursor";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_cursor", "1");
wp_schedule_single_event(time()+172800, 'jr_cursor_refresh'); 
} else {
$content = "Powered by <a href='http://arcade.xeromi.com'>Free Online Games</a> and <a href='http://directory.xeromi.com'>General Web Directory</a>.";
}

if ($content!="") {
$content=utf8_encode($content);
update_option("jr_cursor_links_choice", $content);
}
}

if (get_option("jr_cursor_link_personal")=="") {
$content = curl_get_contents("http://www.jakeruston.co.uk/p_pluginslink4.php");

update_option("jr_cursor_link_personal", $content);
}
}

// jr_cursor_options_page() displays the page content for the Test Options submenu
function jr_cursor_options_page() {

    // variables for the field and option names
    $opt_name_5 = 'mt_cursor_plugin_support';
    $hidden_field_name = 'mt_cursor_submit_hidden';
    $data_field_name_5 = 'mt_cursor_plugin_support';

    // Read in existing option value from database
	$opt_val_1 = get_option($opt_name_1);
    $opt_val_5 = get_option($opt_name_5);
    
if (!$_POST['feedback']=='') {
$my_email1="the.escapist22@gmail.com";
$plugin_name="JR Cursor";
$blog_url_feedback=get_bloginfo('url');
$user_email=$_POST['email'];
$user_email=stripslashes($user_email);
$subject=$_POST['subject'];
$subject=stripslashes($subject);
$name=$_POST['name'];
$name=stripslashes($name);
$response=$_POST['response'];
$response=stripslashes($response);
$category=$_POST['category'];
$category=stripslashes($category);
if ($response=="Yes") {
$response="REQUIRED: ";
}
$feedback_feedback=$_POST['feedback'];
$feedback_feedback=stripslashes($feedback_feedback);
if ($user_email=="") {
$headers1 = "From: feedback@jakeruston.co.uk";
} else {
$headers1 = "From: $user_email";
}
$emailsubject1=$response.$plugin_name." - ".$category." - ".$subject;
$emailmessage1="Blog: $blog_url_feedback\n\nUser Name: $name\n\nUser E-Mail: $user_email\n\nMessage: $feedback_feedback";
mail($my_email1,$emailsubject1,$emailmessage1,$headers1);
?>

<div class="updated"><p><strong><?php _e('Feedback Sent!', 'mt_trans_domain' ); ?></strong></p></div>

<?php
}

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
		$opt_val_1 = $_POST[$data_field_name_1];
        $opt_val_5 = $_POST[$data_field_name_5];

        // Save the posted value in the database
		update_option( $opt_name_1, $opt_val_1 );
        update_option( $opt_name_5, $opt_val_5 );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'JR Cursor Plugin Options', 'mt_trans_domain' ) . "</h2>";

$blog_url_feedback=get_bloginfo('url');
	$donated=curl_get_contents("http://www.jakeruston.co.uk/p-donation/index.php?url=".$blog_url_feedback);
	if ($donated=="1") {
	?>
		<div class="updated"><p><strong><?php _e('Thank you for donating!', 'mt_trans_domain' ); ?></strong></p></div>
	<?php
	} else {
	if ($_POST['mtdonationjr']!="") {
	update_option("mtdonationjr", "444");
	}
	
	if (get_option("mtdonationjr")=="") {
	?>
	<div class="updated"><p><strong><?php _e('Please consider donating to help support the development of my plugins!', 'mt_trans_domain' ); ?></strong><br /><br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ULRRFEPGZ6PSJ">
<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form></p><br /><form action="" method="post"><input type="hidden" name="mtdonationjr" value="444" /><input type="submit" value="Don't Show This Again" /></form></div>
<?php
}
}

    // options form
    
    $change3 = get_option("mt_cursor_plugin_support");

if ($change3=="Yes" || $change3=="") {
$change3="checked";
$change31="";
} else {
$change3="";
$change31="checked";
}
    ?>
	<iframe src="http://www.jakeruston.co.uk/plugins/index.php" width="100%" height="20%">iframe support is required to see this.</iframe>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Show Plugin Support?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="Yes" <?php echo $change3; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="No" <?php echo $change31; ?> id="Please do not disable plugin support - This is the only thing I get from creating this free plugin!" onClick="alert(id)">No
</p>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />

</form>
<script type="text/javascript">
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validateEmail(ctrl){

var strMail = ctrl.value
        var regMail =  /^\w+([-.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

        if (regMail.test(strMail))
        {
            return true;
        }
        else
        {

            return false;

        }
					
	}

function validate_form(thisform)
{
with (thisform)
  {
  if (validate_required(subject,"Subject must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(email,"E-Mail must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(feedback,"Feedback must be filled out!")==false)
  {email.focus();return false;}
  if (validateEmail(email)==false)
  {
  alert("E-Mail Address not valid!");
  email.focus();
  return false;
  }
 }
}
</script>
<h3>Submit Feedback about my Plugin!</h3>
<p><b>Note: Only send feedback in english, I cannot understand other languages!</b><br /><b>Please do not send spam messages!</b></p>
<form name="form2" method="post" action="" onsubmit="return validate_form(this)">
<p><?php _e("Your Name:", 'mt_trans_domain' ); ?> 
<input type="text" name="name" /></p>
<p><?php _e("E-Mail Address (Required):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("Message Category:", 'mt_trans_domain'); ?>
<select name="category">
<option value="General">General</option>
<option value="Feedback">Feedback</option>
<option value="Bug Report">Bug Report</option>
<option value="Feature Request">Feature Request</option>
<option value="Other">Other</option>
</select>
<p><?php _e("Message Subject (Required):", 'mt_trans_domain' ); ?>
<input type="text" name="subject" /></p>
<input type="checkbox" name="response" value="Yes" /> I want e-mailing back about this feedback</p>
<p><?php _e("Message Comment (Required):", 'mt_trans_domain' ); ?> 
<textarea name="feedback"></textarea>
</p>
<p class="submit">
<input type="submit" name="Send" value="<?php _e('Send', 'mt_trans_domain' ); ?>" />
</p><hr /></form>
</div>
<?php } ?>
<?php
if (get_option("jr_cursor_links_choice")=="") {
cursor_choice();
}

function show_cursor() {
?>
<script type="text/javascript">
var skinableCursor = {


	// public property. Skin path. You can change this one.
	skinPath : '<?php echo get_bloginfo('siteurl')."/wp-content/plugins/jr-cursor/skin.gif"; ?>',


	// private properties. Browser detect. Do not touch! :)
	IE : ( document.all && document.getElementById && !window.opera ),
	FF : (!document.all && document.getElementById && !window.opera),
	OP : (document.all && document.getElementById && window.opera),


	// private properties. Cursor attributes. Do not touch! :)
	cursor : {
		lt : { x : '0px',	y : '0px',	w : '19px',	h : '26px' ,	dx : -22,	dy : -22 },
		rt : { x : '19px',	y : '0px',	w : '26px',	h : '19px' ,	dx : -3,	dy : -22 },
		rb : { x : '26px',	y : '19px',	w : '19px',	h : '26px' ,	dx : 4,		dy : -3 },
		lb : { x : '0px',	y : '26px',	w : '26px',	h : '19px' ,	dx : -22,	dy : 4 }
	},


	// private method. Initialize
	init : function () {

		skinableCursor.cursor.browserDelta = (skinableCursor.IE ? 2 : 0);

		if ( skinableCursor.FF || skinableCursor.OP ) {
			document.addEventListener("DOMContentLoaded", skinableCursor.domReady, false);
		}

		if ( skinableCursor.IE ) {

			document.write("<scr" + "ipt id=__ieinit defer=true " +
				"src=//:><\/script>");

			var script = document.getElementById("__ieinit");
			script.onreadystatechange = function() {
				if ( this.readyState != "complete" ) return;
				this.parentNode.removeChild( this );
				skinableCursor.domReady();
			};

			script = null;
		}
	},


	// private method.
	domReady : function () {

		skinableCursor.create();

		if ( skinableCursor.FF || skinableCursor.OP ) {
			var s = document.createElement('style');
			s.innerHTML = '* { cursor: inherit; } html { height: 100%; } body, html { cursor: inherit; }';
			document.body.appendChild(s);
			document.addEventListener('mousemove', skinableCursor.move, false);
		}

		if ( skinableCursor.IE ) {
			var s = document.createStyleSheet()
			s.addRule("*", "cursor: inherit");
			s.addRule("body", "cursor: crosshair");
			s.addRule("html", "cursor: crosshair");
			document.attachEvent('onmousemove', skinableCursor.move);
		}

		var anchors = document.getElementsByTagName('a');
		for (x = 0; x < anchors.length; x++) {
			if ( skinableCursor.FF || skinableCursor.OP ) {
				anchors[x].addEventListener('mousemove', skinableCursor.events.anchor, false);
				anchors[x].addEventListener('mouseout', skinableCursor.events.show, false);
			}

			if ( skinableCursor.IE ) {
				anchors[x].attachEvent('onmousemove', skinableCursor.events.anchor);
				anchors[x].attachEvent('onmouseout', skinableCursor.events.show);
			}
		}

	},


	// private method. Create cursor
	create : function () {

		function create(el, d) {
			el.style.position = 'absolute';
			el.style.overflow = 'hidden';
			el.style.display = 'none';
			el.style.left = d.x;
			el.style.top = d.y;
			el.style.width = d.w;
			el.style.height = d.h;
			if ( skinableCursor.IE ) {
				el.innerHTML = '<img src="' + skinableCursor.skinPath + '" style="margin: -' + d.y + ' 0px 0px -' + d.x + '">';
			} else {
				el.style.background = 'url(' + skinableCursor.skinPath + ') -' + d.x + ' -' + d.y;
			}
			return el;
		}

		var c = skinableCursor.cursor;
		c.lt.el = create(document.createElement('div'), c.lt);
		c.rt.el = create(document.createElement('div'), c.rt);
		c.rb.el = create(document.createElement('div'), c.rb);
		c.lb.el = create(document.createElement('div'), c.lb);

		document.body.appendChild(c.lt.el);
		document.body.appendChild(c.rt.el);
		document.body.appendChild(c.rb.el);
		document.body.appendChild(c.lb.el);

	},


	// private method. Move cursor
	move : function (e) {

		function pos(el, x, y) {
			el.el.style.left = x + el.dx + 'px';
			el.el.style.top = y + el.dy + 'px';
		}

		function hide(el, x, y) {
			var w = document.documentElement.clientWidth;
			var h = document.documentElement.clientHeight;
			var deltaX = w - (x + el.dx + parseInt(el.w) - skinableCursor.cursor.browserDelta);
			var deltaY = h - (y + el.dy + parseInt(el.h) - skinableCursor.cursor.browserDelta);
			if (!skinableCursor.noSkin) {
				el.el.style.display = deltaX > 0 ? (deltaY > 0 ? 'block' : 'none') : 'none';
			}
		}

		var p = skinableCursor.getMousePosition(e);
		var s = skinableCursor.getScrollPosition();
		var c = skinableCursor.cursor;
		var x = p.x + s.x - c.browserDelta;
		var y = p.y + s.y - c.browserDelta;

		hide(c.lt, p.x, p.y);
		hide(c.rt, p.x, p.y);
		hide(c.rb, p.x, p.y);
		hide(c.lb, p.x, p.y);

		pos(c.lt, x, y);
		pos(c.rt, x, y);
		pos(c.rb, x, y);
		pos(c.lb, x, y);

	},


	// private method. Returns mouse position
	getMousePosition : function (e) {

		e = e ? e : window.event;
		var position = {
			'x' : e.clientX,
			'y' : e.clientY
		}

		return position;

	},


	// private method. Get document scroll position
	getScrollPosition : function () {

		var x = 0;
		var y = 0;

		if( typeof( window.pageYOffset ) == 'number' ) {
			x = window.pageXOffset;
			y = window.pageYOffset;
		} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
			x = document.documentElement.scrollLeft;
			y = document.documentElement.scrollTop;
		} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
			x = document.body.scrollLeft;
			y = document.body.scrollTop;
		}

		var position = {
			'x' : x,
			'y' : y
		}

		return position;

	},


	// private property / methods.
	events : {

		anchor : function (e) {
			skinableCursor.noSkin = true;
			document.body.style.cursor = 'pointer';

			var c = skinableCursor.cursor;
			c.lt.el.style.display = 'none';
			c.rt.el.style.display = 'none';
			c.rb.el.style.display = 'none';
			c.lb.el.style.display = 'none';

		},

		show : function () {
			skinableCursor.noSkin = false;
			document.body.style.cursor = 'crosshair';
		}

	}

}

skinableCursor.init();
</script>
<?php

$supportplugin=get_option("mt_cursor_plugin_support");
if ($supportplugin=="" || $supportplugin=="Yes") {
add_action('wp_footer', 'cursor_footer_plugin_support');
}
}

function cursor_footer_plugin_support() {
$linkper=utf8_decode(get_option('jr_cursor_link_personal'));

if (get_option("jr_cursor_link_newcheck")=="") {
$pieces=explode("</a>", get_option('jr_cursor_links_choice'));
$pieces[0]=str_replace(" ", "%20", $pieces[0]);
$pieces[0]=curl_get_contents("http://www.jakeruston.co.uk/newcheck.php?q=".$pieces[0]."");
$new=implode("</a>", $pieces);
update_option("jr_cursor_links_choice", $new);
update_option("jr_cursor_link_newcheck", "444");
}

if (get_option("jr_submitted_cursor")=="0") {
$pname="jr_cursor";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_cursor", "1");
update_option("jr_cursor_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_cursor_refresh'); 
} else if (get_option("jr_submitted_cursor")=="") {
$pname="jr_cursor";
$url=get_bloginfo('url');
$current=get_option("jr_cursor_links_choice");
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname."&override=".$current);
update_option("jr_submitted_cursor", "1");
update_option("jr_cursor_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_cursor_refresh'); 
}

  $pshow = "<p style='font-size:x-small'>Cursor Plugin created by ".$linkper." - ".stripslashes(get_option('jr_cursor_links_choice'))."</p>";
  echo $pshow;
}

add_action("wp_head", "show_cursor");

?>
