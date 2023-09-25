<?php
class ewptshpFsNull {
    public function is_plan() {
        return true;
    }
}
if ( !function_exists( 'ewptshp_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ewptshp_fs()
    {
        global  $ewptshp_fs ;
        
        if ( !isset( $ewptshp_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $ewptshp_fs = new ewptshpFsNull();
        }
        
        return $ewptshp_fs;
    }
    
    // Init Freemius.
    ewptshp_fs();
    // Signal that SDK was initiated.
    do_action( 'ewptshp_fs_loaded' );
}
