<?php

function trsc_admin_menu() {
    global $trsc_settings_page;
    $trsc_settings_page = add_submenu_page(
                            'learndash-lms', //The slug name for the parent menu
                            __( 'Students Count', 'learndash-students-count' ), //Page title
                            __( 'Students Count', 'learndash-students-count' ), //Menu title
                            'manage_options', //capability
                            'learndash-students-count', //menu slug 
                            'trsc_admin_page' //function to output the content
                        );
}

add_action( 'admin_menu', 'trsc_admin_menu' );

function trsc_register_plugin_settings() {
    //register our settings
    register_setting( 'trsc-settings-group', 'trsc_singular_text' );
    register_setting( 'trsc-settings-group', 'trsc_singular_hide' );
    register_setting( 'trsc-settings-group', 'trsc_singular_styling' );
    register_setting( 'trsc-settings-group', 'trsc_plural_text' );
    register_setting( 'trsc-settings-group', 'trsc_plural_hide' );
    register_setting( 'trsc-settings-group', 'trsc_plural_styling' );
    register_setting( 'trsc-settings-group', 'trsc_zero_text' );
    register_setting( 'trsc-settings-group', 'trsc_zero_hide' );
    register_setting( 'trsc-settings-group', 'trsc_zero_styling' );
    register_setting( 'trsc-settings-group', 'trsc_grid_show' );
    register_setting( 'trsc-settings-group', 'trsc_single_show' );
    register_setting( 'trsc-settings-group', 'trsc_who_can_see' );
    register_setting( 'trsc-settings-group', 'trsc_count_update' );
    
}
//call register settings function
add_action( 'admin_init', 'trsc_register_plugin_settings' );


function trsc_admin_page() {
?>

<div class="trsc-head-panel">
    <h1><?php esc_html_e( 'Students Count for Learndash', 'learndash-students-count' ); ?></h1>
    <p><?php esc_html_e( 'Show your visitors how many students you have in your LearnDash courses', 'learndash-students-count' ); ?></p>
</div>

<div class="wrap trsc-wrap-grid">

    <form method="post" action="options.php">

        <?php settings_fields( 'trsc-settings-group' ); ?>
        <?php do_settings_sections( 'trsc-settings-group' ); ?>

        <div class="trsc-form-fields">

            <div class="trsc-settings-title">
                <?php esc_html_e( 'Students Count - Settings', 'learndash-students-count' ); ?>
            </div>

            <div class="trsc-form-fields-label">
                <?php esc_html_e( 'Custom text for singular', 'learndash-students-count' ); ?>
            </div>
            <div class="trsc-form-fields-group">
                <input type="text" placeholder="<?php esc_attr_e( 'student enrolled', 'learndash-students-count' ); ?>" class=""
                    value="<?php echo esc_attr( get_option('trsc_singular_text') ); ?>"
                    name="<?php echo esc_attr( 'trsc_singular_text' ); ?>">
                <div class="trsc-form-div-checkbox">
                    <label>
                        <input class="trsc-checkbox" type="checkbox"
                            name="<?php echo esc_attr( 'trsc_singular_hide' ); ?>" value="1"
                            <?php checked(1, get_option('trsc_singular_hide'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Hide', 'learndash-students-count' ); ?>
                        </span>
                    </label>

                    <label>
                        <input class="trsc-checkbox" type="checkbox"
                            name="<?php echo esc_attr( 'trsc_singular_styling' ); ?>" value="1"
                            <?php checked(1, get_option('trsc_singular_styling'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Basic styling', 'learndash-students-count' ); ?>
                        </span>
                    </label>
                </div>
                
            </div>
            <hr>

            <div class="trsc-form-fields-label">
                <?php esc_html_e( 'Custom text for plural', 'learndash-students-count' ); ?>
            </div>
            <div class="trsc-form-fields-group">
                <input type="text" placeholder="<?php esc_attr_e( 'students enrolled', 'learndash-students-count' ); ?>" class=""
                    value="<?php echo esc_attr( get_option('trsc_plural_text') ); ?>"
                    name="<?php echo esc_attr( 'trsc_plural_text' ); ?>">
                <div class="trsc-form-div-checkbox">
                    <label>
                        <input class="trsc-checkbox" type="checkbox"
                            name="<?php echo esc_attr( 'trsc_plural_hide' ); ?>" value="1"
                            <?php checked(1, get_option('trsc_plural_hide'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Hide', 'learndash-students-count' ); ?>
                        </span>
                    </label>
                    <label>
                        <input class="trsc-checkbox" type="checkbox"
                            name="<?php echo esc_attr( 'trsc_plural_styling' ); ?>" value="1"
                            <?php checked(1, get_option('trsc_plural_styling'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Basic styling', 'learndash-students-count' ); ?>
                        </span>
                    </label>
                </div>
            </div>
            <hr>

            <div class="trsc-form-fields-label">
                <?php esc_html_e( 'Custom text for no students', 'learndash-students-count' ); ?>
            </div>
            <div class="trsc-form-fields-group">
                <input type="text" placeholder="No student enrolled yet" class=""
                    value="<?php echo esc_attr( get_option('trsc_zero_text') ); ?>"
                    name="<?php echo esc_attr( 'trsc_zero_text' ); ?>">
                <div class="trsc-form-div-checkbox">
                    <label>
                        <input class="trsc-checkbox" type="checkbox" name="<?php echo esc_attr( 'trsc_zero_hide' ); ?>"
                            value="1" <?php checked(1, get_option('trsc_zero_hide'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Hide', 'learndash-students-count' ); ?>
                        </span>
                    </label>
                    <label>
                        <input class="trsc-checkbox" type="checkbox"
                            name="<?php echo esc_attr( 'trsc_zero_styling' ); ?>" value="1"
                            <?php checked(1, get_option('trsc_zero_styling'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Basic styling', 'learndash-students-count' ); ?>
                        </span>
                    </label>
                </div>
            </div>
            <hr>

            <div class="trsc-form-fields-label">
                <?php esc_html_e( 'Where?', 'learndash-students-count' ); ?>
            </div>
            <div class="trsc-form-fields-group">
                <div class="trsc-form-div-checkbox">
                    <label>
                        <input class="trsc-checkbox" type="checkbox" name="<?php echo esc_attr( 'trsc_grid_show' ); ?>"
                            value="1" <?php checked(1, get_option('trsc_grid_show'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Show on the grid', 'learndash-students-count' ); ?>
                        </span>
                        <?php esc_html_e( '(LearnDash - Course Grid free add-on)', 'learndash-students-count' ); ?>
                    </label>
                    <span class="trsc-form-fields-label-class">
                      <?php esc_html_e( 'Class for additional CSS styling: ', 'learndash-students-count' ); ?><code>trsc-students-count-grid</code>
                    </span>
                </div>

                <div class="trsc-form-div-checkbox">
                    <label>
                        <input class="trsc-checkbox" type="checkbox"
                            name="<?php echo esc_attr( 'trsc_single_show' ); ?>" value="1"
                            <?php checked(1, get_option('trsc_single_show'), true); ?> />
                        <span class="trsc-form-fields-label">
                            <?php esc_html_e( 'Show on the single course page', 'learndash-students-count' ); ?>
                        </span>
                    </label>
                    <span class="trsc-form-fields-label-class">
                      <?php esc_html_e( 'Class for additional CSS styling: ', 'learndash-students-count' ); ?><code>trsc-students-count-single</code>
                    </span>
                </div>
            </div>
            <hr>

            <div class="trsc-form-fields-label">
                <?php esc_html_e( 'Who can see?', 'learndash-students-count' ); ?>
            </div>
            <div class="trsc-form-fields-group">
                <div class="trsc-form-div-select">
                    <label>
                        <select name="<?php echo esc_attr( 'trsc_who_can_see' ); ?>">
                            <option value="trsc_who_all"
                                <?php selected("trsc_who_all", get_option('trsc_who_can_see'), true); ?>>
                                <?php esc_html_e( 'All', 'learndash-students-count' ); ?>
                            </option>
                            <option value="trsc_who_visitors"
                                <?php selected("trsc_who_visitors", get_option('trsc_who_can_see'), true); ?>>
                                <?php esc_html_e( 'Visitors only (non logged)', 'learndash-students-count' ); ?>
                            </option>
                            <option value="trsc_who_logged"
                                <?php selected("trsc_who_logged", get_option('trsc_who_can_see'), true); ?>>
                                <?php esc_html_e( 'Logged users only', 'learndash-students-count' ); ?>
                            </option>
                        </select>
                </div>
            </div>
            <hr>

            <div class="trsc-form-fields-label">
                <?php esc_html_e( 'Count update', 'learndash-students-count' ); ?>
            </div>
            <div class="trsc-form-fields-group">
                <div class="trsc-form-div-select">
                    <label>
                        <select name="<?php echo esc_attr( 'trsc_count_update' ); ?>">
                            <option value=24
                                <?php selected(24, get_option('trsc_count_update'), true); ?>>
                                <?php esc_html_e( 'Every 24 hours', 'learndash-students-count' ); ?>
                            </option>
                            <option value=12
                                <?php selected(12, get_option('trsc_count_update'), true); ?>>
                                <?php esc_html_e( 'Every 12 hours', 'learndash-students-count' ); ?>
                            </option>
                            <option value=6
                                <?php selected(6, get_option('trsc_count_update'), true); ?>>
                                <?php esc_html_e( 'Every 6 hours', 'learndash-students-count' ); ?>
                            </option>
                            <option value=3
                                <?php selected(3, get_option('trsc_count_update'), true); ?>>
                                <?php esc_html_e( 'Every 3 hours', 'learndash-students-count' ); ?>
                            </option>
                            <option value=1
                                <?php selected(1, get_option('trsc_count_update'), true); ?>>
                                <?php esc_html_e( 'Every hour', 'learndash-students-count' ); ?>
                            </option>
                            <option value=0
                                <?php selected(0, get_option('trsc_count_update'), true); ?>>
                                <?php esc_html_e( 'Always (may hurt performance)', 'learndash-students-count' ); ?>
                            </option>
                        </select>
                </div>
            </div>

            <?php submit_button(); ?>

            <div style="float:right; margin-bottom:20px">
              Contact Luis Rock, the author, at 
              <a href="mailto:lurockwp@gmail.com">
                lurockwp@gmail.com
              </a>
            </div>

        </div> <!-- end form fields -->
    </form>

    <div class="trsc-shortcode-block-button">
        <button class="button button-default"
                id="button-toggle-shortcode"
                onclick="toggleShortcodeBlock()"
                >
            <?php esc_html_e( 'Prefer to use shortcode?', 'learndash-students-count' ); ?>
    </button>
    </div>

    <script>
        function toggleShortcodeBlock() {
            var x = document.getElementById("trsc-shortcode-instructions-block");
            var btnShortcode = document.getElementById("button-toggle-shortcode");
            if (x.style.display === "none") {
                x.style.display = "block";
                btnShortcode.innerHTML = "<?php esc_html_e( 'Hide shortcode instruction', 'learndash-students-count' ); ?>";
            } else {
                x.style.display = "none";
                btnShortcode.innerHTML = "<?php esc_html_e( 'Prefer to use shortcode?', 'learndash-students-count' ); ?>";
            }
        }
    </script>

    <div class="trsc-form-fields trsc-shortcode-block" id="trsc-shortcode-instructions-block" style="display:none;">

        <div class="trsc-settings-title">
            <?php esc_html_e( 'Students Count - Shortcode', 'learndash-students-count' ); ?>
        </div>

        <div>
            <p><code>[ld_students_count]</code></p>
            <p>Use it wherever you like in the course page.</p>
            <p> If you want to use it outside the course page, you must provide the course id. Like this:</p>
            <p><code>[ld_students_count course_id='123']</code></p>
            <p>This shortcode has its own parameters that make it operate independently from the plugin's options settings. Here is the full parameters list:</p>

            <table style="width:100%">
                <tr>
                    <th>PARAMETER</th>
                    <th>FUNCTION</th>
                    <th>DEFAULT</th>
                </tr>
                <tr>
                    <td>
                        <strong>course_id</strong>
                    </td>
                    <td>
                        as said above, this defaults to the id of the course in the loop. If you place the shortcode outside the loop, you must provide a valid value, otherwise it will not display anything.
                    </td>
                    <td>
                        current course id (if shortcode is placed in the single course page)
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>class</strong>
                    </td>
                    <td>
                        with this parameter you can define a class to be targeted if you want to apply additional CSS style to the span element that the shortcode outputs.
                    </td>
                    <td>
                        ' '
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>who</strong>
                    </td>
                    <td>
                        define who will be able to see the output generated by the plugin. Allowed values: 
                        <code>'all'</code>, <code>'visitors'</code> or <code>'logged'</code>.
                    </td>
                    <td>
                        <code>'all'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong> number_only</strong>
                    </td>
                    <td>
                        if set to <code>'true'</code>, the shortcode will generate only the number of enrolled students in the course, without any additional text.
                    </td>
                    <td>
                        <code>'false'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>text_singular</strong>
                    </td>
                    <td>
                        custom text (to show right after the number) in case course has only one enrolled student.
                    </td>
                    <td>
                        <code>'student enrolled'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>text_plural</strong>
                    </td>
                    <td>
                        custom text (to show right after the number) in case course has two or more enrolled students.
                    </td>
                    <td>
                        <code>'students enrolled'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>text_zero</strong>
                    </td>
                    <td>
                        custom text (the '0' will not be displayed) in case course has no enrolled students.
                    </td>
                    <td>
                        <code>'No student enrolled yet'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>hide_singular</strong>
                    </td>
                    <td>
                        if set to <code>'true'</code>, nothing will be displayed if the course has only one enrolled student. 
                    </td>
                    <td>
                        <code>'false'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>hide_plural</strong>
                    </td>
                    <td>
                        if set to <code>'true'</code>, nothing will be displayed if the course has more than two or more enrolled students.
                    </td>
                    <td>
                        <code>'false'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>hide_zero</strong>
                    </td>
                    <td>
                        if set to <code>'true'</code>, nothing will be displayed if the course has no enrolled students.
                    </td>
                    <td>
                        <code>'false'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>style_singular</strong>
                    </td>
                    <td>
                        set to <code>'true'</code> in order to apply predefined basic styling do the displayed text.
                    </td>
                    <td>
                        <code>'false'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>style_plural</strong>
                    </td>
                    <td>
                        set to <code>'true'</code> in order to apply predefined basic styling do the displayed text.
                    </td>
                    <td>
                        <code>'false'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>style_zero</strong>
                    </td>
                    <td>
                        set to <code>'true'</code> in order to apply predefined basic styling do the displayed text.
                    </td>
                    <td>
                        <code>'false'</code>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>udate_in_hours</strong>
                    </td>
                    <td>
                        when the plugin counts enrolled students in a course for the first time, it stores the value in the database as a transient; 
                        here you can define how many hours after that the transient will expire (thus making the plugin count enrolled students again). 
                        Allowed values: <code>'1'</code>,<code>'3'</code>, <code>'6'</code>,<code>'12'</code>, <code>'24'</code>, or <code>'0'</code>.<br>
                        * if you set this to <code>'0'</code>, be aware that the plugin will count students all the time; when you have lots of students (congrats!) this can harm your site's performance.    

                    </td>
                    <td>
                        <code>'12'</code>
                    </td>
                </tr>
                
            </table>

            
        </div>

        <div>
            <p>Example:</p>
            <p><code>[ld_students_count class='my-defined-class' text_zero='New!' update_in_hours='3']</code></p>
            <p>In a PHP template:</p>
            <p><code>echo do_shortcode("[ld_students_count class='my-defined-class' text_zero='New!' update_in_hours='3']");</code></p>

        </div>

        <div style="float:right; margin-bottom:20px">
            Contact Luis Rock, the author, at 
            <a href="mailto:lurockwp@gmail.com">
            lurockwp@gmail.com
            </a>
        </div>
    </div>
</div> <!-- end trsc-wrap-grid -->
<?php } ?>