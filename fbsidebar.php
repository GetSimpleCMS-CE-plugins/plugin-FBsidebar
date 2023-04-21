<?php

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# add in this plugin's language file
i18n_merge('fbsidebar') || i18n_merge('fbsidebar', 'en_US');

# register plugin
register_plugin(
	$thisfile, //Plugin id
	'FB Sidebar', 	//Plugin name
	'2.1', 		//Plugin version
	'Mateusz Skrzypczak',  //Plugin author
	'http://www.multicolor.stargard.pl', //author website
	i18n_r('fbsidebar/LANG_Description'), //Plugin description
	'plugins', //page type - on which admin tab to display
	'fbsidebar_active'  //main function (administration)
);

# activate filter 
add_action('theme-header', 'fbslider');

# add a link in the admin tab 'theme'
add_action('plugins-sidebar', 'createSideMenu', array($thisfile, i18n_r('fbsidebar/LANG_Settings') . ' <img src=" data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAdgAAAHYBTnsmCAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAD1SURBVDiN3ZKxSgNBFEXPnQ3BIh8gpEgpBpImlqu9XyCksDe9KFbp8gMS7W38ACursL1goRu7NBaKhY0ohOw8mwRi3HEX0nmrx8yZO/c9HqwpFQHx0bgj0TKjWolcMjrfelq+rwRf9s3Fb+mVzLoAEmQ+OwHKGey+jveRuj/jKlvlggYm7Qib1xziZzef2fSjtAH4jcWInHSfXLTf86hfQ9zrpQcGx0Ad2JxTKcaX0Fky3L79M4FBA+isHDYBvPl6YQsyPZvsLi+BwUthCwvFvceB0ClA5GmNLpsPeZwLGZTVPzAIL1Jk1zZzEwmr1qaTdT8K6hsijkmZjUoesgAAAABJRU5ErkJggg==" style="vertical-align:middle;">'));

# functions
function fbslider()
{

	$plugin_id = 'fbsidebar';

	// Set up the folder name and its permissions
	// Note the constant GSDATAOTHERPATH, which points to /path/to/getsimple/data/other/
	$folder        = GSDATAOTHERPATH . '/' . $plugin_id . '/';
	$fbname      = $folder . 'fbname.txt';
	$positiontop = $folder . 'positiontop.txt';
	$leftorright = $folder . 'leftorright.txt';

	if (file_exists($fbname)) {
		$style = '
			<style>
				@media(max-width:960px){
					#fb-left{display:none;}	
					#fb-right{display:none;}	
				}

				#fb-left{
					position:fixed; left:0;top:' . @file_get_contents($positiontop) . ';
					transform:translate(-300px,0); z-index:999;
					-webkit-transition: all 1s ease-out;
					-moz-transition: all 1s ease-out;
					-ms-transition: all 1s ease-out;
					-o-transition: all 1s ease-out;
					transition: all 1s ease-out;
				}

				#fb-left:hover{transform:translate(0,0); }
				#fb-left:after{content:"f"; padding:20px;font-weight:bold;background:#3b5998;float:right;color:#fff;font-size:20px;
				border-bottom-right-radius:5px;    border-top-right-radius:5px}

				//right
				@media(max-width:960px){
					#fb-right{display:none;}	
				}

				#fb-right{
					position:fixed; right:0;top:' . @file_get_contents($positiontop) . ';transform:translate(300px,0); z-index:999;
					-webkit-transition: all 1s ease-out;
					-moz-transition: all 1s ease-out;
					-ms-transition: all 1s ease-out;
					-o-transition: all 1s ease-out;
					transition: all 1s ease-out;
				}

				#fb-right:hover{transform:translate(0,0);}
				#fb-right:before{content:"f"; padding:20px;font-weight:bold;background:#3b5998; float:left;color:#fff;font-size:20px; border-bottom-left-radius:5px;    border-top-left-radius:5px}
			</style>';

		echo $style;

		echo '
			<div id="fb-' . file_get_contents($leftorright) . '" class="">
				<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F' . file_get_contents($fbname) . '&tabs=timeline&width=300&height=300&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=false&appId" width="300" height="300" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
				</iframe>
			</div>';
	}
}

function fbsidebar_active()
{
	// Set up the data

	$plugin_id = 'fbsidebar';

	// Set up the folder name and its permissions
	// Note the constant GSDATAOTHERPATH, which points to /path/to/getsimple/data/other/
	$folder        = GSDATAOTHERPATH . '/' . $plugin_id . '/';
	$fbname      = $folder . 'fbname.txt';
	$positiontop = $folder . 'positiontop.txt';
	$leftorright = $folder . 'leftorright.txt';
	$chmod_mode    = 0755;
	$folder_exists = file_exists($folder) || mkdir($folder, $chmod_mode);

	// Save the file (assuming that the folder indeed exists)
	echo '
	<p><b>' . i18n_r('fbsidebar/LANG_Your_FB') . ' <i style="color:#3B5999;">' . @file_get_contents($fbname) . '</i><p></b>';

	echo '
	<form  action="#" style="margin:0 auto; width:100%; box-sizing:border-box; padding:10px; margin-bottom:20px; border:solid 1px #ddd; background:#fafafa;" method="POST">
		<label>' . i18n_r('fbsidebar/LANG_FB_Name') . '</label>
		<input type="text" placeholder="' . i18n_r('fbsidebar/LANG_Name_From_URL') . '" value="' . @file_get_contents($fbname) . '" style="border:solid 1px #ddd; padding:10px; border-radius:5px; width:100%; box-sizing:border-box; margin-top:10px; margin-bottom:10px;" name="fbname" />
		<label>' . i18n_r('fbsidebar/LANG_Position') . '</label>

		<select name="leftorright" style="border:solid 1px #ddd;border-radius:5px; margin:20px 0;min-width:100%; padding:10px;">
			<option value="left" class="left">' . i18n_r('fbsidebar/LANG_Left') . '</option>
			<option value="right" class="right">' . i18n_r('fbsidebar/LANG_Right') . '</option>
		</select>

		<label>' . i18n_r('fbsidebar/LANG_Position_From_Top') . '</label>
		<input type="text" placeholder="' . i18n_r('fbsidebar/LANG_Default') . '" value="' . @file_get_contents($positiontop) . '" style="border:solid 1px #ddd; padding:10px; border-radius:5px; width:100%; box-sizing:border-box; margin-top:10px; margin-bottom:10px;"  name="fbtop" />

		<input type="submit" name="submit" style="background:#000; color:#fff; padding:10px; margin-top:10px; border:solid 0; border-radius:10px; cursor: pointer;" value="' . i18n_r('fbsidebar/LANG_Save_Settings') . '" />
	</form>
 
	<script>
		if("' . @file_get_contents($leftorright) . '"=="left"){
			document.querySelector(".left").setAttribute("selected","");
		}else if("' . @file_get_contents($leftorright) . '"=="right"){
				document.querySelector(".right").setAttribute("selected","");
		}
	</script>
	';

	echo '
	<p style="background:#fafafa; border:solid 1px #ddd; display:flex; justify-content:space-between; align-items:center; padding:10px;">' . i18n_r('fbsidebar/LANG_PayPal') . ' 
		<a href="https://www.paypal.com/donate/?hosted_button_id=TW6PXVCTM5A72"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"  /></a>
	</p>';

	if (isset($_POST['submit'])) {
		$fbnameinput = $_POST['fbname'];
		$leftorrightinput = $_POST['leftorright'];
		$positiontopinput = $_POST['fbtop'];

		file_put_contents($fbname, $fbnameinput);
		file_put_contents($leftorright, $leftorrightinput);
		file_put_contents($positiontop, $positiontopinput);

		echo '<div style="width:100%; background:green; color:#fff; border-radius:5px; padding:10px; text-align:center;">ok ! ';
		echo 'your fb: ';
		echo file_get_contents($fbname);
		echo '</div>';
		echo "<meta http-equiv='refresh' content='0'>";
	}
}
