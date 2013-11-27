<?php
/**
 * Plugin Name: Advanced meta widget
 * Description: Widget for displaying Wordpress info - Login/out, Admin, RSS feeds and Link to wordpress.org extended by few functions as Loginout redirect...
 * Version: 0.9.2
 * Text Domain: metawidget
 * Domain Path: /langs
 * Author: Sjiamnocna
 * Author URI: http://sjiaphoto.g6.cz/advanced-meta-widget
 */
function metawidget_plugin_init() {
  load_plugin_textdomain('metawidget',false,'advanced-meta-widget/langs/'); 
}
add_action('plugins_loaded','metawidget_plugin_init');
add_action('widgets_init','my_widget');
function my_widget() {
	register_widget('adv_meta_info');
}
class adv_meta_info extends WP_Widget {
	function adv_meta_info() {
		$widget_ops=array('classname'=>'metawidget','description'=>__('Widget displaying many useful informations as Admin link or Login link and RSS feed links','metawidget'));
		$control_ops=array('width'=>300,'height'=>350,'id_base'=>'metawidget-widget');
		$this->WP_Widget('metawidget-widget',__('Advanced meta info widget','metawidget'),$widget_ops,$control_ops);
	}
	function widget($args,$instance){
		extract($args);
		$title=apply_filters('widget_title',$instance['title']);
                $show_adminurl=isset($instance['show_admin_link']) ? $instance['show_admin_link'] : false;
		$show_loginout=isset($instance['show_loginout']) ? $instance['show_loginout'] : false;
                $use_redir=isset($instance['use_loginout_redir']) ? $instance['use_loginout_redir'] : false;
                $show_wpreglink=isset($instance['show_wpreglink']) ? $instance['show_wpreglink'] : false;
                $show_postrsslink=isset($instance['show_postrsslink']) ? $instance['show_postrsslink'] : false;
                $postrsslink_type=isset($instance['use_rss_format']) ? $instance['use_rss_format'] : '';
                $show_commentrsslink=isset($instance['show_commentrsslink']) ? $instance['show_commentrsslink'] : false;
                $show_wordpressorglink=isset($instance['show_wordpressorglink']) ? $instance['show_wordpressorglink'] : false;
                $show_profilelink=isset($instance['show_profilelink']) ? $instance['show_profilelink'] : false;
                $each_entry=($instance['show_as_element']=='li') ? array('before'=>'<li>','after'=>'</li>'): array('before'=>'<p>','after'=>'</p>');
                $whole_widget=($instance['show_as_element']=='li') ? array('before'=>'<ul>','after'=>'</ul>'): array('before'=>'<div>','after'=>'</div>');
		echo $before_widget;
		if($title)echo $before_title.$title.$after_title;
                echo $whole_widget['before'];
                if($show_adminurl && is_user_logged_in()){
                    echo $each_entry['before'];
                    echo'<a href="'.admin_url().'" alt="'.__('Admin link','metawidget').'">'.__('Site admin','metawidget').'</a>';
                    echo $each_entry['after'];
                }
		if($show_loginout){
                    if($use_redir){
                        $redir='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    }
                    echo $each_entry['before'];
                    wp_loginout($redir);
                    echo $each_entry['after'];
                }
                if($show_wpreglink){
                    wp_register($each_entry['before'],$each_entry['after'],TRUE);
                }
                if($show_profilelink && is_user_logged_in()){
                    echo $each_entry['before'];
                    echo'<a href="'.get_edit_user_link().'" alt="'.__('Edit profile link','metawidget').'">'.__('Profile','metawidget').'</a>';
                    echo $each_entry['after'];
                }
                if($show_postrsslink){
                    echo $each_entry['before'];
                    echo'<a href="'.get_bloginfo($postrsslink_type).'" alt="'.__('Posts RSS feed','metawidget').'">'.__('Posts RSS feed','metawidget').'</a>';
                    echo $each_entry['after'];
                }
                if($show_commentrsslink){
                    echo $each_entry['before'];
                    echo'<a href="'.get_bloginfo('comments_rss2_url').'" alt="'.__('Comments RSS feed','metawidget').'">'.__('Comments RSS feed','metawidget').'</a>';
                    echo $each_entry['after'];
                }
                if($show_wordpressorglink){
                    echo $each_entry['before'];
                    echo'<a href="http://wordpress.org/" alt="'.__('link to Wordpress.org','metawidget').'">'.'Wordpress.org'.'</a>';
                    echo $each_entry['after'];
                }
                echo'<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
';
                echo $whole_widget['after'];
		echo $after_widget;
	}
	function update($new_instance,$old_instance) {
		$instance=$old_instance;
		$instance['title']=strip_tags( $new_instance['title'] );
                $instance['show_admin_link']=$new_instance['show_admin_link'];
		$instance['show_loginout']=$new_instance['show_loginout'];
                $instance['use_loginout_redir']=$new_instance['use_loginout_redir'];
                $instance['show_wpreglink']=$new_instance['show_wpreglink'];
                $instance['show_postrsslink']=$new_instance['show_postrsslink'];
                $instance['show_commentrsslink']=$new_instance['show_commentrsslink'];
                $instance['show_wordpressorglink']=$new_instance['show_wordpressorglink'];
                $instance['show_profilelink']=$new_instance['show_profilelink'];
                $instance['show_as_element']=$new_instance['show_as_element'];
                $instance['use_rss_format']=$new_instance['use_rss_format'];
		return $instance;
	}
	function form($instance) {
		$defaults = array('title'=>__('Advanced meta info Widget','metawidget'),'show_as_element'=>'li','show_admin_link'=>'yes','show_loginout'=>'yes','use_loginout_redir'=>false,'show_profilelink'=>false,'show_postrsslink'=>'yes','use_rss_format'=>'rss_url','show_commentrsslink'=>false,'show_wordpressorglink'=>'yes','wporglink_lang'=>'nolang');
		$instance = wp_parse_args($instance, $defaults );
                ?>
            <div id="options" style="margin:0px 0 15px 0;padding:0px;">
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','metawidget'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_admin_link'); ?>" name="<?php echo $this->get_field_name('show_admin_link'); ?>" <?php checked($instance['show_admin_link'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_admin_link'); ?>"><?php _e('Display admin link (only logged-in users)','metawidget'); ?></label><br/>
                </p>
                <p style="margin:0px;">
			<input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_loginout'); ?>" name="<?php echo $this->get_field_name('show_loginout'); ?>" <?php checked( $instance['show_loginout'],'yes'); ?> /> 
			<label for="<?php echo $this->get_field_id('show_loginout'); ?>"><?php _e('Display loginout','metawidget'); ?></label><br/>
                        
                        <input class="checkbox" style="margin-left:10px;" type="checkbox" value="yes" id="<?php echo $this->get_field_id('use_loginout_redir'); ?>" name="<?php echo $this->get_field_name('use_loginout_redir'); ?>" <?php checked($instance['use_loginout_redir'],'yes'); ?> />
                        <label for="<?php echo $this->get_field_id('use_loginout_redir'); ?>"><?php _e('Use redirection to actual URL', 'metawidget'); ?></label>
                </p>
                <?php if(get_option('users_can_register')){ ?>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_wpreglink'); ?>" name="<?php echo $this->get_field_name('show_wpreglink'); ?>" <?php checked( $instance['show_wpreglink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_wpreglink'); ?>"><?php _e('Display link to registration page','metawidget'); ?></label><br/>
                </p>
                <?php } ?>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_profilelink'); ?>" name="<?php echo $this->get_field_name('show_profilelink'); ?>" <?php checked( $instance['show_profilelink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_profilelink'); ?>"><?php _e('Display link to edit or view profile (only logged-in users)','metawidget'); ?></label><br/>
                </p>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_postrsslink'); ?>" name="<?php echo $this->get_field_name('show_postrsslink'); ?>" <?php checked( $instance['show_postrsslink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_postrsslink'); ?>"><?php _e('Display post RSS feed link','metawidget'); ?></label><br/>
                    
                    <select style="margin-left:8%;" id="<?php echo $this->get_field_id('use_rss_format'); ?>" name="<?php echo $this->get_field_name('use_rss_format'); ?>">
                        <option value="atom_url" <?php selected($instance['use_rss_format'],'atom_url'); ?>><?php _e('RSS Atom','metawidget'); ?>
                        <option value="rdf_url" <?php selected($instance['use_rss_format'],'rdf_url'); ?>><?php _e('RDF','metawidget'); ?>
                        <option value="rss_url" <?php selected($instance['use_rss_format'],'rss_url'); ?>><?php _e('RSS','metawidget'); ?>
                        <option value="rss2_url" <?php selected($instance['use_rss_format'],'rss2_url'); ?>><?php _e('RSS2','metawidget'); ?>
                    </select>
                </p>
                
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_commentrsslink'); ?>" name="<?php echo $this->get_field_name('show_commentrsslink'); ?>" <?php checked( $instance['show_commentrsslink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_commentrsslink'); ?>"><?php _e('Display comments RSS feed link','metawidget'); ?></label><br/>
                </p>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_wordpressorglink'); ?>" name="<?php echo $this->get_field_name('show_wordpressorglink'); ?>" <?php checked( $instance['show_wordpressorglink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_wordpressorglink'); ?>"><?php _e('Display link to Wordpress.org','metawidget'); ?></label><br/>
                    <select style="margin-left:8%;" id="<?php echo $this->get_field_id('wporglink_lang'); ?>" name="<?php echo $this->get_field_name('wporglink_lang'); ?>">
                        <option value="nolang" <?php selected($instance['wporglink_lang'],'nolang'); ?>><?php _e('Wordpress.org','metawidget'); ?>
                        <option value="ru" <?php selected($instance['wporglink_lang'],'ru'); ?>><?php _e('Russian','metawidget'); ?>
                        <option value="cs" <?php selected($instance['wporglink_lang'],'sc'); ?>><?php _e('Czech','metawidget'); ?>
                    </select>
                </p>
                <p style="margin:15px 5px 5px 25px;">
                    <label for="<?php echo $this->get_field_id('show_as_element'); ?>"><?php _e('Display items as','metawidget'); ?></label><br/>
                    <select id="<?php echo $this->get_field_id('show_as_element'); ?>" name="<?php echo $this->get_field_name('show_as_element'); ?>">
                        <option value="p" <?php selected($instance['show_as_element'],'p'); ?>><?php _e('Paragraphs','metawidget'); ?>
                        <option value="li" <?php selected($instance['show_as_element'],'li'); ?>><?php _e('List items','metawidget'); ?>
                    </select>
                </p>
            </div>
	<?php
	}
}
?>
