# StripExifGPSData

A MediaWiki extension that strips out Exif GPS data from images during the
upload process. This effectively removes any worry that users are uploading
images that might leak their current or home location as many cellphones or
cameras automatically attach GPS coordinates to photos.

Setting `$wgShowEXIF = false;` in LocalSettings.php will disable metadata
from being displayed in the File: page. However, the data still exists in
the image itself, and that's where this extension comes in.

## Installation

Requires MediaWiki 1.35 or higher, as well as the third-party tool [exiftool](https://exiftool.org/),
with the command aliased to "exiftool".

This can be installed on a Linux distribution with the command:
`apt install libimage-exiftool-perl`

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
