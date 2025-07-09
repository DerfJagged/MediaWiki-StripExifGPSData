<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;

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
		$cmd = Shell::command(
	    	'exiftool',
	    	'-gps:all=',
	    	'-overwrite_original',
	    	$path
		)->restrict( Shell::RESTRICT_DEFAULT );
		$result = $cmd->execute();
		$ret = $result->getExitCode();
    	
        if ($ret === 0) {
    	    //Remove GPS metadata from displaying on File: page
    	    $file->upgradeRow();
    	}
    	
        return true;
    }
}
