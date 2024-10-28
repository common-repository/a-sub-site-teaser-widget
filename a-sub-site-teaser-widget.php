<?php
/*
Plugin Name: A Sub Site Teaser Widget
Plugin URI: http://www.kevin-busch.com
Description: Teasers subpages
Version: 1.0
Author: Kevin Busch
Author URI: http://www.kevin-busch.com
License: GPLv2
*/

function load_languages_init()
{

    load_plugin_textdomain( 'a-sub-site-teaser-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/' );
}



class A_Sub_Site_Teaser_Widget extends WP_Widget 
{
    private $defaults = array(
            'title' => '',
            'showimages' => 1,
            'flipflop' => 1,
            'startingside' => 'odd',
            'limitsites' => 0,
            'maxsites' => '10',
            'limitchars' => 0,
            'maxchars' => '250',
            'excerptsonly' => 1,
            'morelink' => 1,
            'morelinktext' => 'Mehr',
            'randomize' => 0,
            'sort_column'=>'menu_order',
            'sort_order'=>'asc',
            'is_custom_order' => 0,
            'custom_order'=>'',
            'parentpage' => 'actual',
            'ignoreempty' => 1,
            'exclude'=> '',
            'customimageclass' => ''
        );
   
    function A_Sub_Site_Teaser_Widget() {
    $options = array(
                  'classname' => 'a_sub_site_teaser_widget',
                  'description' => 'Unterseiten geteasert anzeigen','dynamic-subpages'
                );
    $this->WP_Widget('A_Sub_Site_Teaser_Widget', 'A Sub Site Teaser', $options);             
    add_filter( 'plugin_action_links', array($this, 'plugin_action_links'), 10, 2 );
    }

    private function getAllPagesAsList()
    {
        // prepare list of pages
        $pages_array = get_pages( array(
            'hierarchical' => 0,
            'post_status' => 'publish'
        ));
        // make blank first option
        $page_select_list = array( '' => '' );
        foreach( $pages_array as $page ){
            $page_select_list[$page->ID] = esc_attr( $page->post_title );
        }
        
        return $page_select_list;
        
    }

 	public function form($instance) 
	{
	    $instance = wp_parse_args( (array) $instance, $this->defaults);
        
		$title = $instance['title'];
		$showimages = $instance['showimages'];
		$flipflop = $instance['flipflop'];
        $startingside = $instance['startingside'];
		$limitsites = $instance['limitsites'];
		$maxsites = $instance['maxsites'];
		$limitchars = $instance['limitchars'];
		$maxchars = $instance['maxchars'];
		$excerptsonly = $instance['excerptsonly'];
        $morelink = $instance['morelink'];
        $morelinktext = $instance['morelinktext'];
        $randomize = $instance['randomize'];
        $parentpage = $instance['parentpage'];
        $ignoreempty = $instance['ignoreempty'];
        $customimageclass = $instance['customimageclass'];
        
        $sort_column = $instance['sort_column'];
        $sort_order = $instance['sort_order'];
        $exclude = $instance['exclude'];
        $custom_order = $instance['custom_order'];
        $is_custom_order = $instance['is_custom_order'];
        
        $pagesList = $this->getAllPagesAsList();
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','a-sub-site-teaser-widget'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<table width="100%">
		    <colgroup>
		      <col width = "50%" />
		      <col width = "50%" />
		    </colgroup>
		    <tr>
		        <td colspan="2">
                    <h3><?php _e('Imagesettings','a-sub-site-teaser-widget'); ?></h3>
                </td>
		    </tr>
		    <tr>
		        <td>
		            <p>
                        <label for="<?php echo $this->get_field_id('showimages'); ?>"><?php _e('Images?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('showimages'); ?>" name="<?php echo $this->get_field_name('showimages'); ?>" value="1" <?php if ($instance['showimages'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('showimages'); ?>" name="<?php echo $this->get_field_name('showimages'); ?>" value="0" <?php if ($instance['showimages'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p>
		        </td>
		        <td>
		            &nbsp;
		        </td>
		    </tr>
		    <tr>
		        <td>
		            <p>
                        <label for="<?php echo $this->get_field_id('flipflop'); ?>"><?php _e('Alternate?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('flipflop'); ?>" name="<?php echo $this->get_field_name('flipflop'); ?>" value="1" <?php if ($instance['flipflop'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('flipflop'); ?>" name="<?php echo $this->get_field_name('flipflop'); ?>" value="0" <?php if ($instance['flipflop'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
		        </td>
		        <td>
		            <p>
                        <label for="<?php echo $this->get_field_id('startingside'); ?>"><?php _e('Start with?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('startingside'); ?>" name="<?php echo $this->get_field_name('startingside'); ?>" value="odd" <?php if ($instance['startingside'] == 'odd') { echo "checked='checked'"; } ?>><?php _e('Odd','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('startingside'); ?>" name="<?php echo $this->get_field_name('startingside'); ?>" value="even" <?php if ($instance['startingside'] == 'even') { echo "checked='checked'"; } ?>><?php _e('Even','a-sub-site-teaser-widget');?>
                    </p> 
		        </td>
		    </tr>
		    <tr>
		        <td colspan="2">
		            <p>
                        <label for="<?php echo $this->get_field_id('customimageclass'); ?>"><?php _e('Custom image CSS:','a-sub-site-teaser-widget'); ?></label> 
                        <input class="widefat" id="<?php echo $this->get_field_id('customimageclass'); ?>" name="<?php echo $this->get_field_name('customimageclass'); ?>" type="text" value="<?php echo esc_attr($customimageclass); ?>" />
                    </p>
		        </td>
		    </tr>
		    <tr>
		        <td colspan="2">
		            <hr />
		            <h3><?php _e('Pagesettings','a-sub-site-teaser-widget'); ?></h3>
		        </td>
		    </tr>
		    <tr>
                <td colspan="2">
                    <p>
                        <label for="<?php echo $this->get_field_id('parentpage'); ?>"><?php _e('Parent page:','a-sub-site-teaser-widget'); ?></label><br /> 
                        <select class="" id="<?php echo $this->get_field_id( 'parentpage' ); ?>" name="<?php echo $this->get_field_name('parentpage'); ?>">
                          <option class="" value="actual" <?php if ($parentpage == "actual") { echo "selected='true'"; } ?>><?php _e('Actual Page','a-sub-site-teaser-widget'); ?></option> 
                          <?php foreach( $pagesList as $page_id => $page_title ) {
                                
                                printf( '<option class="" value="%1$s" %3$s>%2$s</option>',
                                    $page_id,
                                    $page_title,
                                    selected( $parentpage, $page_id, false ),
                                    esc_url( get_edit_post_link( $page_id ) )
                                );
                            } ?>
                        </select>          
                    </p> 
                </td>
            </tr>
		    <tr>
		        <td>
		            <p>
                        <label for="<?php echo $this->get_field_id('limitsites'); ?>"><?php _e('Limit sites?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('limitsites'); ?>" name="<?php echo $this->get_field_name('limitsites'); ?>" value="1" <?php if ($instance['limitsites'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('limitsites'); ?>" name="<?php echo $this->get_field_name('limitsites'); ?>" value="0" <?php if ($instance['limitsites'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
        
		        </td>
		        <td>
		            <p>
                        <label for="<?php echo $this->get_field_id('maxsites'); ?>"><?php _e('Maximum sites:','a-sub-site-teaser-widget'); ?></label> 
                        <input class="widefat" id="<?php echo $this->get_field_id('maxsites'); ?>" name="<?php echo $this->get_field_name('maxsites'); ?>" type="text" value="<?php echo esc_attr($maxsites); ?>" />
                    </p>  
		        </td>
		    </tr>
		    <tr>
                <td colspan="2">
                    <p>
                        <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php echo __('Exclude pages:','a-sub-site-teaser-widget')." <i>(".__('Coma separated ID list','a-sub-site-teaser-widget').")";?></i>: </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo esc_attr($exclude); ?>" />
                    </p>
                </td>
            </tr>
		    <tr>
                <td colspan="2">
                    <hr />
                    <h3><?php _e('Textsettings','a-sub-site-teaser-widget'); ?></h3>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('limitchars'); ?>"><?php _e('Limit text?','a-sub-site-teaser-widget');?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('limitchars'); ?>" name="<?php echo $this->get_field_name('limitchars'); ?>" value="1" <?php if ($instance['limitchars'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('limitchars'); ?>" name="<?php echo $this->get_field_name('limitchars'); ?>" value="0" <?php if ($instance['limitchars'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
                </td>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('maxchars'); ?>"><?php _e('Maximum chars:','a-sub-site-teaser-widget'); ?></label> 
                        <input class="widefat" id="<?php echo $this->get_field_id('maxchars'); ?>" name="<?php echo $this->get_field_name('maxchars'); ?>" type="text" value="<?php echo esc_attr($maxchars); ?>" />
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('excerptsonly'); ?>"><?php _e('Excerp only?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('excerptsonly'); ?>" name="<?php echo $this->get_field_name('excerptsonly'); ?>" value="1" <?php if ($instance['excerptsonly'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('excerptsonly'); ?>" name="<?php echo $this->get_field_name('excerptsonly'); ?>" value="0" <?php if ($instance['excerptsonly'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
        
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('ignoreempty'); ?>"><?php _e('Ignore empty?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('ignoreempty'); ?>" name="<?php echo $this->get_field_name('ignoreempty'); ?>" value="1" <?php if ($instance['ignoreempty'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('ignoreempty'); ?>" name="<?php echo $this->get_field_name('ignoreempty'); ?>" value="0" <?php if ($instance['ignoreempty'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('morelink'); ?>"><?php _e('More link?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('morelink'); ?>" name="<?php echo $this->get_field_name('morelink'); ?>" value="1" <?php if ($instance['morelink'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('morelink'); ?>" name="<?php echo $this->get_field_name('morelink'); ?>" value="0" <?php if ($instance['morelink'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
                </td>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('morelinktext'); ?>"><?php _e('More text:','a-sub-site-teaser-widget'); ?></label> 
                        <input class="widefat" id="<?php echo $this->get_field_id('morelinktext'); ?>" name="<?php echo $this->get_field_name('morelinktext'); ?>" type="text" value="<?php echo esc_attr($morelinktext); ?>" />
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr />
                    <h3><?php _e('Sortsettings','a-sub-site-teaser-widget'); ?></h3>
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('randomize'); ?>"><?php _e('Random?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('randomize'); ?>" name="<?php echo $this->get_field_name('randomize'); ?>" value="1" <?php if ($instance['randomize'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('randomize'); ?>" name="<?php echo $this->get_field_name('randomize'); ?>" value="0" <?php if ($instance['randomize'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
                </td>
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('sort_column'); ?>"><?php _e('Sort by:','a-sub-site-teaser-widget'); ?></label>
                        <select class="widefat" id="<?php echo $this->get_field_id('sort_column'); ?>" name="<?php echo $this->get_field_name('sort_column'); ?>">
                            <?php $op=array(
                            "Menu order"=>"menu_order",
                            "Post Title"=>"post_title",
                            "Post Date"=>"post_date",
                            "Post modified"=>"post_modified",
                            "ID"=>"ID",
                            "Post Author"=>"post_author",
                            "Post Name"=>"post_name",
                            );
                                foreach($op as $name=>$value){
                                    echo "<option value='$value' ".(esc_attr($sort_column)==$value?"selected":"").">$name</option>";
                            }
                            ?>
                        </select>    
                    </p>
                </td>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('sort_order'); ?>"><?php _e('Sort order:','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('sort_order'); ?>" name="<?php echo $this->get_field_name('sort_order'); ?>" value="asc" <?php if ($instance['sort_order'] == "asc") { echo "checked='checked'"; } ?>><?php echo "ASC";?>
                        <input type="radio" id="<?php echo $this->get_field_id('sort_order'); ?>" name="<?php echo $this->get_field_name('sort_order'); ?>" value="desc" <?php if ($instance['sort_order'] == "desc") { echo "checked='checked'"; } ?>><?php echo "DESC";?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                      <p>
                        <label for="<?php echo $this->get_field_id('is_custom_order'); ?>"><?php _e('Custom order?','a-sub-site-teaser-widget'); ?></label><br />
                        <input type="radio" id="<?php echo $this->get_field_id('is_custom_order'); ?>" name="<?php echo $this->get_field_name('is_custom_order'); ?>" value="1" <?php if ($instance['is_custom_order'] == 1) { echo "checked='checked'"; } ?>><?php _e('Yes','a-sub-site-teaser-widget');?>
                        <input type="radio" id="<?php echo $this->get_field_id('is_custom_order'); ?>" name="<?php echo $this->get_field_name('is_custom_order'); ?>" value="0" <?php if ($instance['is_custom_order'] == 0) { echo "checked='checked'"; } ?>><?php _e('No','a-sub-site-teaser-widget');?>
                    </p> 
                </td>
                <td>
                    <p>
                        <label for="<?php echo $this->get_field_id('custom_order'); ?>"><?php echo __("Custom Order pages:",'a-sub-site-teaser-widget')." <i>(".__("Coma separated ID list",'a-sub-site-teaser-widget').")";?></i>: </label>
                        <input class="widefat" id="<?php echo $this->get_field_id('custom_order'); ?>" name="<?php echo $this->get_field_name('custom_order'); ?>" type="text" value="<?php echo esc_attr($custom_order); ?>" />
                    </p>
                </td>
            </tr>

		</table>
		
		<?php
		
	}

	public function update($new_instance, $old_instance) 
	{        
	    $instance = array();
        /*$instance['title'] = strip_tags($new_instance['title']);*/
        $instance['title'] = $new_instance['title'];
        $instance['showimages'] = $new_instance['showimages'];
        $instance['flipflop'] = $new_instance['flipflop'];
        $instance['startingside'] = $new_instance['startingside'];
        $instance['limitsites'] = $new_instance['limitsites'];
        $instance['maxsites'] = $new_instance['maxsites'];
        $instance['limitchars'] = $new_instance['limitchars'];
        $instance['maxchars'] = $new_instance['maxchars'];
        $instance['excerptsonly'] = $new_instance['excerptsonly'];
        $instance['morelink'] = $new_instance['morelink'];
        $instance['morelinktext'] = $new_instance['morelinktext'];
        $instance['randomize'] = $new_instance['randomize'];
        $instance['parentpage'] = $new_instance['parentpage'];
        $instance['ignoreempty'] = $new_instance['ignoreempty'];
        $instance['customimageclass'] = $new_instance['customimageclass'];
        
        $instance['sort_column'] = $new_instance['sort_column'];
        $instance['sort_order'] = $new_instance['sort_order'];
        $instance['exclude'] = $new_instance['exclude'];
        $instance['is_custom_order'] = $new_instance['is_custom_order'];
        $instance['custom_order'] = $new_instance['custom_order'];
     
     
        return $instance;   
	}

	public function widget($args, $instance) 
	{
	    extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        
        if ( !empty($instance['title']) )
            echo $args['before_title'] . $instance['title'] . $args['after_title'];
          
          
        global $page;
        
        $randomize = $instance['randomize'];
        $parentid = $instance['parentpage'];
        $limitsites = $instance['limitsites'];
        $maxsites = $instance['maxsites'];
        $sort_column = $instance['sort_column'];
        $sort_order = $instance['sort_order'];
        $custom_order = $instance['custom_order'];
        $excludeArray=explode(",",$instance['exclude']);
        $is_custom_order = $instance['is_custom_order'];
        $custom_order_array = explode(",", $instance['custom_order']);      
       
        if($parentid == 'actual')
        {
            $parentid = get_the_id();
        }

        $parameterArray = array(
            'post_type' => 'page',
            'child_of' => $parentid,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order
        );
       
        $children_array = get_pages($parameterArray);

        if($limitsites == 0 || $maxsites > count($children_array))
        {
            $maxsites = count($children_array);
        }

        if($randomize == 1)
        {    
            shuffle($children_array); 
        }

        if($is_custom_order == 1 && count($custom_order_array) > 0)
        {
            $tmpArray = array();
            foreach($custom_order_array as $ele)
            {
                foreach($children_array as $chil)
                {
                    if($chil->ID == $ele)
                    {
                       array_push($tmpArray,$chil); 
                    }
                }
            }
            $children_array = $tmpArray;
        }
            echo "<div class='asst' id='" . $this->id. "-item'>";
            echo "<ul class='asstul'>";
        
        
        if($instance['startingside'] == 'odd')
        {
            $even = false;
            $evenoddclass = "asst-odd";
        }
        else{
            $even = true;
            $evenoddclass = "asst-even";
        }
        
        $pagecount = 0;
        
        
        foreach($children_array as $currPage)
        {
            if(in_array($currPage->ID,$excludeArray))
            {
                continue;
            }
            
            $pagecount++;
            if($pagecount > $maxsites)
            {
                break;
            }
            
            $empty = $this->printOutput($currPage, $even, $evenoddclass, $instance);
            
            if($empty == "empty")
            {
              $pagecount--;  
            }
            else{
                if($instance['flipflop'] == 1)
                {
                    if($even)
                    {
                        $evenoddclass = "asst-odd";
                        $even = false;
                    }
                    else
                    {
                        $evenoddclass = "asst-even";
                        $even = true;
                    }  
                }
            }
            
        }
        
        
            echo "</ul>";
            echo "</div>";
        echo $args['after_widget'];
	}


    function printOutput($currentPage, $even, $evenoddclass, $instance)
    {
        $displaytext =  ($instance['excerptsonly'] == 1 ?  $currentPage->post_excerpt : $currentPage->post_content);
        $displaytext = trim($displaytext);

        if($instance['limitchars'] == 1 && $instance['maxchars'] < strlen($displaytext))
        {
            $displaytext = substr($displaytext, 0, $instance['maxchars']) . "...";
        }
        
        $tidy = new tidy();

        $options = array("show-body-only" => true); 
        $tidy = tidy_parse_string($displaytext, $options, 'utf8');
        tidy_clean_repair($tidy);
        
        $displaytext =  $tidy;
        $displaytext = trim($displaytext);
        
        if($instance['ignoreempty'] == 1 && strlen($displaytext) == 0 )
        {
            return "empty";
        }

        
        echo    '<li class="asst-item ' . $evenoddclass . ' clearfix">';
        
        if($instance['showimages'] == 1)
        {
            echo '<a href="' . $currentPage->guid . '" class="asst-image ">&nbsp;'. get_the_post_thumbnail($currentPage->ID, 'a-sub-site-teaser-widget-image', array('class' => $instance['customimageclass'] )) .'</a>';
        }                
        echo            '<div class="asst-content">'.
                        '<a clasS="asst-title">' . $currentPage->post_title. '</a>'.
                        '<div style="">' . 
                            $displaytext .
                            ($instance['morelink'] == 1 ? '&nbsp;<a href="' . $currentPage->guid . '">' . $instance['morelinktext'] . '</a>' : '') .
                        '</div>'.
                    '</div>'.
                '</li>'; 
                
        return "ok";
    }

    function plugin_action_links( $links, $file ) {
    static $this_plugin;
    
    if( empty($this_plugin) )
      $this_plugin = plugin_basename(__FILE__);

    if ( $file == $this_plugin )
      $links[] = '<a href="' . admin_url( 'widgets.php' ) . '">Widgets</a>';

    return $links;
  }

}


add_action('widgets_init', create_function('', 'return register_widget("A_Sub_Site_Teaser_Widget");'));
add_action('widgets_init', create_function('', 'return add_image_size("a-sub-site-teaser-widget-image", 200, 200);'));
add_action('init', 'load_languages_init');

add_action('wp_enqueue_scripts', 'A_Sub_Site_Teaser_Scripts');
function A_Sub_Site_Teaser_Scripts() {
    //import css
    if(@file_exists(TEMPLATEPATH.'/a-sub-site-teaser-widget.css')) {
        wp_enqueue_style('a-sub-site-teaser-widget', get_stylesheet_directory_uri().'/a-sub-site-teaser-widget.css', false, '0.50', 'all');
    } else {
        wp_enqueue_style('a-sub-site-teaser-widget', plugins_url('a-sub-site-teaser-widget/a-sub-site-teaser-widget.css'), false, '0.50', 'all');
    }   
}
  
?>