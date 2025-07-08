# StripExifGPSData

A MediaWiki extension that strips out Exif GPS data from images during the
upload process. This effectively removes any worry that users are uploading
images that might leak their current or home location as many cellphones or
cameras automatically attach GPS coordinates to photos.

Setting `$wgShowEXIF = false;` in LocalSettings.php will disable metadata
from being displayed in the File: page. However, the data still exists in
the image itself, and that's where this extension comes in.

## Installation

Requires MediaWiki 1.35 or higher.

Install the third-party tool [exiftool](https://exiftool.org/) on the OS and
ensure "exiftool" is a valid command. This can be installed on Linux with:
```
apt install libimage-exiftool-perl
```

Add the following to LocalSettings.php:

```php
wfLoadExtension( "StripExifGPSData" );
```

## Configuration

You can optionally edit StripExifGPSData.php to change the line containing:

`$cmd = "exiftool -overwrite_original -gps:all= " . escapeshellarg( $path );`

to strip any desired metadata. For instance, to strip all metadata:

`$cmd = "exiftool -overwrite_original -all= " . escapeshellarg( $path );`

Documentation can be found on [the exiftool website](https://exiftool.org/).

## Notes
* You are not required to set `$wgShowEXIF = false;` to use this extension.
* Given that the GPS metadata is stripped after the image is uploaded, you
may still see the original metadata-tagged image for some time if you have
caching enabled.
* This extension will not remove any metadata from images already on your wiki. To do this, you can use exiftool as described below.

To remove metadata from a single file: <pre>exiftool -gps:all= -overwrite_original /var/www/YOUR_SITE/wiki/images/a/bc/File_Containing_GPS_Info.jpg</pre>
To remove metadata from all files: <pre>exiftool -csv -filename -gps:GPSLatitude -gps:GPSLongitude /var/www/YOUR_SITE/wiki/images/ -r > ./output.csv</pre>
Open the CSV, sort by GPSLatitude, then copy all filenames with GPS info to a file called input.txt and upload it to the server and run: <pre>tr -d '\r' < input.txt > cleanlist.txt && xargs -a cleanlist.txt exiftool -gps:all= -overwrite_original</pre>
Run <code>php maintenance/refreshImageMetadata.php</code> to update the metadata in the backend.
