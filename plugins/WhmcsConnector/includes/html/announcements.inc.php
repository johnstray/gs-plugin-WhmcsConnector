<?php
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * 
 * @package: gs-WhmcsConnector
 * @version: 1.0.0-alpha
 * @author: John Stray <getsimple@johnstray.com>
 */

# Prevent impropper loading of this file. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); } ?>

        <div class="widesec gs_whmcs_ui_ai_container">
            <hr class="gs_whmcs_ui_ai_hline" />
            <div class="gs_whmcs_ui_ai_title-sect">
                <h3 class="gs_whmcs_ui_ai_title">Announcement Import</h3>
                <div class="gs_whmcs_ui_ai_enable">
                    <?php if ( $savedSettings['blogenable'] == "on" ) { ?>
                        <input type="checkbox" id="blogenable" name="blogenable" checked />
                    <?php } else { ?>
                        <input type="checkbox" id="blogenable" name="blogenable" />
                    <?php } ?>
                    <label for="blogenable">Enable Announcement Import</label>
                </div>
                <div class="clear"></div>
            </div>
            <p>GetSimple Blog was detected as an installed plugin. You now have the ability to configure the importation of announcements from WHMCS into GetSimple Blog as new posts. Use the settings below to configure the importation of announcements to blog posts.</p>
            
            <div class="leftsec">
                <p>
                    <label for="apikey">Blog Category</label>
                    <span class="hint">The category announcement posts will be attached to</span>
                    <select class="text" name="blogcategory">
                        <option value="">--- None ---</option>
                        <optgroup label="Blog Categories">
                            <?php echo $BlogImporter->getBlogCategories($savedSettings['blogcategory']); ?>
                        </optgroup>
                    </select>
                </p>
            </div>
            <div class="rightsec">
                <p>
            <label for="blogauthor">Author's Name</label>
            <span class="hint">Name of the post author for all imported announcements</span>
            <input class="text" type="text" name="blogauthor" value="<?php echo $savedSettings['blogauthor']; ?>" placeholder="eg. John Smith or WHMCS Announcements" />
        </p>
            </div>
            <div class="clear"></div>
            
        </div>
        <div class="clear"></div>