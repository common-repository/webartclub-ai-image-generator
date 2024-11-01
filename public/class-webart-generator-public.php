<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://webartclub.com
 * @since      1.0.0
 *
 * @package    Webart_Generator
 * @subpackage Webart_Generator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Webart_Generator
 * @subpackage Webart_Generator/public
 * @author     Web Art Club <contact@webartclub.com>
 */
class Webart_Generator_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Webart_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Webart_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/webart-generator-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Webart_Generator_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Webart_Generator_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/webart-generator-public.js', array( 'jquery' ), $this->version, false );

	}

}




add_shortcode('webart_generate_form','webart_frontend_form');
function webart_frontend_form(){
	$webartclub_options = get_option( 'webartclub_option_name' ); // Array of All Options
	$artstation_api_key_0 = $webartclub_options['artstation_api_key_0']; // Artstation API Key
	$request_allowed_per_day_1 = $webartclub_options['request_allowed_per_day_1']; // Request allowed per day
	$anyone_can_use_2 = $webartclub_options['anyone_can_use_2']; // Anyone can use?
	$give_credits_to_developer_3 = $webartclub_options['give_credits_to_developer_3']; 
	
	/*$fun_chatgpt_frontend_options = get_option( 'webart_ajax_start' ); // Array of All Options
	$show_created_by_knowhalim = $fun_chatgpt_frontend_options['show_created_by_knowhalim_2'];
    $credit='';*/
    if ($give_credits_to_developer_3=="yes"){
        $credit='<center><small><a href="https://webartclub.com" target="_blank">Powered by Webartclub</a></small></center>';
    }
    
    $ok=1;
    if ($anyone_can_use_2=="no"){
        if (is_user_logged_in()){
            $ok = 1;
        }else{
            $ok=0;
        }
    }
    if ($ok==1){
	add_action('wp_footer','webart_ajax_start');
	$form ='<div class="sample_chatgpt"><p>Describe the image you want to generate into the box and click on the generate button.</p><textarea id="webart_ask" ></textarea><br><div class="webart_button"><div class="webart_btn">Generate Image Now</div><div class="webart_show_limit">'.webart_get_today_limit().'</div></div></div>
	<center><div class="preloader_webart">'.webart_preloader().'</div>
	<div class="response_msg"></div></center>'.$credit;
    }else{
        $return;
    }
	return $form;
}


add_action('wp_ajax_nopriv_webart_do_image', 'webart_do_run');
add_action('wp_ajax_webart_do_image', 'webart_do_run');


function webart_ajax_start(){
?>
<style>
.sample_chatgpt .webart_btn {
    background-color: #8b2626;
    display: inline-block;
    color: #fff;
    padding: 10px 20px;
    margin: 0 auto;
    cursor:pointer;
    margin:20px;
	margin-left:0px;
}
.webart_button {
    display: flex;
    align-content: flex-start;
    flex-wrap: wrap;
    flex-direction: row;
}
	.webart_show_limit{
		vertical-align: middle;
    padding: 27px;
		padding-left: 0px;
	}
	.refresh_abt{
	    margin-right: 20px;
    background-color: #404040;
    padding: 10px 20px;
	}
</style>
<script>
var score_count=1;

	
jQuery('.preloader_webart').hide();
jQuery(".webart_btn").click(function(){
	var webart_ask = jQuery('#webart_ask').val();
    jQuery(".webart_btn").hide();
	jQuery('.preloader_webart').show();
	jQuery("#webart_ask").attr('disabled',true);
	jQuery('.webart_show_limit').html('Generating...please waiting..');
	var data = { 'action':'webart_do_image', 'webart_ask':webart_ask}
	jQuery.ajax({
		url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
		type: "POST",
	  	data,
		dataType: "json",
		success: function(response) {
			jQuery('.preloader_webart').hide();
			
			
			if (response.status=="success"){
                jQuery('.webart_button').html('<button onClick="location.reload();" class="refresh_abt">Refresh page to generate new image</button><span class="webart_show_limit" style="padding:10px;">Success!</span>');
				jQuery(".response_msg").html('');

				jQuery(".ai_generate").html('<img src="' +response.message+'" />')
			}else{
				jQuery(".response_msg").html('failed! ');
				jQuery("#webart_ask").attr('disabled',false);
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			jQuery('.preloader_webart').hide();
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);
			jQuery("#webart_ask").attr('disabled',false);
        	alert("There seem to be an error."); // this will be "timeout"
    	},
		timeout: 925000 
		
    });
	
});
	
</script>
<?php
}


function webart_preloader(){
	return '<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="252px" height="57px" viewBox="0 0 128 29" xml:space="preserve"><g><path fill="#b25936" d="M-75.617,2.175h9.956l-8.533,24.65H-84.15ZM-64.1,4.322h8.345L-62.9,24.678h-8.345Zm11.48,1.527h6.867L-51.636,22.9H-58.5Zm11.386,2.29h4.982l-4.27,12.468H-45.5Zm11.846,2.036h3.634l-3.115,8.906H-32.5Zm11.23,1.781H-16l-1.847,5.089H-20ZM-88.346,4.322H-80l-7.153,20.356H-95.5Zm-12.271,1.527h6.868L-99.636,22.9H-106.5Zm-12.613,2.29h4.982l-4.271,12.468H-117.5Zm-12.155,2.036h3.634l-3.114,8.906H-128.5ZM-6.6,12.973H-5.25L-6.4,16.281H-7.75Zm72.98-10.8h9.956l-8.533,24.65H57.85ZM77.9,4.322h8.345L79.1,24.678H70.751Zm11.48,1.527h6.867L90.364,22.9H83.5Zm11.387,2.29h4.982l-4.271,12.468H96.5Zm11.845,2.036h3.634l-3.114,8.906H109.5Zm11.231,1.781H126l-1.846,5.089H122ZM53.654,4.322H62L54.846,24.678H46.5ZM41.383,5.849h6.867L42.364,22.9H35.5ZM28.77,8.139h4.982l-4.27,12.468H24.5ZM16.616,10.174h3.634L17.135,19.08H13.5Zm118.789,2.8h1.346L135.6,16.281H134.25ZM5.346,11.956H7.5L5.654,17.045H3.5Z"/><animateTransform attributeName="transform" type="translate" values="12 0;24 0;36 0;48 0;60 0;72 0;84 0;96 0;108 0;120 0;132 0;144 0" calcMode="discrete" dur="1800ms" repeatCount="indefinite"/></g></svg>
';

}

function webart_do_run(){
	$ask = sanitize_text_field($_POST['webart_ask']);
	$answer = webart_create_ai_img($ask);
	
	$res = array(
	"status"=>"success",
	"message"=>$answer
	);
	echo json_encode($res);
	die();
}


function webart_get_today_limit(){
	$totalrequest = get_option( 'webart_check_requests_limit' ) ? get_option( 'webart_check_requests_limit' ):array();
    //return print_r($totalrequest,true);
	$webartclub_options = get_option( 'webartclub_option_name' ); // Array of All Options
	$artstation_api_key_0 = $webartclub_options['artstation_api_key_0']; // Artstation API Key
	$request_allowed_per_day_1 = $webartclub_options['request_allowed_per_day_1']; // Request allowed per day
	$anyone_can_use_2 = $webartclub_options['anyone_can_use_2']; // Anyone can use?
	$give_credits_to_developer_3 = $webartclub_options['give_credits_to_developer_3']; 
	if (count($totalrequest)>0){
		$today=strval(date('d-m-Y'));
		if (array_key_exists($today,$totalrequest)){
            //$request=$totalrequest[$today];
			$count = count($totalrequest[$today]);
            //return $count;
            //return print_r($request,true);
			return "Free credits per day: ".$request_allowed_per_day_1."  <span id='credits_left'>Credits left: <strong><u>".($request_allowed_per_day_1-$count)."</u></strong></span>";
		}
		else{
			$count=0;
            //return $count;
			return "Free credits per day: ".$request_allowed_per_day_1."  <span id='credits_left'>Credits left: <strong><u>".($request_allowed_per_day_1-$count)."</u></strong></span>";
		}
        return ;
	}else{
        return '<div class="outofcredits">Sorry! We are out of credits!</div>';
    }
}

function webart_create_ai_img($content){
	$totalrequest = get_option( 'webart_check_requests_limit' )  ? get_option( 'webart_check_requests_limit' ):array();
	$webartclub_options = get_option( 'webartclub_option_name' ); // Array of All Options
	$artstation_api_key_0 = $webartclub_options['artstation_api_key_0']; // Artstation API Key
	$request_allowed_per_day_1 = $webartclub_options['request_allowed_per_day_1']; // Request allowed per day
	$anyone_can_use_2 = $webartclub_options['anyone_can_use_2']; // Anyone can use?
	$give_credits_to_developer_3 = $webartclub_options['give_credits_to_developer_3']; 
	$no_allow=0;
	if ($totalrequest){
		$today=date('d-m-Y');
		if (array_key_exists($today,$totalrequest)){

			if (count($totalrequest[$today])>=$request_allowed_per_day_1){
				$no_allow=1;
			}
			else{
				$totalrequest[$today][]=date("h:i:s A");
				update_option( 'webart_check_requests_limit',$totalrequest);
			}
		}
		else{
			$totalrequest[$today][]=date("h:i:s A");
			update_option( 'webart_check_requests_limit',$totalrequest);
		}
	}else{
		
		$today=date('d-m-Y');
		$arr = array();
		$arr[$today][]=date("h:i:s A");
		update_option( 'webart_check_requests_limit',$arr);
		
	}
	if ($no_allow==0){
		
		$image_url = webart_generate_image($content);
		

		return $image_url;
	}else{
		return "Limit for today has reached! We limit to ".$request_allowed_per_day_1.' requests per day in order not to incur high usage cost.';
	}
	
}




function webart_generate_image($keyword){

	$webartclub_options = get_option( 'webartclub_option_name' ); // Array of All Options
	$artstation_api_key_0 = $webartclub_options['artstation_api_key_0']; // Artstation API Key
	$request_allowed_per_day_1 = $webartclub_options['request_allowed_per_day_1']; // Request allowed per day
	$anyone_can_use_2 = $webartclub_options['anyone_can_use_2']; // Anyone can use?
	$give_credits_to_developer_3 = $webartclub_options['give_credits_to_developer_3']; 
    if ($artstation_api_key_0 !=""){
	$prompt = $keyword;


	$the_post = '{
"prompt": "'.$prompt.'",
"negative_prompt": "nude, naked, nsfw, deformed eyes, canvas frame, cartoon, 3d, ((disfigured)), ((bad art)), ((deformed)),((extra limbs)),((close up)),((b&w)), weird colors, blurry, (((duplicate))), ((morbid)), ((mutilated)), [out of frame], extra fingers, mutated hands, ((poorly drawn hands)), ((poorly drawn face)), (((mutation))), (((deformed))), ((ugly)), blurry, ((bad anatomy)), (((bad proportions))), ((extra limbs)), cloned face, (((disfigured))), out of frame, ugly, extra limbs, (bad anatomy), gross proportions, (malformed limbs), ((missing arms)), ((missing legs)), (((extra arms))), (((extra legs))), mutated hands, (fused fingers), (too many fingers), (((long neck))), Photoshop, video game, ugly, tiling, poorly drawn hands, poorly drawn feet, poorly drawn face, out of frame, mutation, mutated, extra limbs, extra legs, extra arms, disfigured, deformed, cross-eye, body out of frame, blurry, bad art, bad anatomy, 3d render",
"width": 512,
"height": 512,
"num_outputs": "1",
"guidance_scale": 7,
"num_inference_steps": 20,
"prompt_strength": 0.8,
"seed": -1,
"public": false,
"detail": true,
"mode": "semi",
"save": true
}';

	$args_post = array(
       'method' => 'POST',
    'timeout' => 45,
    'redirection' => 5,
    'httpversion' => '1.1',
    'blocking' => true,
	'body'        => $the_post,
	'sslverify' => false,
	'headers'     => array(
		'Content-type' => 'application/json',
		'Authorization'=> 'Token '.$artstation_api_key_0
	  ),
      'cookies' => array() 
	);

	$response = wp_remote_post( 'https://artsmart.ai/api/v1/process?type=text2img', $args_post );
	
	 $res = $response['body'];
	$returnvalue = json_decode($res,true);

if (array_key_exists("error",$returnvalue)){
    
        wp_mail(get_bloginfo('admin_email'),'Image is not generated for the keyword '.$prompt, 'Dear admin,
        
This email is to inform you that the featured image for the keyword : '.$prompt.' is not generated. Please manually upload a featured image. If this keeps on happening, please update your fallback prompt.

Error: '.print_r($response,true).'

Regards
WebArtClub Plugin');
    
}else{
	
    $theurl= $returnvalue['result']["output"][0];
	$imageurl = webart_downloadimg($theurl,$keyword);
	return $imageurl;
}

    }
}



function webart_downloadimg($imageurl,$keyword){
	
	
	$my_post = array(
		'post_title'    => wp_strip_all_tags( $keyword ),
		'post_content'  => $keyword,
		'post_status'   => 'publish',
		'post_type'   => 'ai',
	);

	
	//Add Featured Image
	// Add Featured Image to Post
	$image_url        = $imageurl; // Define the image URL here
	$image_name       = "webart_".time().'.png';
	$image_namejpg       = "webart_".time().'.jpeg';
	$upload_dir       = wp_upload_dir(); // Set upload folder
	
	$getimg = wp_remote_get( $image_url );
	    
    if ( is_wp_error( $getimg ) ) {
        // handle error
        return plugins_url( '/', __FILE__ ).'/fail.jpg';
    } else {
        $body = wp_remote_retrieve_body( $getimg );
  
        $image_data = $body;
    }
    
    // Insert the post into the database
	$post_insert_id = wp_insert_post( $my_post );



	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
	$unique_file_name2 = wp_unique_filename( $upload_dir['path'], $image_namejpg ); // Generate unique name
	
	$filename         = basename( $unique_file_name ); // Create image file name
	$filename2         = basename( $unique_file_name2 );
	
	$thepath='';
	$jpgpath = '';
	// Check folder permission and define file location
	if( wp_mkdir_p( $upload_dir['path'] ) ) {
		$thepath = $upload_dir['path'];
		$file = $thepath. '/' . $filename;
		
	} else {
		$thepath = $upload_dir['basedir'];
		$file =$thepath . '/' . $filename;
	}
	
	$jpgpath = $thepath.'/'.$filename2;
	// Create the image  file on the server
	file_put_contents( $file, $image_data );

	// Check image file type
	$wp_filetype = wp_check_filetype( $filename, null );

	// Set attachment data
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => sanitize_file_name( $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);
	
	// Create the attachment
	$attach_id = wp_insert_attachment( $attachment, $file, $post_insert_id );

	// Include image.php
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id, $attach_data );

	// And finally assign featured image to post
	set_post_thumbnail( $post_insert_id, $attach_id );
	add_post_meta($post_insert_id, '_thumbnail_id', $attach_id);
	update_post_meta($post_insert_id, '_thumbnail_id', $attach_id);	
	
	$imgurl =get_the_post_thumbnail_url($post_insert_id);
	
	return $imgurl;
}


add_shortcode('webart_display_generated','webart_display_generated');
function webart_display_generated(){
	add_action('wp_footer','webart_display_generated_script');
	$display = '<div class="ai_generate">';
	
	$display .= '</div>';
	return $display;
}


function webart_display_generated_script(){
	$webartclub_options = get_option( 'webartclub_option_name' ); // Array of All Options
	$default = $webartclub_options['extra_custom_field_1_4']; // Artstation API Key
	?>
<script>
jQuery('document').ready(function(){
		jQuery('.ai_generate').html('<img src="<?php echo $default; ?>" />');
	});
</script>
<?php
}

add_shortcode('webart_display','webart_generate_showcase');
function webart_generate_showcase(){
	$ai = get_posts(array('post_type' => 'ai'));
	
	$display = '<div class="ai_showcase">';
	foreach ($ai as $item){
		$display .= '<div class="ai_img"><img src="'.get_the_post_thumbnail_url($item->ID).'" /></div>';
	}
	$display .= '</div>';
	return $display;
}

function webart_generate_fimg($post_id, $post, $update){
	$webartclub_options = get_option( 'webartclub_option_name' ); // Array of All Options
	$artstation_api_key_0 = $webartclub_options['artstation_api_key_0']; // Artstation API Key
	$request_allowed_per_day_1 = $webartclub_options['request_allowed_per_day_1']; // Request allowed per day
	$anyone_can_use_2 = $webartclub_options['anyone_can_use_2']; // Anyone can use?
	$give_credits_to_developer_3 = $webartclub_options['give_credits_to_developer_3']; 
	if ($artstation_api_key_0!=""){
		$all_the_time = 0;
		if ($all_the_time==1){
			if ( $post->post_type == 'post' && $post->post_status == 'publish' && empty(get_post_meta($post_id, '_thumbnail_id')) ) {
				$gotimg = get_post_meta($post_id, '_thumbnail_id') ? get_post_meta($post_id, '_thumbnail_id'):'';
				if (!has_post_thumbnail($post_id)){
					$prompt = apply_filters('kh_autoimg_prompt',strtolower($post->post_title));
					knowhalim_ai_auto_generate_image($prompt,$post->ID);
					update_post_meta( $post_id, 'check_if_generated_img', true );

				}
			}
		}
		if ($all_the_time==0){
			if ( $post->post_type == 'post' && $post->post_status == 'publish' && empty(get_post_meta($post_id, 'check_if_generated_img')) ) {
				if (!has_post_thumbnail($post_id)){
					$prompt = apply_filters('kh_autoimg_prompt',strtolower($post->post_title));
					knowhalim_ai_auto_generate_image($prompt,$post->ID);
					update_post_meta( $post_id, 'check_if_generated_img', true );
				}

			}
		}
	}
}








function webartclub_posttype(): void {
	$labels = [
		'name' => _x( 'AI Images', 'Post Type General Name', 'webartclub' ),
		'singular_name' => _x( 'AI Image', 'Post Type Singular Name', 'webartclub' ),
		'menu_name' => __( 'AI Images', 'webartclub' ),
		'name_admin_bar' => __( 'AI Images', 'webartclub' ),
		'archives' => __( 'AI Images Archives', 'webartclub' ),
		'attributes' => __( 'AI Images Attributes', 'webartclub' ),
		'parent_item_colon' => __( 'Parent AI Image:', 'webartclub' ),
		'all_items' => __( 'All AI Images', 'webartclub' ),
		'add_new_item' => __( 'Add New AI Image', 'webartclub' ),
		'add_new' => __( 'Add New', 'webartclub' ),
		'new_item' => __( 'New AI Image', 'webartclub' ),
		'edit_item' => __( 'Edit AI Image', 'webartclub' ),
		'update_item' => __( 'Update AI Image', 'webartclub' ),
		'view_item' => __( 'View AI Image', 'webartclub' ),
		'view_items' => __( 'View AI Images', 'webartclub' ),
		'search_items' => __( 'Search AI Images', 'webartclub' ),
		'not_found' => __( 'AI Image Not Found', 'webartclub' ),
		'not_found_in_trash' => __( 'AI Image Not Found in Trash', 'webartclub' ),
		'featured_image' => __( 'Featured Image', 'webartclub' ),
		'set_featured_image' => __( 'Set Featured Image', 'webartclub' ),
		'remove_featured_image' => __( 'Remove Featured Image', 'webartclub' ),
		'use_featured_image' => __( 'Use as Featured Image', 'webartclub' ),
		'insert_into_item' => __( 'Insert into AI Image', 'webartclub' ),
		'uploaded_to_this_item' => __( 'Uploaded to this AI Image', 'webartclub' ),
		'items_list' => __( 'AI Images List', 'webartclub' ),
		'items_list_navigation' => __( 'AI Images List Navigation', 'webartclub' ),
		'filter_items_list' => __( 'Filter AI Images List', 'webartclub' ),
	];
	apply_filters( 'ai-labels', $labels );

	$args = [
		'label' => __( 'AI Image', 'webartclub' ),
		'description' => __( 'Browse our AI generated images', 'webartclub' ),
		'labels' => $labels,
		'supports' => [
			'title',
			'thumbnail',
		],
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-admin-post',
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'can_export' => false,
		'capability_type' => 'page',
		'show_in_rest' => true,
	];
	apply_filters( 'ai-args', $args );

	register_post_type( 'ai', $args );
}
add_action( 'init', 'webartclub_posttype', 0 );




function webart_recommends(){


	$args_post = array(
       'method' => 'POST',
    'timeout' => 45,
    'redirection' => 5,
    'httpversion' => '1.1',
    'blocking' => true,
	'body'        => '{"about": "Auto AI Featured Image"}',
	'sslverify' => false,
	'headers'     => array(
		'Content-type' => 'application/json',
		'Authorization'=> 'Bearer 22jd948hhfrg'
	  ),
      'cookies' => array() 
	);


	$response = wp_remote_post( 'https://webartclub.com/wp-json/webart/v1/recommend', $args_post );
	
	 $res = $response['body'];
	
	$returnvalue = json_decode($res,true);

	$display='<div class="recommends">'.$returnvalue['instruction'].'<h3>Other recommendations</h3>';
	foreach ($returnvalue['news'] as $item){
		$display .='<div class="kh_news">'.$item.'</div>';
	}
	$display .='</div>';
	return $display;
}




class WebArtClub {
	private $webartclub_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'webartclub_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'webartclub_page_init' ) );
	}

	public function webartclub_add_plugin_page() {
		
		add_submenu_page(
			'edit.php?post_type=ai',
			__( 'Webartclub Settings', 'khpay' ),
			__( 'Webartclub Settings', 'khpay' ),
			'manage_options', // capability
			'webartclub', // menu_slug
			array( $this, 'webartclub_create_admin_page' ) // function
		);
	}

	public function webartclub_create_admin_page() {
		$this->webartclub_options = get_option( 'webartclub_option_name' ); ?>

		<div class="wrap">
			<h2>WebArtClub</h2>
			<p></p>
			<?php settings_errors(); ?>
			<div class="webcontainer">
				
			<div class="left_side">
				
			<form method="post" action="options.php">
				<?php
					settings_fields( 'webartclub_option_group' );
					do_settings_sections( 'webartclub-admin' );
					submit_button();
				?>
			</form>
				</div>
				<div class="right_side">
					<?php echo webart_recommends(); ?>
				</div>
			</div>
		</div>
	<?php }

	public function webartclub_page_init() {
		register_setting(
			'webartclub_option_group', // option_group
			'webartclub_option_name', // option_name
			array( $this, 'webartclub_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'webartclub_setting_section', // id
			'Settings', // title
			array( $this, 'webartclub_section_info' ), // callback
			'webartclub-admin' // page
		);

		add_settings_field(
			'artstation_api_key_0', // id
			'Artsmart API Key', // title
			array( $this, 'artstation_api_key_0_callback' ), // callback
			'webartclub-admin', // page
			'webartclub_setting_section' // section
		);

		add_settings_field(
			'request_allowed_per_day_1', // id
			'Request allowed per day', // title
			array( $this, 'request_allowed_per_day_1_callback' ), // callback
			'webartclub-admin', // page
			'webartclub_setting_section' // section
		);

		add_settings_field(
			'anyone_can_use_2', // id
			'Anyone can use?', // title
			array( $this, 'anyone_can_use_2_callback' ), // callback
			'webartclub-admin', // page
			'webartclub_setting_section' // section
		);

		add_settings_field(
			'give_credits_to_developer_3', // id
			'Give credits to developer?', // title
			array( $this, 'give_credits_to_developer_3_callback' ), // callback
			'webartclub-admin', // page
			'webartclub_setting_section' // section
		);

		add_settings_field(
			'extra_custom_field_1_4', // id
			'Default Image Placeholder', // title
			array( $this, 'extra_custom_field_1_4_callback' ), // callback
			'webartclub-admin', // page
			'webartclub_setting_section' // section
		);
/*
		add_settings_field(
			'extra_custom_field_2_5', // id
			'Extra Custom Field 2', // title
			array( $this, 'extra_custom_field_2_5_callback' ), // callback
			'webartclub-admin', // page
			'webartclub_setting_section' // section
		);*/
	}

	public function webartclub_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['artstation_api_key_0'] ) ) {
			$sanitary_values['artstation_api_key_0'] = sanitize_text_field( $input['artstation_api_key_0'] );
		}

		if ( isset( $input['request_allowed_per_day_1'] ) ) {
			$sanitary_values['request_allowed_per_day_1'] = sanitize_text_field( $input['request_allowed_per_day_1'] );
		}

		if ( isset( $input['anyone_can_use_2'] ) ) {
			$sanitary_values['anyone_can_use_2'] = $input['anyone_can_use_2'];
		}

		if ( isset( $input['give_credits_to_developer_3'] ) ) {
			$sanitary_values['give_credits_to_developer_3'] = $input['give_credits_to_developer_3'];
		}

		if ( isset( $input['extra_custom_field_1_4'] ) ) {
			$sanitary_values['extra_custom_field_1_4'] = sanitize_text_field( $input['extra_custom_field_1_4'] );
		}

		if ( isset( $input['extra_custom_field_2_5'] ) ) {
			$sanitary_values['extra_custom_field_2_5'] = sanitize_text_field( $input['extra_custom_field_2_5'] );
		}

		return $sanitary_values;
	}

	public function webartclub_section_info() {
		
	}

	public function artstation_api_key_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="webartclub_option_name[artstation_api_key_0]" id="artstation_api_key_0" value="%s">',
			isset( $this->webartclub_options['artstation_api_key_0'] ) ? esc_attr( $this->webartclub_options['artstation_api_key_0']) : ''
		);
	}

	public function request_allowed_per_day_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="webartclub_option_name[request_allowed_per_day_1]" id="request_allowed_per_day_1" value="%s">',
			isset( $this->webartclub_options['request_allowed_per_day_1'] ) ? esc_attr( $this->webartclub_options['request_allowed_per_day_1']) : ''
		);
	}

	public function anyone_can_use_2_callback() {
		?> <fieldset><?php $checked = ( isset( $this->webartclub_options['anyone_can_use_2'] ) && $this->webartclub_options['anyone_can_use_2'] === 'yes' ) ? 'checked' : '' ; ?>
		<label for="anyone_can_use_2-0"><input type="radio" name="webartclub_option_name[anyone_can_use_2]" id="anyone_can_use_2-0" value="yes" <?php echo $checked; ?>> Yes, anyone can use it even if they are not a registered user</label><br>
		<?php $checked = ( isset( $this->webartclub_options['anyone_can_use_2'] ) && $this->webartclub_options['anyone_can_use_2'] === 'no' ) ? 'checked' : '' ; ?>
		<label for="anyone_can_use_2-1"><input type="radio" name="webartclub_option_name[anyone_can_use_2]" id="anyone_can_use_2-1" value="no" <?php echo $checked; ?>> No they need to logged in.</label></fieldset> <?php
	}

	public function give_credits_to_developer_3_callback() {
		?> <fieldset><?php $checked = ( isset( $this->webartclub_options['give_credits_to_developer_3'] ) && $this->webartclub_options['give_credits_to_developer_3'] === 'yes' ) ? 'checked' : '' ; ?>
		<label for="give_credits_to_developer_3-0"><input type="radio" name="webartclub_option_name[give_credits_to_developer_3]" id="give_credits_to_developer_3-0" value="yes" <?php echo $checked; ?>> Yes, show powered by Webartclub</label><br>
		<?php $checked = ( isset( $this->webartclub_options['give_credits_to_developer_3'] ) && $this->webartclub_options['give_credits_to_developer_3'] === 'no' ) ? 'checked' : '' ; ?>
		<label for="give_credits_to_developer_3-1"><input type="radio" name="webartclub_option_name[give_credits_to_developer_3]" id="give_credits_to_developer_3-1" value="no" <?php echo $checked; ?>> No, maybe next time</label></fieldset> <?php
	}

	public function extra_custom_field_1_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="webartclub_option_name[extra_custom_field_1_4]" id="extra_custom_field_1_4" value="%s">',
			isset( $this->webartclub_options['extra_custom_field_1_4'] ) ? esc_attr( $this->webartclub_options['extra_custom_field_1_4']) : ''
		);
	}

	public function extra_custom_field_2_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="webartclub_option_name[extra_custom_field_2_5]" id="extra_custom_field_2_5" value="%s">',
			isset( $this->webartclub_options['extra_custom_field_2_5'] ) ? esc_attr( $this->webartclub_options['extra_custom_field_2_5']) : ''
		);
	}

}
if ( is_admin() )
	$webartclub = new WebArtClub();
