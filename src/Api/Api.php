<?php

namespace Chivincent\Youku\Api;

use Chivincent\Youku\Api\Response\Create;
use Chivincent\Youku\Api\Response\CreateFile;
use Chivincent\Youku\Api\Response\NewSlice;
use Chivincent\Youku\Api\Response\RefreshToken;

class Api
{
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
     */
    public function refreshToken(): RefreshToken
    {
        return RefreshToken::json(json_encode([
            'access_token' => '',
            'expires_in' => 0,
            'refresh_token' => '',
            'token_type' => '',
        ]));
    }

    /**
     * @api      GET https://api.youku.com/uploads/create.json
     *
     * @apiParam string   client_id
     * @apiParam string   access_token
     * @apiParam string   title
     * @apiParam string   tags
     * @apiParam string   category
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
     */
    public function create(): Create
    {
        return Create::json(json_encode([
            'upload_token' => '',
            'video_id' => '',
            'upload_server_uri' => '',
        ]));
    }

    /**
     * @api      POST http://gX.upload.youku.com/gupload/create_file
     *
     * @apiParam string upload_token
     * @apiParam int    file_size
     * @apiParam string ext
     * @apiParam ?int   slice_length=2048
     *
     * @apiReturn object error
     * @apiReturn int    code
     * @apiReturn string type
     * @apiReturn string description
     */
    public function createFile(): CreateFile
    {
        return CreateFile::json('{}');
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
     */
    public function newSlice(): NewSlice
    {
        return NewSlice::json(json_encode([
            'slice_task_id' => 0,
            'offset' => 0,
            'length' => 0,
            'transferred' => 0,
            'finished' => true,
        ]));
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
     */
    public function uploadSlice()
    {

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
     * @apiReturn int    success
     * @apiReturn int    transferred_percent
     * @apiReturn int    confirmed_percent
     * @apiReturn int    empty_tasks
     * @apiReturn bool   finished
     * @apiReturn string upload_server_ip
     */
    public function check()
    {

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
     */
    public function commit()
    {

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
     */
    public function cancel()
    {

    }
}
