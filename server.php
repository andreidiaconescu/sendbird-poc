<?php

$sendbirdApiToken = '51892ab192ab9c98833f496272393b76913a540f';

$task = isset($_GET['task']) ? $_GET['task'] : null;

switch ($task) {
    case 'createSendbirdAccount':
    case 'createSendbirdGroupChannel':
    case 'listSendbirdGroupChannels': {
        $task($sendbirdApiToken);
        break;
    }
    default: {
        echo '<br>Task ' . $task . ' not found';
    }
}

/**
 * Create a SendBird user account via Platform API when your user signs up your service.
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
 * http://sendbird-poc.me/server.php?task=createSendbirdGroupChannel&name=upcall_grp_channel_1000&cover_url=upcall_grp_channel_1000_cover_url&data=upcall_grp_channel_1000_data&user_ids=upcall_usr_1000,upcall_usr_2000&is_distinct=true
 */
function createSendbirdGroupChannel($sendbirdApiToken)
{
    echo '<br>Executing task createSendbirdGroupChannel';

    $name = $_GET['name'];
    $coverUrl = $_GET['cover_url'];
    $data = $_GET['data'];
    $userIds = $_GET['user_ids'];
    $isDistinct = $_GET['is_distinct'];

    $sendbirdParams = [
        'name'        => $name,
        'cover_url'   => $coverUrl,
        'data'        => $data,
        'user_ids'    => explode(',', $userIds),
        'is_distinct' => $isDistinct,
    ];


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
