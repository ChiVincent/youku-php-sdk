<?php

namespace Chivincent\Youku;

use stdClass;
use Exception;

class Uploader
{
    const ACCESS_TOKEN_URL = 'https://api.youku.com/oauth2/token';
    const UPLOAD_TOKEN_URL = 'https://api.youku.com/uploads/create.json';
    const UPLOAD_COMMIT_URL = 'https://api.youku.com/uploads/commit.json';
    const VERSION_UPDATE_URL = 'http://api.youku.com/sdk/version_update';
    const REFRESH_FILE = 'refresh.txt';

    private $clientId;
    private $clientSecret;
    private $accessToken;
    private $uploadToken;
    private $uploadServerIp;
    private $refreshToken;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    private function getUploadToken(array $uploadInfo): stdClass
    {
        $basic = [
            'client_id' => $this->clientId,
            'access_token' => $this->accessToken,
        ];

        $params = array_merge($basic, $uploadInfo);
        try {
            $result = json_decode(Http::get(self::UPLOAD_TOKEN_URL, $params));

            if (isset($result->error) && $result->error->code !== 1009) {
                throw new UploadException($result->error->description, $result->error->code);
            }

            return $result;
        } catch (UploadException $e) {
            die($e->getError());
        }
    }

    private function uploadCreate(string $fileName): stdClass
    {
        $fileSize = filesize($fileName);
        $url = "http://$this->uploadServerIp/gupload/create_file";
        $param = [
            'upload_token' => $this->uploadToken,
            'file_size' => $fileSize,
            'slice_length' => 1024,
            'ext' => $this->getFileExt($fileName),
        ];

        try {
            $result = json_decode(Http::post($url, $param));

            if (isset($result->error)) {
                throw new UploadException($result->error->description, $result->error->code);
            }

            return $result;
        } catch (UploadException $e) {
            die($e->getError());
        }
    }

    private function createSlice(): stdClass
    {
        $url = "http://$this->uploadServerIp/gupload/new_slice";
        $param = [
            'upload_token' => $this->uploadToken,
        ];

        try {
            $result = json_decode(Http::get($url, $param));

            if (isset($result->error)) {
                throw new UploadException($result->error->description, $result->error->code);
            }

            return $result;
        } catch (UploadException $e) {
            die($e->getError());
        }
    }

    private function uploadSlice(string $sliceTaskId, int $offset, int $length, string $fileName): stdClass
    {
        $url = "http://$this->uploadServerIp/gupload/upload_slice";
        $data = $this->readVideoFile($fileName, $offset, $length);
        $param = [
            'upload_token' => $this->uploadToken,
            'slice_task_id' => $sliceTaskId,
            'offset' => $offset,
            'length' => $length,
            'crc' => dechex(crc32($data)),
            'hash' => bin2hex(md5($data, true))
        ];

        try {
            $result = Http::doPostRequest($url, $param, $data);

            if (isset($result->error)) {
                throw new UploadException($result->error->description, $result->error->code);
            }

            return $result;
        } catch (UploadException $e) {
            die($e->getError());
        }
    }

    private function getFileExt(string $fileName): string
    {
        return pathinfo($fileName)['extension'];
    }

    private function readVideoFile(string $fileName, int $offset, int $length): string
    {
        try {
            $handle = fopen($fileName, 'rb');
            if (!$handle) {
                throw new Exception('Could not open the file!');
            }
            $data = stream_get_contents($handle, $length, $offset);
            fclose($handle);
            return $data;
        } catch (Exception $e) {
            die("Error (Fiile: {$e->getFile()}, line: {$e->getLine()}): {$e->getMessage()}");
        }
    }

    private function commit(string $uploadServerIp)
    {
        $param = [
            'access_token' => $this->accessToken,
            'client_id' => $this->clientId,
            'upload_token' => $this->uploadToken,
            'upload_server_ip' => $this->uploadServerIp,
        ];

        try {
            $result = json_decode(Http::get(self::UPLOAD_COMMIT_URL, $param));

            if (isset($result->error)) {
                throw new UploadException($result->error->description, $result->error->code);
            }

            return $result;
        } catch (UploadException $e) {
            die($e->getError());
        }
    }

    private function versionUpdate(string $verlog)
    {
        $file = fopen($verlog, 'r');

        if (!$file) {
            die("Could not open $verlog!");
        }

        $version = trim(fgets($file));
        echo "Your current sdk version is: $version\n";
        $param = [
            'client_id' => $this->clientId,
            'version' => $version,
            'type' => 'php',
        ];
        Http::get(self::VERSION_UPDATE_URL, $param);
        fclose($file);
    }

    private function check(): stdClass
    {
        $url = "http://{$this->uploadServerIp}/gupload/check";
        $param = [
            'upload_token' => $this->uploadToken,
        ];

        try {
            $result = json_decode(Http::get($url, $param));

            if (isset($result->error)) {
                throw new UploadException($result->error->description, $result->error->code);
            }

            return $result;
        } catch (UploadException $e) {
            die($e->getError());
        }
    }

    private function refreshToken()
    {
        $param = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
        ];

        try {
            $result = json_decode(Http::post(self::ACCESS_TOKEN_URL, $param));

            if (isset($result->error)) {
                throw new UploadException($result->error->description, $result->error->code);
            }

            return $result;
        } catch (UploadException $e) {
            die($e->getError());
        }
    }

    private function readRefreshFile(string $refreshFile)
    {
        $file = fopen($refreshFile, 'r');
        if ($file) {
            $refreshInfo = json_decode(trim(fgets($file)));
            $this->accessToken = $refreshInfo->access_token ?? '';
            $this->refreshToken = $refreshInfo->refresh_token ?? '';
            fclose($file);
        }
    }

    private function writeRefreshFile(string $refreshFile,  $refreshJsonResult)
    {
        $file = fopen($refreshFile, 'w');
        if (!$file) {
            die("Could not open $refreshFile!");
        }
        $refreshInfo = json_encode($refreshJsonResult);
        $fw = fwrite($file, $refreshInfo);
        fclose($file);
        if (!$fw) {
            die("Write refresh file failed!");
        }
    }

    public function upload(bool $uploadProcess = true, array $params = [], array $uploadInfo = [])
    {
        if (isset($params['access_token']) && !empty($params['access_token'])) {
            $this->accessToken = $params['access_token'];
            if (isset($params['refresh_token']) && !empty($params['refresh_token'])) {
                $this->refreshToken = $params['refresh_token'];
            }
            $this->readRefreshFile(self::REFRESH_FILE);
        } else {
            echo 'Only applys to the client of partner level!';
            $result = $this->getAccessToken($params);
            if (isset($result->access_token)) {
                $this->accessToken = $result->access_token;
            }
        }

        $uploadResult = $this->getUploadToken($uploadInfo);

        if (isset($uploadResult->error) && $uploadResult->error->code === 1009 && !empty($this->refreshToken)) {
            $refreshResult = $this->refreshToken();
            $this->accessToken = $refreshResult->access_token;
            $this->refreshToken = $refreshResult->refresh_token;
            $this->writeRefreshFile(self::REFRESH_FILE, $refreshResult);
            $uploadResult = $this->getUploadToken($uploadInfo);
        }

        if (!isset($uploadResult->upload_token)) {
            die('Canont get upload token by this access token and refresh token');
        }

        $this->uploadToken = $uploadResult->upload_token;
        $fileName = $uploadInfo['file_name'];
        $this->uploadServerIp = gethostbyname($uploadResult->upload_server_uri);
        $uploadCreate = $this->uploadCreate($fileName);

        echo "Start Uploading ...\n";
        $finish = false;
        $transferred = 0;

        $uploadSlice = $this->createSlice();
        $sliceId = $uploadSlice->slice_task_id;
        $offset = $uploadSlice->offset;
        $length = $uploadSlice->length;
        $uploadServerIp = '';
        do {
            $uploadSlice = $this->uploadSlice($sliceId, $offset, $length, $fileName);
            $sliceId = $uploadSlice->slice_task_id;
            $offset = $uploadSlice->offset;
            $length = $uploadSlice->length;
            $transferred = (int) round($uploadSlice->transferred / $uploadInfo['file_size'] * 100);

            if ($sliceId === 0) {
                do {
                    $checkResult = $this->check();
                    if (isset($checkResult->status)) {
                        $finish = $checkResult->finished;
                        if ($checkResult->status === 1) {
                            $uploadServerIp = $checkResult->upload_server_ip;
                            $transferred = 100;
                            break;
                        } else if ($checkResult->status === 2 || $checkResult->status === 3) {
                            $transferred = $checkResult->confirmed_percent;
                        }
                    }
                } while(1);
            }

            if ($uploadProcess) {
                echo "Upload progress: {$transferred}%\n";
            }
        } while(!$finish);

        if ($finish) {
            $commitResult = $this->commit($uploadServerIp);
            echo "Uploading success!\n";
            if (isset($commitResult->video_id)) {
                echo "videoid: {$commitResult->video_id}\n";
            }
        }

    }
}
