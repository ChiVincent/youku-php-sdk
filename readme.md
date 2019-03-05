#PHP Youku SDK

The youku.com php sdk.

## Installation

```bash
composer require chivincent/youku-uploader
```

## Usage

```php
<?php

use Chivincent\Youku\Uploader;

$file = 'test.mp4';
$uploader = new Uploader($clientId, $clientSecret);

$params = [];
// $params['access_token'] = '';
// $params['refresh_token'] = '';

$uploadInfo = [
    'title' => 'php sdk test', // video title,
    'tags' => 'test', // tags, split by spaces.
    'file_name' => $file,
    'file_md5' => md5_file($file),
    'file_size' => filesize($file),
];

$progress = true; // if true, show the uploading progress
$uploader->upload($progress, $params, $uploadInfo);
```