<?php

namespace Chivincent\Youku\Api;

use Chivincent\Youku\Api\Response\StsInf;
use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;
use Chivincent\Youku\Api\Response\Check;
use Chivincent\Youku\Api\Response\Error;
use Chivincent\Youku\Api\Response\Cancel;
use Chivincent\Youku\Api\Response\Commit;
use Chivincent\Youku\Api\Response\Create;
use GuzzleHttp\Exception\ClientException;
use Chivincent\Youku\Api\Response\NewSlice;
use Chivincent\Youku\Api\Response\CreateFile;
use Chivincent\Youku\Api\Response\UploadSlice;
use Chivincent\Youku\Api\Response\RefreshToken;
use Chivincent\Youku\Exception\UploadException;

class Api
{
    const REFRESH_TOKEN_URL = 'https://api.youku.com/oauth2/token.json';
    const CREATE_URL = 'https://api.youku.com/uploads/create.json';
    const CREATE_FILE_URL = 'http://%s/gupload/create_file';
    const NEW_SLICE_URL = 'http://%s/gupload/new_slice';
    const UPLOAD_SLICE_URL = 'http://%s/gupload/upload_slice';
    const CHECK_URL = 'http://%s/gupload/check';
    const COMMIT_URL = 'https://api.youku.com/uploads/commit.json';
    const CANCEL_URL = 'https://api.youku.com/uploads/cancel.json';
    const GET_STS_INF_URL = 'https://api.youku.com/uploads/get_sts_inf.json';

    /**
     * @var Client
     */
    protected $client;

    /**
     * Api constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @api      POST https://api.youku.com/oauth2/token.json
     *
     * @apiParam string   client_id
     * @apiParam string   grant_type
     * @apiParam string   refresh_token
     *
     * @apiReturn string  access_token
     * @apiReturn int     expires_in
     * @apiReturn string  refresh_token
     * @apiReturn string  token_type
     *
     * @param     string $clientId
     * @param     string $grantType
     * @param     string $refreshToken
     * @return    RefreshToken
     * @throws    UploadException
     */
    public function refreshToken(string $clientId, string $grantType, string $refreshToken): RefreshToken
    {
        try {
            $response = $this->client->post(self::REFRESH_TOKEN_URL, [
                'form_params' => [
                    'client_id' => $clientId,
                    'grant_type' => $grantType,
                    'refresh_token' => $refreshToken,
                ],
            ]);

            return RefreshToken::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    /**
     * @api      GET https://api.youku.com/uploads/create.json
     *
     * @apiParam string   client_id
     * @apiParam string   access_token
     * @apiParam string   title
     * @apiParam string   tags
     * @apiParam ?string  category
     * @apiParam ?string  copyright_type=original
     * @apiParam ?string  public_type=all
     * @apiParam ?string  watch_password
     * @apiParam string   description
     * @apiParam string   file_name
     * @apiParam string   file_md5
     * @apiParam int      file_size
     * @apiParam ?int     isWeb
     * @apiParam ?int     deshake
     *
     * @apiReturn string  upload_token
     * @apiReturn string  video_id
     * @apiReturn string  upload_server_uri
     *
     * @param     string $clientId
     * @param     string $accessToken
     * @param     string $title
     * @param     string $tags
     * @param     string $description
     * @param     string $fileName
     * @param     string $fileMd5
     * @param     string $fileSize
     * @param     string $category = 'Other'
     * @param     string $thumbnail = 'Other'
     * @param     string $copyrightType = 'original'
     * @param     string $publicType = 'all'
     * @param     null|string $watchPassword = null
     * @param     int    $isWeb = 0
     * @param     int    $isNew = 0
     * @param     int    $deshake = 0
     * @return    Create
     * @throws    UploadException
     */
    public function create(
        string $clientId,
        string $accessToken,
        string $title,
        string $tags,
        string $description,
        string $fileName,
        string $fileMd5,
        string $fileSize,
        string $category = 'Other',
        ?string $thumbnail = null,
        string $copyrightType = 'original',
        string $publicType = 'all',
        ?string $watchPassword = null,
        int    $isWeb = 0,
        int    $isNew = 0,
        int    $deshake = 0
    ): Create {
        $queries = [
            'client_id' => $clientId,
            'access_token' => $accessToken,
            'title' => $title,
            'tags' => $tags,
            'description' => $description,
            'file_name' => $fileName,
            'file_md5' => $fileMd5,
            'file_size' => $fileSize,
            'category' => $category,
            'copyright_type' => $copyrightType,
            'public_type' => $publicType,
            'isweb' => $isWeb,
            'isnew' => $isNew,
            'deshake' => $deshake,
        ];

        if ($watchPassword) {
            $queries['watch_password'] = $watchPassword;
        }

        if ($thumbnail) {
            $queries['thumbnail'] = $thumbnail;
        }

        try {
            $response = $this->client->get(self::CREATE_URL, [
                'query' => $queries,
            ]);

            return Create::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    /**
     * @api      POST http://gX.upload.youku.com/gupload/create_file
     *
     * @apiParam string upload_token
     * @apiParam int    file_size
     * @apiParam string ext
     * @apiParam int   slice_length=2048
     *
     * @apiReturn object error
     * @apiReturn int    code
     * @apiReturn string type
     * @apiReturn string description
     *
     * @param     string $ip
     * @param     string $uploadToken
     * @param     int    $fileSize
     * @param     string $ext
     * @param     int    $sliceLength = 2048
     * @return    CreateFile
     * @throws    UploadException
     */
    public function createFile(string $ip, string $uploadToken, int $fileSize, string $ext, int $sliceLength = 10485760): CreateFile
    {
        try {
            $response = $this->client->post(sprintf(self::CREATE_FILE_URL, $ip), [
                'form_params' => [
                    'upload_token' => $uploadToken,
                    'file_size' => $fileSize,
                    'ext' => $ext,
                    'slice_length' => $sliceLength,
                ],
            ]);

            return CreateFile::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    /**
     * @api      GET http://gX.upload.youku.com/gupload/new_slice
     *
     * @apiParam string upload_token
     *
     * When Error:
     * @apiReturn object error
     * @apiReturn int    code
     * @apiReturn string type
     * @apiReturn string description
     *
     * When Success:
     * @apiReturn int    slice_task_id
     * @apiReturn int64  offset
     * @apiReturn int    length
     * @apiReturn int64  transferred
     * @apiReturn bool   finished
     *
     * @param     string $ip
     * @param     string $uploadToken
     * @return    NewSlice
     * @throws    UploadException
     */
    public function newSlice(string $ip, string $uploadToken): NewSlice
    {
        try {
            $response = $this->client->get(sprintf(self::NEW_SLICE_URL, $ip), [
                'query' => [
                    'upload_token' => $uploadToken,
                ],
            ]);

            return NewSlice::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    /**
     * @api      POST http://gX.upload.youku.com/gupload/upload_slice
     *
     * @apiParam string  upload_token
     * @apiParam string  slice_task_id
     * @apiParam int64   offset
     * @apiParam int     length
     * @apiParam binary  data, transferred by body, without key.
     * @apiParam ?string crc=''
     * @apiParam ?string hash=''
     *
     * When Error:
     * @apiReturn object error
     * @apiReturn int    code
     * @apiReturn string type
     * @apiReturn string description
     *
     * When Success:
     * @apiReturn int    slice_task_id
     * @apiReturn int64  offset
     * @apiReturn int    length
     * @apiReturn int64  transferred
     * @apiReturn bool   finished
     *
     * @param     string $ip
     * @param     string $uploadToken
     * @param     string $sliceTaskId
     * @param     int    $offset
     * @param     int    $length
     * @param     string|resource|StreamInterface $data
     * @param     null|string $crc
     * @param     null|string $hash
     * @return    UploadSlice
     * @throws    UploadException
     */
    public function uploadSlice(
        string $ip,
        string $uploadToken,
        string $sliceTaskId,
        int    $offset,
        int    $length,
        $data,
        string $crc = '',
        string $hash = ''
    ): UploadSlice {
        try {
            $response = $this->client->post(sprintf(self::UPLOAD_SLICE_URL, $ip), [
                'query' => [
                    'upload_token' => $uploadToken,
                    'slice_task_id' => $sliceTaskId,
                    'offset' => $offset,
                    'length' => $length,
                    'crc' => $crc,
                    'hash' => $hash,
                ],
                'body' => $data,
            ]);

            return UploadSlice::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    /**
     * @api      GET http://gX.upload.youku.com/gupload/check
     *
     * @apiParam string upload_token
     *
     * When Error:
     * @apiReturn obejct error
     * @apiReturn int    code
     * @apiReturn string type
     * @apiReturn string description
     *
     * When Success:
     * @apiReturn int     status
     * @apiReturn ?int    transferred_percent
     * @apiReturn ?int    confirmed_percent
     * @apiReturn ?int    empty_tasks
     * @apiReturn bool    finished
     * @apiReturn string  upload_server_ip
     *
     * @param     string $ip
     * @param     string $uploadToken
     * @return    Check
     * @throws    UploadException
     */
    public function check(string $ip, string $uploadToken): Check
    {
        try {
            $response = $this->client->get(sprintf(self::CHECK_URL, $ip), [
                'query' => [
                    'upload_token' => $uploadToken,
                ],
            ]);

            return Check::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    /**
     * @api      POST http://api.youku.com/gupload/commit.json
     *
     * @apiParam string  access_token
     * @apiParam string  client_id
     * @apiParam string  upload_token
     * @apiParam ?string upload_server_ip=''
     *
     * When Error:
     * @apiReturn object error
     * @apiReturn int    code
     * @apiReturn string type
     * @apiReturn string description
     *
     * When Success:
     * @apiReturn string video_id
     *
     * @param     string $accessToken
     * @param     string $clientId
     * @param     string $uploadToken
     * @param     null|string $uploadServerIp = null
     * @param     null|string $uploadServerName = null
     * @return    Commit
     * @throws    UploadException
     */
    public function commit(
        string $accessToken,
        string $clientId,
        string $uploadToken,
        ?string $uploadServerIp = null,
        ?string $uploadServerName = null
    ): Commit {
        try {
            $params = [
                'access_token' => $accessToken,
                'client_id' => $clientId,
                'upload_token' => $uploadToken,
            ];

            if ($uploadServerIp) {
                $params['upload_server_ip'] = $uploadServerIp;
            }

            if ($uploadServerName) {
                $params['upload_server_name'] = $uploadServerName;
            }

            $response = $this->client->post(self::COMMIT_URL, [
                'form_params' => $params,
            ]);

            return Commit::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    /**
     * @api      GET http://api.youku.com/uploads/cancel.json
     *
     * @apiParam string  access_token
     * @apiParam string  client_id
     * @apiParam string  upload_token
     * @apiParam ?string upload_server_ip=''
     *
     * When Error:
     * @apiReturn object error
     * @apiReturn int    code
     * @apiReturn string type
     * @apiReturn string description
     *
     * When Success:
     * @apiReturn string upload_token
     *
     * @param     string $accessToken
     * @param     string $clientId
     * @param     string $uploadToken
     * @param     string $uploadServerIp = ''
     * @return    Cancel
     * @throws    UploadException
     */
    public function cancel(
        string $accessToken,
        string $clientId,
        string $uploadToken,
        string $uploadServerIp = ''
    ): Cancel {
        try {
            $response = $this->client->get(self::CANCEL_URL, [
                'query' => [
                    'access_token' => $accessToken,
                    'client_id' => $clientId,
                    'upload_token' => $uploadToken,
                    'upload_server_ip' => $uploadServerIp,
                ],
            ]);

            return Cancel::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }

    public function getStsInf(
        string $clientId,
        string $accessToken,
        string $uploadToken,
        string $ossBucket,
        string $ossObject
    ) {
        try {
            $response = $this->client->post(self::GET_STS_INF_URL, [
                'query' => [
                    'client_id' => $clientId,
                    'access_token' => $accessToken,
                    'upload_token' => $uploadToken,
                    'oss_bucket' => $ossBucket,
                    'oss_object' => $ossObject,
                ],
            ]);

            return StsInf::json($response->getBody()->getContents());
        } catch (ClientException $exception) {
            throw $exception->hasResponse()
                ? new UploadException(Error::json($exception->getResponse()->getBody()->getContents()), $exception)
                : new UploadException(null, $exception);
        }
    }
}
