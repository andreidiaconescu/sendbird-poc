<?php

$sendbirdApiToken = '51892ab192ab9c98833f496272393b76913a540f';

$task = isset($_GET['task']) ? $_GET['task'] : null;

switch ($task) {
    case 'createSendbirdAccount':

    // group channels
    case 'createSendbirdGroupChannel':
    case 'listSendbirdGroupChannels':
    case 'sendSendbirdMessageToGroupChannel':
    case 'listSendbirdMessagesOfGroupChannel':
    case 'inviteUserToGroupChannel':
    case 'listMembersOfGroupChannel':

    // open channels
    case 'createSendbirdOpenChannel':
    {
        $task($sendbirdApiToken);
        break;
    }
    default: {
        echo '<br>Task ' . $task . ' not found';
    }
}

/**
 * Create a SendBird user account via Platform API when your user signs up your service.
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=createSendbirdAccount&user_id=upcall_usr_1000&nickname=nick_upcall_usr_1000&profile_url=url_upcall_usr_1000&issue_access_token=true
 */
function createSendbirdAccount($sendbirdApiToken)
{
    echo '<br>Executing task createSendbirdAccount';

    $userId = $_GET['user_id'];
    $nickname = $_GET['nickname'];
    $profileUrl = $_GET['profile_url'];
    $issueAccessToken = $_GET['issue_access_token'];

    $sendbirdParams = [
        'user_id'            => $userId,
        'nickname'           => $nickname,
        'profile_url'        => $profileUrl,
        'issue_access_token' => $issueAccessToken ? true : false
    ];
    $sendbirdUrl = 'https://api.sendbird.com/v3/users';

    $sendBirdResponse = curlPost(
        $sendbirdUrl,
        json_encode($sendbirdParams),
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ],
        true
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}

/**
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=createSendbirdGroupChannel&name=upcall_grp_channel_1000&cover_url=upcall_grp_channel_1000_cover_url&data=upcall_grp_channel_1000_data&user_ids=upcall_usr_1000,upcall_usr_2000&is_distinct=true
 */
function createSendbirdGroupChannel($sendbirdApiToken)
{
    echo '<br>Executing task createSendbirdGroupChannel';

    $name = $_GET['name'];
    $coverUrl = $_GET['cover_url'];
    $data = $_GET['data'];
    $userIds = isset($_GET['user_ids']) ? $_GET['user_ids'] : null;
    $isDistinct = $_GET['is_distinct'];

    $sendbirdParams = [
        'name'        => $name,
        'cover_url'   => $coverUrl,
        'data'        => $data,
        'is_distinct' => $isDistinct,
    ];
    if ($userIds) {
        $sendbirdParams['user_ids'] = explode(',', $userIds);
    }

    $sendbirdUrl = 'https://api.sendbird.com/v3/group_channels';

    $sendBirdResponse = curlPost(
        $sendbirdUrl,
        json_encode($sendbirdParams),
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ],
        true
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}

/**
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=createSendbirdGroupChannel&name=upcall_grp_channel_1000&cover_url=upcall_grp_channel_1000_cover_url&data=upcall_grp_channel_1000_data&user_ids=upcall_usr_1000,upcall_usr_2000&is_distinct=true
 */
function listSendbirdGroupChannels($sendbirdApiToken)
{
    echo '<br>Executing task listSendbirdGroupChannels';

    $sendbirdParams = [
        'limit'      => 100,
        'order'      => 'chronological',
        'member'     => 'true',
        'show_empty' => 'true'
    ];

    $urlQueryString = http_build_query($sendbirdParams);
    $sendbirdUrl = 'https://api.sendbird.com/v3/group_channels?' . $urlQueryString;

    $sendBirdResponse = curlGet(
        $sendbirdUrl,
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ]
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}

/**
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=sendSendbirdMessageToGroupChannel&channelUrlParticle=sendbird_group_channel_21658750_5e0c1400cf1795e38be8abfaefba21975a28fd20
 */
function sendSendbirdMessageToGroupChannel($sendbirdApiToken)
{
    echo '<br>Executing task sendSendbirdMessageToGroupChannel';

    $channelUrlParticle = $_GET['channelUrlParticle'];

    $sendbirdParams = [
        'message_type' => 'MESG',
        'user_id'      => 'upcall_usr_2000',
        'message'      => 'test message from server ' . date('c', time()),
        'data'         => 'some data',
        'mark_as_read' => 'true'
    ];

    $sendbirdUrl = 'https://api.sendbird.com/v3/group_channels/' . $channelUrlParticle . '/messages';

    $sendBirdResponse = curlPost(
        $sendbirdUrl,
        json_encode($sendbirdParams),
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ],
        true
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}


/**
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=listSendbirdMessagesOfGroupChannel&channelUrlParticle=sendbird_group_channel_21658750_4d7704df928c6c71bed0dcd54c248de88a76dbe5
 */
function listSendbirdMessagesOfGroupChannel($sendbirdApiToken)
{
    echo '<br>Executing task listSendbirdMessagesOfGroupChannel';

    $channelUrlParticle = $_GET['channelUrlParticle'];

    $sendbirdParams = [
        'message_ts' => '0',
        'prev_limit' => '200',
        'next_limit' => '200',
        'include'    => 'true',
        'reverse'    => 'true'
    ];

    $urlQueryString = http_build_query($sendbirdParams);
    $sendbirdUrl = 'https://api.sendbird.com/v3/group_channels/' . $channelUrlParticle . '/messages?'.$urlQueryString;

    $sendBirdResponse = curlGet(
        $sendbirdUrl,
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ]
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}


/**
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=inviteUserToGroupChannel&channel_url=sendbird_group_channel_21658750_5e0c1400cf1795e38be8abfaefba21975a28fd20&user_ids=upcall_usr_1000
 */
function inviteUserToGroupChannel($sendbirdApiToken)
{
    echo '<br>Executing task inviteUserToGroupChannel';

    $channelUrl = $_GET['channel_url'];
    $userIds = $_GET['user_ids'];

    $sendbirdParams = [
        'user_ids'    => explode(',', $userIds)
    ];


    $sendbirdUrl = 'https://api.sendbird.com/v3/group_channels/'.$channelUrl.'/invite';

    $sendBirdResponse = curlPost(
        $sendbirdUrl,
        json_encode($sendbirdParams),
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ],
        true
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}


/**
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=listMembersOfGroupChannel&channelUrl=sendbird_group_channel_21658750_5e0c1400cf1795e38be8abfaefba21975a28fd20
 */
function listMembersOfGroupChannel($sendbirdApiToken)
{
    echo '<br>Executing task listSendbirdMessagesOfGroupChannel';

    $channelUrl = $_GET['channelUrl'];

    $sendbirdParams = [
        'limit' => '100'
    ];

    $urlQueryString = http_build_query($sendbirdParams);
    $sendbirdUrl = 'https://api.sendbird.com/v3/group_channels/'.$channelUrl.'/members?'.$urlQueryString;

    $sendBirdResponse = curlGet(
        $sendbirdUrl,
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ]
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}



/**
 * Example url to call this function (you can use localhost)
 * http://sendbird-poc.me/server.php?task=createSendbirdOpenChannel&name=upcall_open_channel_1000&cover_url=upcall_open_channel_1000_cover_url&data=upcall_open_channel_1000_data&channel_url=upcall_open_channel_1000_channel_url
 */
function createSendbirdOpenChannel($sendbirdApiToken)
{
    echo '<br>Executing task createSendbirdOpenChannel';

    $name = $_GET['name'];
    $coverUrl = $_GET['cover_url'];
    $channelUrl = $_GET['channel_url'];
    $data = $_GET['data'];

    $sendbirdParams = [
        'name'          => $name,
        'cover_url'     => $coverUrl,
        'channel_url'   => $channelUrl,
        'data'          => $data
    ];


    $sendbirdUrl = 'https://api.sendbird.com/v3/open_channels';

    $sendBirdResponse = curlPost(
        $sendbirdUrl,
        json_encode($sendbirdParams),
        [
            'Content-Type: application/json, charset=utf8',
            'Api-Token: ' . $sendbirdApiToken
        ],
        true
    );

    echo '<br>';
    echo '$sendBirdResponse: <pre>';
    var_dump($sendBirdResponse);
    echo '</pre>';
}

function curlPost($url, $stringPostParams, array $extraHeaders = [], $customPost = false)
{
    $ch = curl_init();    // initialize curl handle
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 60s
    if ($customPost) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    } else {
        // set classical POST method, application/x-www-form-urlencoded kind, most commonly used by HTML forms
        curl_setopt($ch, CURLOPT_POST, 1);
    }

    curl_setopt($ch, CURLOPT_POSTFIELDS, $stringPostParams); // add POST fields

    if ($extraHeaders) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $extraHeaders);
    }

    $responseContent = curl_exec($ch); // run the whole process
    $requestResultInfo = curl_getinfo($ch);
    curl_close($ch);


    return $responseContent;
}

function curlGet($url, array $extraHeaders = [])
{
    $ch = curl_init();    // initialize curl handle
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 60s
    curl_setopt($ch, CURLOPT_HTTPGET, true);

    if ($extraHeaders) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $extraHeaders);
    }

    $responseContent = curl_exec($ch); // run the whole process
    $requestResultInfo = curl_getinfo($ch);
    curl_close($ch);

    return $responseContent;
}
