<?php

use MediaWiki\MediaWikiServices;

class StripExifGPSDataHooks {
    public static function onUploadComplete( UploadBase $upload_base ) {
    	$file = $upload_base->getLocalFile();
    	if ( !$file || !$file->exists() ) {
            return true;
        }
        $path = $file->getLocalRefPath();

        if ( !$path || !file_exists( $path ) ) {
            return true;
        }

        $mime = mime_content_type( $path );
        $supported = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/heic',
            'image/heif',
            'image/tiff',
        ];

        if ( !in_array( strtolower( $mime ), $supported ) ) {
            return true;
        }

        // exiftool must be installed - apt install libimage-exiftool-perl
        $cmd = "exiftool -overwrite_original -gps:all= " . escapeshellarg( $path );
        exec( $cmd, $output, $ret );
    	
        if ($ret === 0) {
    	    //Remove metadata from displaying on File: page
    	    $file->upgradeRow();
    	}
    	
        return true;
    }
}

