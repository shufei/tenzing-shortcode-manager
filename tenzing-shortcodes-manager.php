<?php
/*
 Plugin Name: Tenzing Shortcodes Manager

 Plugin URI: http://gotenzing.com/wordpress-plugins/tenzing-shortcodes-manager

 Description: For shortcode utility manager

 Version: 1.0

 Author: Shufei Gong

 License: GPLv2
 */
session_start();

/* 注册激活插件时要调用的函数 */
//register_activation_hook( __FILE__, 'tz_campaign_monitor_install');

/* 注册停用插件时要调用的函数 */
register_deactivation_hook( __FILE__, 'tsm_remove' );

function tsm_remove(){
	if(isset($_SESSION['tsm_shortcodes'])){
		unset($_SESSION['tsm_shortcodes']);
	}
	
	if(isset($_SESSION['tsm_lastItems'])){
		unset($_SESSION['tsm_lastItems']);
	}
}

function tsm_scripts(){
	wp_enqueue_style('tsmstyle', plugins_url( 'tsm.css', __FILE__ ));
	wp_enqueue_script( 'tsmscript', plugins_url( 'tsm.js', __FILE__ ), array('jquery'), '1.0.0', true );
}

add_action( 'admin_footer','tsm_scripts');



if( is_admin() ) {
	/*  利用 admin_menu 钩子，添加菜单 */
	add_action('admin_menu', 'display_tsm_menu');
}



function display_tsm_menu() {
	/* add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);  */
	/* 页名称，菜单名称，访问级别，菜单别名，点击该菜单时的回调函数（用以显示设置页面） */
	add_options_page('Set Shortcode Manager', 'Shorcode Manager Menu', 'administrator', 'display_tsm','display_tsm_html_page');
}

add_filter( 'su/data/shortcodes', 'short_code_list' );


function short_code_list($shortcodes){
	$_SESSION['tsm_shortcodes']=$shortcodes;
	return $shortcodes;
}


add_action( 'admin_post_tsm_click_hook', 'process_tsm_click' );

function process_tsm_click()
{
	if(isset($_POST['suItems'])){
		$myfile = fopen(plugin_dir_path(__FILE__).'tsm.css', "w");
		$txt='';
		
		$items=$_POST['suItems'];
		foreach ($items as $item){
			$txt.="#su-generator-choices > span[data-shortcode=".$item."]{display:none;}";
		}
		
		fwrite($myfile, $txt);
		fclose($myfile);
		$_SESSION['tsm_lastItems']=$items;
		
		wp_redirect(admin_url('options-general.php?page=display_tsm'));
	}else{
		unset($_SESSION['tsm_lastItems']);
		$myfile = fopen(plugin_dir_path(__FILE__).'tsm.css', "w");
		$txt='';
		fwrite($myfile, $txt);
		fclose($myfile);
		wp_redirect(admin_url('options-general.php?page=display_tsm'));
	}
	 
   	  	
	
}






function display_tsm_html_page() {
	if(isset($_SESSION['tsm_shortcodes'])){
		$shortcodes=$_SESSION['tsm_shortcodes'];
	
	
	
	?>
    <div class="wrap">  
        <h2>Tenzing Shortcode Manager</h2>
        <h3 style="color:red;">Please check the shortcode items you want to hide in user interface</h3>  
        <form method="post" action="admin-post.php">
        <input type="hidden" name="action" value="tsm_click_hook" />  
	        <?php 
	        if(isset($_SESSION['tsm_lastItems'])){
	        	$items=$_SESSION['tsm_lastItems'];
	        	foreach ($shortcodes as $key=>$value){
	        		if(in_array($key, $items)) {
	        			echo '<input type="checkbox" name="suItems[]" value="'.$key.'" id="'.$key.'" checked/>'.$key.'<br />';
	        		}else{
	        			echo '<input type="checkbox" name="suItems[]" value="'.$key.'" id="'.$key.'"/>'.$key.'<br />';
	        		}
	        		
	        	}
	        	
	        }else {
	        	foreach ($shortcodes as $key=>$value){
	        		 
	        		echo '<input type="checkbox" name="suItems[]" value="'.$key.'" id="'.$key.'" class="tsm_items"/>'.$key.'<br />';
	        	}
	        	
	        }
	        		
	       
	       
	       ?>   
           <input type="submit" value="Submit" id="tsm_click"/>
        </form>  
       
    </div>  
<?php  
	}else{
		echo '<h1 style="color:red;">Please install and active Shortcodes Ultimate plugin</h1>';
	}

}  	
?>