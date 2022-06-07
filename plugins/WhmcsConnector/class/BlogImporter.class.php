<?php
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * 
 * @package: gs-WhmcsConnector
 * @version: 1.0.0-alpha
 * @author: John Stray <get-simple@jonstray.com>
 */

# Prevent impropper loading of this class. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }

class WhmcsBlogImporter extends WhmcsConnector {
    
    public function getBlogCategories( string $selected = '' ) : string
    {
        if ( defined('BLOGCATEGORYFILE') ) {
            $categories = getXML(BLOGCATEGORYFILE);
        } else {
            // @TODO: Handle not having a defined category file
            $categories = null;
        }
        
        $category_options = "";
        
        if ( empty($categories) === false && count($categories) > 0 ) {
            foreach ( $categories->category as $category ) {
                $category = (string) $category;
                if ( $category == $selected ) {
                    $category_options .= '<option value="' . $category . '" selected>' . $category . '</option>';
                } else {
                    $category_options .= '<option value="' . $category . '">' . $category . '</option>';
                }
            }
        } else {
            $category_options .= '<option value="">----- No Categories Found -----</option>';
        }
        
        return $category_options;
    }
    
    public function blogExists() : bool
    {
        if ( defined('GSPLUGINPATH') ) {
            if ( file_exists(GSPLUGINPATH . 'gs-blog.php') ) {
                return true;
            }
        }
        return false;
    }
}
