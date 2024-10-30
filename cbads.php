<?php
/* 
  Plugin Name: Affiliate Ads for cbAds.com
  Plugin URI: http://cbads.com/
  Description: This plugin creates a graphic banner in post and in widget areas to display cbAds.com ads on your Wordpress blog. 
  Version: 2.0
  Author: cbAds
  Author URI: http://cbads.com/

 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$cbads_version="2.0"; 

if (!class_exists("cbads")) {
class cbads {
	var $opts; 
	function cbads() { $this->getOpts(); } 
	function getOpts() {
		if (isset($this->opts) AND !empty($this->opts)) {return;} 
		$this->opts=get_option("ClickBankWEC3"); 
		if (!empty($this->opts)) {return;} 
		$this->opts=Array ('title' => 'Vacation Rentals:', 'name' => '', 'afID' => '', 'border' => '','homepage'=>'1','onlypost'=>'0','runplugin'=>'1', 'bordstyle' => '1', 'width' => '100%', 'height' => '1','pos' => 'Top','change' => '5','margin' => '20','featured' => '','message' => 'Book your vacation rentals here and save 8% with a coupon: PROMO! These luxurious rentals, nestled in the finest resort areas, offer complete furnishing and essential amenities to enhance your stay. Bid farewell to endless emails and phone calls - secure your dream vacation rental now using the convenient search box above.');
	}

	function admin_menu() {
		global $cbads_version;
		if (isset($_POST["cbads_submit"]) && isset($_POST['nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'])), 'cbads_update' )) {
			$sanitized=Array();
			$sanitized['title'] = 		(isset($_POST['title'])?sanitize_text_field(wp_unslash($_POST['title'])):'Vacation Rentals:');
			$sanitized['name'] = 		(isset($_POST['name'])?sanitize_text_field(wp_unslash($_POST['name'])):'');
			$sanitized['afID'] = 		(isset($_POST['afID'])?sanitize_text_field(wp_unslash($_POST['afID'])):'');
			$sanitized['border'] = 		(isset($_POST['border'])?sanitize_text_field(wp_unslash($_POST['border'])):'0');
			$sanitized['homepage'] = 	(isset($_POST['homepage'])?sanitize_text_field(wp_unslash($_POST['homepage'])):'0');
			$sanitized['onlypost'] = 	(isset($_POST['onlypost'])?sanitize_text_field(wp_unslash($_POST['onlypost'])):'0');
			$sanitized['runplugin'] = 	(isset($_POST['runplugin'])?sanitize_text_field(wp_unslash($_POST['runplugin'])):'0');
			$sanitized['bordstyle'] =	(isset($_POST['bordstyle'])?sanitize_text_field(wp_unslash($_POST['bordstyle'])):'1');
			$sanitized['width'] = 		(isset($_POST['width'])?sanitize_text_field(wp_unslash($_POST['width'])):'100%');
			$sanitized['height'] = 		(isset($_POST['height'])?sanitize_text_field(wp_unslash($_POST['height'])):'1');
			$sanitized['pos'] = 		(isset($_POST['pos'])?sanitize_text_field(wp_unslash($_POST['pos'])):'Top');
			$sanitized['change'] = 		(isset($_POST['change'])?sanitize_text_field(wp_unslash($_POST['change'])):'5');
			$sanitized['margin'] = 		(isset($_POST['margin'])?sanitize_text_field(wp_unslash($_POST['margin'])):'20');
			$sanitized['message'] = 	(isset($_POST['message'])?sanitize_textarea_field(wp_unslash($_POST['message'])):'');
			$sanitized['featured'] = 	(isset($_POST['featured'])?sanitize_text_field(wp_unslash($_POST['featured'])):'');
			$this->opts=$sanitized; 
			update_option('ClickBankWEC3',$this->opts); 
			echo '<div id="message" class="updated fade"><p><strong>Options Updated!</strong></p></div>';
		}
		$this->getOpts();
		
		if($this->opts['width']!="100%" && (int)$this->opts['width']>10){$this->opts['width']=(int)($this->opts['width']/200);}//corr for old users
		if($this->opts['height']!="100%" && (int)$this->opts['height']>10){$this->opts['height']=(int)($this->opts['height']/180);}//corr for old users
		if(!isset($this->opts ['itw'])){$this->opts ['itw']=200;}//corr for old users
		if(!isset($this->opts ['margin'])){$this->opts ['margin']=20;}//corr for old users
		if(!isset($this->opts ['change'])){$this->opts ['change']=5;}//corr for old users
		if(!isset($this->opts ['afID'])){$this->opts ['afID']=0;}//corr for old users
		
		$hashes='coupon=PROMO&a_id='.$this->opts['afID'].(isset($this->opts['name'])?'&clickbank='.$this->opts['name']:'');
	?>

	<div class="wrap">
		<h2>Affiliate Ads for cbAds.com (V <?php echo esc_html($cbads_version); ?>)</h2>
		<p>For further Information visit the <a target=_blank href="http://cbads.com/">Plugin Site</a>.<br/>Notes:
		<br/>- You will receive <b>7% of the base rent price</b> if a traveler has books a vacation by clicking on your affiliate form/link. The average base rent of a booking on our platform is about <b>$3,000</b>. You will receive 7% of that rent.
		<br/>- To place an ad block in the <b>widget area (sidebar)</b>, go to the '<a href=widgets.php>Appearance -> Widgets</a>' SubPanel, add the "Affiliate Ads for cbAds.com" to your sidebar and configure it.
		<br/>- The ad block can be inserted using the shortcode API: [cbads]. Place [cbads] in any post, page, or widget to display the ad block.
		<br/>- If you see only its name instead of the plugin - your theme, removes the tags. Choose a different theme or use widgets.
		<br/>- Up to 3 ad blocks can be placed on each page. If there are more of them, an empty window will be displayed.
		</p>
		<form name="mainform" method="post" ><input type="hidden" id="nonce" name="nonce" value="<?php echo esc_attr(wp_create_nonce('cbads_update' )); ?>" style="width:200px;" />
		<p><label>Show/hide plugin: <input type="checkbox" <?php if(isset($this->opts['runplugin']) && $this->opts['runplugin']=="1") {echo 'checked';}?> name="runplugin" value="1" style="border:0px;" /></label></p>
		<p><label>Show Ads on the home page: <input type="checkbox" <?php if(isset($this->opts['homepage']) && $this->opts['homepage']=="1") {echo 'checked';}?> name="homepage" value="1" style="border:0px;" /></label></p>
		<p><label>Show Ads only on posts: <input type="checkbox" <?php if(isset($this->opts['onlypost']) && $this->opts['onlypost']=="1") {echo 'checked';}?> name="onlypost" value="1" style="border:0px;" /> (hide ads on other pages)</label></p>
		
		
		<?php if(isset($this->opts['name']) && $this->opts['name']!=''){
		echo '<p id="name_p" style="border:solid 1px #d00;padding:5px;">
		<b style="color:#d00;">The affiliate niche has been changed from ClickBank e-books to vacation rentals</b>. The average booking fee is about $200 instead of $20 for e-books.
		<br/><b>Your ClickBank Login:</b> <input type="text" readonly id="name" name="name" value="'.esc_attr($this->opts['name']).'" style="width:100px;" /> 
		Your previous ad impressions are recorded using your clickbank login, <a target="_blank" href="https://vacationrentals.website/affiliate-program.html#clickbank='.esc_attr($this->opts['name']).'">get your new affiliate ID here.</a> Then enter it below.
		<br/>I have registered on vacationrentals.website, hide this message: <input type="checkbox" onclick="document.getElementById(\'name\').value=\'\';document.getElementById(\'name_p\').style.display=\'none\';" style="border:0px;" />
		</p>';}
		?>
		
		<p><label>Your Affiliate ID: <a target="_blank" href="https://vacationrentals.website/affiliate-program.html">(Register here)</a><br/><input type="number" min="1" name="afID" value="<?php echo esc_attr($this->opts['afID']); ?>" style="width:100px;" required /></label></p>
		<p><label>Title: (optional)<br /><input type="text" name="title" value="<?php echo esc_html($this->opts['title']); ?>" style="width:200px;" /></label></p>
		<p><label>Change ads every: (5...300 seconds. If 0, then do not change):<br/><input type="number" min="0" max="300" step="5" name="change" value="<?php echo esc_attr($this->opts['change']); ?>" style="width:100px;display:inline;" /> sec</label></p>
		<p><label>The space between ad blocks: (0...50)<br/><input type="number" min="0" max="50" step="2" name="margin" value="<?php echo esc_attr($this->opts['margin']); ?>" style="width:100px;display:inline;" /> px</label></p>

		<div><div style="position:relative;"><label>The number of ads, horizontally:<br />
			<div style="position:absolute;left:200px;top:20px;">Preview:</div>
			<div id="width_2" style="background:#ffffff; display:inline-block; text-align:center;width:<?php echo esc_attr($this->opts['width']=='100%'?140:($this->opts['width']*20)); ?>px;height:<?php echo esc_attr($this->opts['height']*18); ?>px;background: url(<?php echo esc_url(plugins_url( 'prev.png', __FILE__ ));?>) left top;position:absolute;left:200px;top:50px;<?php echo (isset($this->opts['border'])&& $this->opts['border']=="1"?"box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;":"");?>"></div>
			<select onchange="var x=document.getElementById('width').value;var y=document.getElementById('height').value;var p=document.getElementById('width_2').style;if(x=='100%'){x=7;};p.width= Math.floor(x*20)+'px';p.height=Math.floor(y*18)+'px';" size="1" id="width" name="width" style="width:100px;display: inline;"><option value="100%" <?php if($this->opts['width']=="100%") {echo 'selected';}?>>100%</option><option value="1" <?php if($this->opts['width']=="1") {echo 'selected';}?>>1</option><option value="2" <?php if($this->opts['width']=="2") {echo 'selected';}?>>2</option><option value="3" <?php if($this->opts['width']=="3") {echo 'selected';}?>>3</option><option value="4" <?php if($this->opts['width']=="4") {echo 'selected';}?>>4</option><option value="5" <?php if($this->opts['width']=="5") {echo 'selected';}?>>5</option><option value="6" <?php if($this->opts['width']=="6") {echo 'selected';}?>>6</option><option value="7" <?php if($this->opts['width']=="7") {echo 'selected';}?>>7</option></select>
		</label></div></div>
		<div><label>The number of ads, vertically:<br />
			<select onchange="var x=document.getElementById('width').value;var y=document.getElementById('height').value;var p=document.getElementById('width_2').style;if(x=='100%'){x=7;};p.width= Math.floor(x*20)+'px';p.height=Math.floor(y*18)+'px';" size="1" id="height" name="height" style="width:100px;display: inline;"><option value="1" <?php if($this->opts['height']=="1") {echo 'selected';}?>>1</option><option value="2" <?php if($this->opts['height']=="2") {echo 'selected';}?>>2</option><option value="3" <?php if($this->opts['height']=="3") {echo 'selected';}?>>3</option><option value="4" <?php if($this->opts['height']=="4") {echo 'selected';}?>>4</option><option value="5" <?php if($this->opts['height']=="5") {echo 'selected';}?>>5</option><option value="6" <?php if($this->opts['height']=="6") {echo 'selected';}?>>6</option><option value="7" <?php if($this->opts['height']=="7") {echo 'selected';}?>>7</option><option value="8" <?php if($this->opts['height']=="8") {echo 'selected';}?>>8</option><option value="9" <?php if($this->opts['height']=="9") {echo 'selected';}?>>9</option><option value="10" <?php if($this->opts['height']=="10") {echo 'selected';}?>>10</option></select>
		</label></div>
		
		<p><label>Position on page: <select style="width:80px;" id="pos" name="pos" size="1"><option <?php if($this->opts['pos']=="Top") echo("selected"); ?>>Top</option><option <?php if($this->opts['pos']!="Top") echo("selected"); ?>>Bottom</option></select></label></p>

		<p><label>Show Border: <input onclick="document.getElementById('bordstylec').style.boxShadow=document.getElementById('bordstylec2').style.boxShadow=document.getElementById('width_2').style.boxShadow=(this.checked?'0 0 2px #aaa, 2px 2px 4px #aaa':'unset');" type="checkbox" <?php if(isset($this->opts['border'])&& $this->opts['border']=="1") {echo 'checked';}?> id="border" name="border" value="1" style="border:0px;" /></label></p>
		<div>Border Radius:<br/>
			<div id="bordstylec"  style="background:#ffffff; display:inline-block; text-align:center; padding:3px 3px 7px;width:90px;<?php echo (isset($this->opts['border'])&& $this->opts['border']=="1"?"box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;":"");?>border-radius:9px;"><label>Style 1 &nbsp;<input type="radio" <?php if(isset($this->opts['bordstyle']) && $this->opts['bordstyle']=="1") {echo 'checked';}?> value="1" id="bordstyle"  name="bordstyle" /></label></div>
			<div id="bordstylec2" style="background:#ffffff; display:inline-block; text-align:center; padding:3px 3px 7px;width:90px;<?php echo (isset($this->opts['border'])&& $this->opts['border']=="1"?"box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;":"");?>"><label>Style 2 &nbsp;<input type="radio" <?php if(isset($this->opts['bordstyle']) && $this->opts['bordstyle']=="2") {echo 'checked';}?> value="2" id="bordstyle2" name="bordstyle" /></label></div>
		</div>
		<p><label>Message/Invitation: (optional)<br /><textarea id="message" name="message" style="width:100%;" placeholder="Sample: Book your vacation rentals here and save 8% with a coupon: PROMO! These luxurious rentals, nestled in the finest resort areas, offer complete furnishing and essential amenities to enhance your stay. Bid farewell to endless emails and phone calls - secure your dream vacation rental now using the convenient search box above."><?php echo esc_textarea($this->opts['message']); ?></textarea></label></p>

		<div class="submit"><input type="submit" name="cbads_submit" value="Save" /></div>
		</form>
	</div>
	<?php
	}
	 


	// ------------ out to page ---------------------

	function cbads_ad_block() {
		global $wp,$cbads_version;
		$this->getOpts();
		$title = $this->opts['title'];
		$afID = $this->opts['afID'];
		$clickbank = (isset($this->opts['name'])?$this->opts['name']:'');
		$border=($this->opts['border']=='1'?1:0);
		$itw=200; $ith=190;
		$margin=(isset($this->opts ['margin'])?(int)$this->opts ['margin']:20);
		$marg=floor($margin/2);//magin to apply: half from 1 side + half from 2 side
		
		$width=$this->opts['width'];
		$height=$this->opts['height'];
		if($width!= "100%"){$width= (($itw+$margin)*$width+ ($margin*$border)).'px';}
		$height=(($ith+$margin)*$height+($margin*$border)).'px';

		$refer=esc_url(home_url( $wp->request ));
		if(strpos($title,'ebook')!==false || strpos($title,'e-book')!==false){$title='';}
		$hashes='coupon=PROMO&a_id='.$afID.'&referer='.$refer.($clickbank!=''?'&clickbank='.$clickbank:'');
		$featured='';

		wp_enqueue_script ( 'js', plugins_url('/js.js',   __FILE__),array(),$cbads_version,array('in_footer' => true,'strategy'  => 'defer') );
		
		$allowed_tags = wp_kses_allowed_html( 'post' );
		
		$ourdiv='<p style="'.($width!='100%'?'width:'.esc_attr($width).';max-width:100%;':'').'box-sizing:border-box;margin:auto;padding:0;">'.($title!=''?"<b>".$title."</b>":"").'<iframe data-src="https://cbads.com/ads.php?id='.esc_attr($afID).'&referer='.esc_attr($refer).'&change='.esc_attr($this->opts['change']).'&margin='.esc_attr($margin).'&v='.$cbads_version.'&a='.esc_attr($clickbank).'"
		style="width:'.esc_attr($width).';height:'.esc_attr($height).';'.($border==0?'padding:0;':'background:#fff;box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;padding:'.esc_attr($marg).'px;').($this->opts['bordstyle']=="1"?'border-radius:9px;':'').'max-width:100%;box-sizing:border-box;display:block;margin:0;border:0;" frameborder="0" scrolling="no"></iframe>
		<a target="_blank" href="https://vacationrentals.website/#'.esc_attr($hashes).'" style="padding:'.esc_attr($border*5).'px '.esc_attr(($border==1?0:1)*$marg).'px 0 0;width:100%;max-width:100%;box-sizing:border-box;text-decoration:none;color:#3E95EA;font:12px Arial;display:block;text-align:right;" title="Book your vacation rentals here and save 8% with a coupon: PROMO!">Vacation Rentals</a>'
		.($this->opts['message']!=''?wp_kses(str_replace("\n","<br/>",$this->opts['message']),$allowed_tags):"").($featured!=''?"<br/><b>Featured:</b><br/>".$featured:"").'</p>';
		return $ourdiv;
	}

	var $cbads_blocks=0;
	function cbads_out_to_page($content) {
		global $cbads_version,$cbads_blocks;
		$this->getOpts();

		if(!isset($this->opts['runplugin']) || $this->opts['runplugin']!="1"){return $content;}// exit if plugin blocked
		if(is_home() && (!isset($this->opts['homepage']) || $this->opts['homepage']!="1")){return $content;}// exit if homepage blocked
		if(!is_single() && !is_home() && (isset($this->opts['onlypost']) && $this->opts['onlypost']=="1")){return $content;}// exit if for post only
		if ($cbads_blocks==1){return $content;} $cbads_blocks=1;// exit for pages with multiple posts - show only in 1 post
		
		$ourdiv=$this->cbads_ad_block();

		if ($this->opts['pos'] == "Top") {$content = '<!-- cbads_ad_section_start --><div style="margin:10px auto;">'.$ourdiv.'</div><hr/><!-- cbads_ad_section_end -->'.$content;}
		if ($this->opts['pos'] != "Top") {$content = $content.'<!-- cbads_ad_section_start --><div style="margin:10px auto;">'.$ourdiv.'</div><!-- cbads_ad_section_end -->';}

		return $content;
	}
}//class
}//if

$cbads = new cbads(); 

function cbads_menu() {
	global $cbads;
	if (function_exists('add_options_page')) {add_options_page('Affiliate Ads for cbAds.com', 'Affiliate Ads for cbAds.com', 'administrator', 'options_page_slug', array(&$cbads, 'admin_menu'));} //tag title, text link in menu, , ,[class,function]
}

add_action('admin_menu', 'cbads_menu');
add_filter('the_content', array($cbads, 'cbads_out_to_page')); //out to page
add_shortcode('cbads', array($cbads, 'cbads_ad_block'));












//   -------    WIDGET -----------



add_action('widgets_init', 'cbads_widget');

function cbads_widget() { register_widget('cbads_Widget');}

class cbads_Widget extends WP_Widget {
	function __construct() {
		$widget_ops = ['name' => 'Affiliate Ads for cbAds.com','description' => 'This widget creates a graphic banner to display Vacation Rentals ads.'];
		parent::__construct( 'cbads_Widget', 'Affiliate Ads for cbAds.com widget', $widget_ops );
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		if(isset($new_instance['cbid'])){$instance['cbid'] = sanitize_text_field($new_instance['cbid']);}
		$instance['title'] = sanitize_text_field($new_instance['title']);
		$instance['afID'] = sanitize_text_field($new_instance['afID']);
		$instance['border'] = (isset($new_instance['border']) && $new_instance['border']=="1"?"1":"0");
		$instance['bordstyle'] = sanitize_text_field($new_instance['bordstyle']);
		$instance['height'] = sanitize_text_field($new_instance['height']);
		$instance['width'] = sanitize_text_field($new_instance['width']);
		$instance['change'] = sanitize_text_field($new_instance['change']);
		$instance['margin'] = sanitize_text_field($new_instance['margin']);
		$instance['message'] = sanitize_textarea_field($new_instance['message']);
		return $instance;
	}

	function form($instance) {
		if($instance['width']!="100%" && $instance['width']>10){$instance['width']/=200;}//corr for old users
		if($instance['height']!="100%" && $instance['height']>10){$instance['height']/=180;}//corr for old users
		if(!isset($instance['itw'])){$instance['itw']=200;}//corr for old users
		if(!isset($instance['margin'])){$instance['margin']=20;}//corr for old users
		if(!isset($instance['change'])){$instance['change']=5;}//corr for old users
		if(!isset($instance['afID'])){$instance['afID']=0;}//corr for old users
		
		$defaults = array('cbid' => '', 'title' => 'Vacation Rentals', 'afID' => '0', 'border' => '1', 'bordstyle' => '1', 'width' => '3', 'height' => '5', 'change' => '5', 'margin' => '20', 'message' => 'Book your vacation rentals here and save 8% with a coupon: PROMO! These luxurious rentals, nestled in the finest resort areas, offer complete furnishing and essential amenities to enhance your stay. Bid farewell to endless emails and phone calls - secure your dream vacation rental now using the convenient search box above.');
		$instance = wp_parse_args((array) $instance, $defaults); 

		if(isset($instance['cbid']) && $instance['cbid']!=''){
			echo '<p id="'.esc_attr($this->get_field_id('cbid')).'_p" style="border:solid 1px #d00;padding:5px;"><label>
		<b style="color:#d00;">The affiliate niche has been changed from ClickBank e-books to vacation rentals</b>. The average booking fee is about $200 instead of $20 for e-books.
		<br/><b>Your ClickBank Login:</b> <input type="text" readonly id="'.esc_attr($this->get_field_id('cbid')).'" name="'.esc_attr($this->get_field_name('cbid')).'" value="'.esc_attr($instance['cbid']).'" style="width:100px;display:inline;" /> 
		Your previous ad impressions are recorded using your clickbank login, <a target="_blank" href="https://vacationrentals.website/affiliate-program.html#clickbank='.esc_attr($this->opts['name']).'">get your new affiliate ID here.</a> Then enter it below.<br/>
		I have registered on vacationrentals.website, hide this message: <input type="checkbox" onclick="document.getElementById(\''.esc_attr($this->get_field_id('cbid')).'\').value=\'\';document.getElementById(\''.esc_attr($this->get_field_id('cbid')).'_p\').style.display=\'none\';" style="border:0px;" />
		</label></p>';}
		?>


		<p><label>Your Affiliate ID: <a target="_blank" href="https://vacationrentals.website/affiliate-program.html">(Register here)</a><br/><input type="number" min="1" id="<?php echo esc_attr($this->get_field_id('afID')); ?>" name="<?php echo esc_attr($this->get_field_name('afID')); ?>" value="<?php echo esc_attr($instance['afID']); ?>" style="width:100px;" required /></label></p>
		<p><label>Title: (optional)<br /><input type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:200px;" /></label></p>
		
		<p><label>Change ads every: (5...300 seconds. If 0, then do not change):<br/><input type="number" min="0" max="300" step="5" id="<?php echo esc_attr($this->get_field_id('change')); ?>" name="<?php echo esc_attr($this->get_field_name('change')); ?>" value="<?php echo esc_attr($instance['change']); ?>" style="width:100px;display:inline;" /> sec</label></p>
		<p><label>The space between ad blocks: (0...50)<br/><input type="number" min="0" max="50" step="2" id="<?php echo esc_attr($this->get_field_id('margin')); ?>" name="<?php echo esc_attr($this->get_field_name('margin')); ?>" value="<?php echo esc_attr($instance['margin']); ?>" style="width:100px;display:inline;" /> px</label></p>
		
		<p><div style="position:relative;"><label>The number of ads, horizontally:<br /><div style="position:absolute;left:200px;top:20px;">Preview:</div><div class="cbads" id="<?php echo esc_attr($this->get_field_id('width')); ?>_2" style="width:<?php echo esc_attr($instance['width']=='100%'?140:($instance['width']*20)); ?>px;height:<?php echo esc_attr($instance['height']*18); ?>px;background: url(<?php echo esc_url(plugins_url( 'prev.png', __FILE__ ));?>) left top;position:absolute;left:200px;top:50px;<?php echo($instance['border']=="1"?'box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;':'');?>"></div>
		<select onchange="var x=this.value;var y=document.getElementById('<?php echo esc_attr($this->get_field_id('height'));?>').value;var prev=document.getElementById('<?php echo esc_attr($this->get_field_id('width')); ?>_2').style;if(x=='100%'){x=7;}prev.width= Math.floor(x*20)+'px';prev.height= Math.floor(y*18)+'px';" size="1" id="<?php echo esc_attr($this->get_field_id('width')); ?>" name="<?php echo esc_attr($this->get_field_name('width')); ?>" style="width:100px;display: inline;"><option value="100%" <?php if($instance['width']=="100%") {echo 'selected';}?>>100%</option><option value="1" <?php if($instance['width']=="1") {echo 'selected';}?>>1</option><option value="2" <?php if($instance['width']=="2") {echo 'selected';}?>>2</option><option value="3" <?php if($instance['width']=="3") {echo 'selected';}?>>3</option><option value="4" <?php if($instance['width']=="4") {echo 'selected';}?>>4</option><option value="5" <?php if($instance['width']=="5") {echo 'selected';}?>>5</option><option value="6" <?php if($instance['width']=="6") {echo 'selected';}?>>6</option><option value="7" <?php if($instance['width']=="7") {echo 'selected';}?>>7</option></select>
		</label></p> 
		<p><label>The number of ads, vertically:<br />
		<select onchange="var y=this.value;var x=document.getElementById('<?php echo esc_attr($this->get_field_id('width')); ?>').value;var prev=document.getElementById('<?php echo esc_attr($this->get_field_id('width')); ?>_2').style;if(x=='100%'){x=7;};prev.width = Math.floor(x*20)+'px';prev.height=Math.floor(y*18)+'px';" size="1" id="<?php echo esc_attr($this->get_field_id('height')); ?>" name="<?php echo esc_attr($this->get_field_name('height')); ?>" style="width:100px;display: inline;"><option value="1" <?php if($instance['height']=="1") {echo 'selected';}?>>1</option><option value="2" <?php if($instance['height']=="2") {echo 'selected';}?>>2</option><option value="3" <?php if($instance['height']=="3") {echo 'selected';}?>>3</option><option value="4" <?php if($instance['height']=="4") {echo 'selected';}?>>4</option><option value="5" <?php if($instance['height']=="5") {echo 'selected';}?>>5</option><option value="6" <?php if($instance['height']=="6") {echo 'selected';}?>>6</option><option value="7" <?php if($instance['height']=="7") {echo 'selected';}?>>7</option><option value="8" <?php if($instance['height']=="8") {echo 'selected';}?>>8</option><option value="9" <?php if($instance['height']=="9") {echo 'selected';}?>>9</option><option value="10" <?php if($instance['height']=="10") {echo 'selected';}?>>10</option></select>
		</label></p> 
		
		<p><label>Show Border: <input onclick="var e = document.getElementsByTagName('div');for(var i=0; i<e.length; i++) {if(e[i].className!=undefined && e[i].className=='cbads'){e[i].style.boxShadow=(this.checked?'0 0 2px #aaa, 2px 2px 4px #aaa':'unset');}} this.value=(this.checked?1:0)" type="checkbox" <?php if($instance['border']=="1") {echo 'checked';}?> id="<?php echo esc_attr($this->get_field_id('border')); ?>" name="<?php echo esc_attr($this->get_field_name('border')); ?>" value="1" style="border:0px;" /></label></p>
		<div style="background:#eee;padding:0 10px 10px;"><label>Border Radius:</label><br/>
		<div class="cbads" style="<?php echo($instance['border']=="1"?'box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;':'');?>text-align:center;padding:3px 3px 7px;background:#fff;width:90px;display:inline-block;border-radius:9px;"><label>Style 1 <input type="radio" <?php if($instance['bordstyle']=="1") {echo 'checked';}?> value="1" id="<?php echo esc_attr($this->get_field_id('bordstyle')); ?>"  name="<?php echo esc_attr($this->get_field_name('bordstyle')); ?>" /></label></div>
		<div class="cbads" style="<?php echo($instance['border']=="1"?'box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;':'');?>text-align:center;padding:3px 3px 7px;background:#fff;width:90px;display:inline-block;"><label>Style 2 <input type="radio" <?php if($instance['bordstyle']=="2") {echo 'checked';}?> value="2" id="<?php echo esc_attr($this->get_field_id('bordstyle')); ?>2" name="<?php echo esc_attr($this->get_field_name('bordstyle')); ?>" /></label></div>
		</div>
		<p><label>Message/Invitation: (optional)<br /><textarea id="<?php echo esc_attr($this->get_field_id('message')); ?>" name="<?php echo esc_attr($this->get_field_name('message')); ?>" style="width:100%;" placeholder="Sample: Book your vacation rentals here and save 8% with a coupon: PROMO! These luxurious rentals, nestled in the finest resort areas, offer complete furnishing and essential amenities to enhance your stay. Bid farewell to endless emails and phone calls - secure your dream vacation rental now using the convenient search box above."><?php echo esc_textarea($instance['message']); ?></textarea></label></p>

	<?php
	}



	//--------- print to page ------------
	function widget($args, $instance) {
		global $cbads_version,$wp;
		extract($args);
		
		$title_ = $instance['title']; if(strpos($title_,'ebook')!==false || strpos($title_,'e-book')!==false){$title_='';}
		$title = apply_filters('widget_title', $title_);
		$allowed_tags = wp_kses_allowed_html( 'post' );
		echo wp_kses($before_widget,$allowed_tags);
		if ($title!='') {echo wp_kses($before_title . $title. $after_title,$allowed_tags);}
		
		$border=($instance['border']==1?1:0);
		$itw=200; $ith=190;
		$margin=(int)$instance ['margin'];//magin to apply: half from 1 side + half from 2 side
		$marg=floor($margin/2);//magin to apply: half from 1 side + half from 2 side
		
		$width=$instance['width'];
		$height=$instance['height'];
		if($width!= "100%"){$width= (($itw+$margin)*$width+ ($margin*$border)).'px';}
		$height=(($ith+$margin)*$height+($margin*$border)).'px';
		
		$refer=esc_url(home_url( $wp->request ));
		
		$hashes='coupon=PROMO&a_id='.$instance['afID'].'&referer='.$refer.(isset($instance['cbid'])?'&clickbank='.$instance['cbid']:'');
		
		wp_enqueue_script ( 'js', plugins_url('/js.js',   __FILE__),array(),$cbads_version,array('in_footer' => true,'strategy'  => 'defer') );
	?>
		<p style="width:<?php echo esc_attr($width);?>;max-width:100%;box-sizing:border-box;margin:auto;padding:0;"><iframe data-src="https://cbads.com/ads.php?id=<?php echo esc_attr($instance['afID']); ?>&referer=<?php echo esc_attr($refer); ?>&change=<?php echo esc_attr($instance['change']);?>&margin=<?php echo esc_attr($margin);?>&v=<?php echo esc_attr($cbads_version)?><?php echo (isset($instance['cbid'])?'&a='.esc_attr($instance['cbid']):''); ?>"
		style="width:<?php echo esc_attr($width);?>;height:<?php echo esc_attr($height);?>;<?php echo ($border==0? 'padding:0;':'background:#fff;box-shadow:0 0 2px #aaa, 2px 2px 4px #aaa;padding:'. esc_attr($marg).'px;'); ?><?php echo ($instance['bordstyle']=="1"?"border-radius:9px;":"");?>max-width:100%;box-sizing:border-box;display:block;margin:0;border:0;" frameborder='0' scrolling='no'></iframe>
		<a target='_blank' href='https://vacationrentals.website/#<?php echo esc_attr($hashes); ?>' style='width:<?php echo esc_attr($width);?>;padding:<?php echo esc_attr($border*5); ?>px <?php echo esc_attr(($border==1?0:1)*$marg); ?>px 0 0;max-width:100%;box-sizing:border-box;text-decoration:none;color:#3E95EA;font:12px Arial;display:block;text-align:right;' title='Book your vacation rentals here and save 8% with a coupon: PROMO!'>Vacation Rentals</a>
	<?php 
		echo ($instance['message']!=''?wp_kses(str_replace("\n","<br/>",$instance['message']),$allowed_tags):"")."</p>"; 

		echo wp_kses($after_widget,$allowed_tags);
	}
}
?>