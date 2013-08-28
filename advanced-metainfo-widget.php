<?php
/**
 * Plugin Name: Advanced meta widget
 * Description: Widget for displaying Wordpress info - Login/out, Admin, RSS feeds and Link to wordpress.org extended by few functions as Loginout redirect...
 * Version: 0.8.1
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
                $show_postrsslink=isset($instance['show_postrsslink']) ? $instance['show_postrsslink'] : false;
                $show_commentrsslink=isset($instance['show_commentrsslink']) ? $instance['show_commentrsslink'] : false;
                $show_wordpressorglink=isset($instance['show_wordpressorglink']) ? $instance['show_wordpressorglink'] : false;
                $show_profilelink=isset($instance['show_profilelink']) ? $instance['show_profilelink'] : false;
                $each_entry=array('before'=>'<li>','after'=>'</li>');
                $whole_widget=array('before'=>'<ul>','after'=>'</ul>');
		echo $before_widget;
		if($title)echo $before_title.$title.$after_title;
                echo $whole_widget['before'];
                if($show_adminurl && is_user_logged_in()){
                    echo'<li>';
                    echo'<a href="'.admin_url().'" alt="'.__('Admin link','metawidget').'">'.__('Site admin','metawidget').'</a>';
                    echo'</li>';
                }
		if($show_loginout){
                    if($use_redir){
                        $redir='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    }
                    echo $each_entry['before'];
                    wp_loginout($redir);
                    echo $each_entry['after'];
                }
                if($show_profilelink && is_user_logged_in()){
                    echo $each_entry['before'];
                    echo'<a href="'.get_edit_user_link().'" alt="'.__('Edit profile link','metawidget').'">'.__('Profile','metawidget').'</a>';
                    echo $each_entry['after'];
                }
                if($show_postrsslink){
                    echo $each_entry['before'];
                    echo'<a href="'.get_bloginfo('rss2_url').'" alt="'.__('Posts RSS feed','metawidget').'">'.__('Posts RSS feed','metawidget').'</a>';
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
                echo $whole_widget['after'];
		echo $after_widget;
	}
	function update($new_instance,$old_instance) {
		$instance=$old_instance;
		$instance['title']=strip_tags( $new_instance['title'] );
                $instance['show_admin_link']=$new_instance['show_admin_link'];
		$instance['show_loginout']=$new_instance['show_loginout'];
                $instance['use_loginout_redir']=$new_instance['use_loginout_redir'];
                $instance['show_postrsslink']=$new_instance['show_postrsslink'];
                $instance['show_commentrsslink']=$new_instance['show_commentrsslink'];
                $instance['show_wordpressorglink']=$new_instance['show_wordpressorglink'];
                $instance['show_profilelink']=$new_instance['show_profilelink'];
		return $instance;
	}
	function form($instance) {
		$defaults = array('title'=>__('Advanced meta info Widget','metawidget'),'show_admin_link'=>'yes','show_loginout'=>'yes','use_loginout_redir'=>false,'show_postrsslink'=>'yes','show_commentrsslink'=>false,'show_wordpressorglink'=>'yes');
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
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_profilelink'); ?>" name="<?php echo $this->get_field_name('show_profilelink'); ?>" <?php checked( $instance['show_profilelink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_profilelink'); ?>"><?php _e('Display link to edit or view profile (only logged-in users)','metawidget'); ?></label><br/>
                </p>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_postrsslink'); ?>" name="<?php echo $this->get_field_name('show_postrsslink'); ?>" <?php checked( $instance['show_postrsslink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_postrsslink'); ?>"><?php _e('Display post RSS feed link','metawidget'); ?></label><br/>
                </p>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_commentrsslink'); ?>" name="<?php echo $this->get_field_name('show_commentrsslink'); ?>" <?php checked( $instance['show_commentrsslink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_commentrsslink'); ?>"><?php _e('Display comments RSS feed link','metawidget'); ?></label><br/>
                </p>
                <p style="margin:0px;">
                    <input class="checkbox" type="checkbox" value="yes" id="<?php echo $this->get_field_id('show_wordpressorglink'); ?>" name="<?php echo $this->get_field_name('show_wordpressorglink'); ?>" <?php checked( $instance['show_wordpressorglink'],'yes'); ?> /> 
                    <label for="<?php echo $this->get_field_id('show_wordpressorglink'); ?>"><?php _e('Display link to Wordpress.org','metawidget'); ?></label><br/>
                </p>
            </div>
	<?php
	}
}
?>
