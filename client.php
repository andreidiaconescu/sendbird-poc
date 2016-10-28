<?php
$sendbirdApplicationId = '38A89BF0-B085-4F76-981D-FC5695FABC1B';

$userId = 'upcall_usr_1000';
$userAccessToken = 'dc4dae9488be14aca328560f51a870c21af0d1dc';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sendbird POC</title>

    <style type="text/css">
        .output_box_bottom {
            width: 100%;
            height: 300px;
            position: absolute;
            bottom: 0;
            left: 0;
            border: 1px solid black;
            overflow: auto;
        }
    </style>

</head>
<body>

<h3>Sendbird actions</h3>


<script>
    var currentGroupChannel = null;
    window.addEventListener("load", function (event) {
        var sb = new SendBird({
            appId: '<?= $sendbirdApplicationId ?>'
        });

        var userIdsForGroupChannel = ['upcall_usr_2000', 'upcall_usr_3000'];
        connectSendbirdUser(sb)
            .then(function () {
                return listGroupChannels(sb);
            })
            .then(function () {
                return initGroupChannel(sb, userIdsForGroupChannel);
            })
            .then(function () {
                return sendMessageToGroupChannel(userIdsForGroupChannel, 'test message '+(new Date()).toISOString());
            })
            .then(function () {
                return listMessagesOfGroupChannel(userIdsForGroupChannel);
            })
        ;

    });

</script>

<script>

    function poc_output(msg) {
        var elOutputBox = document.querySelector(".output_box_bottom");
        elOutputBox.innerHTML = elOutputBox.innerHTML + '<br>' + msg;
    }


    function connectSendbirdUser(sb) {
        var deferredConnectUser = Q.defer();
        poc_output('+++ Trying to connect user ... ');
        sb.connect('<?= $userId; ?>', '<?= $userAccessToken; ?>', function (user, error) {
            poc_output('user: ' + JSON.stringify(user));
            poc_output('error: ' + JSON.stringify(error));

            if (!error) {
                deferredConnectUser.resolve(user);
            } else {
                deferredConnectUser.reject(error);
            }
        });

        return deferredConnectUser.promise;
    }

    function listGroupChannels(sb) {
        var deferredGroupChannelUsers = Q.defer();
        poc_output('+++ Trying to get group channels ... ');
        var channelListQuery = sb.GroupChannel.createMyGroupChannelListQuery();
        channelListQuery.includeEmpty = true;

        poc_output('channelListQuery.hasNext: ' + JSON.stringify(channelListQuery.hasNext));
        if (channelListQuery.hasNext) {
            var resNext = channelListQuery.next(function (channelList, error) {
                poc_output('channelList: ' + JSON.stringify(channelList));
                poc_output('error: ' + JSON.stringify(error));
                poc_output('resNext: ' + JSON.stringify(resNext));

                if (!error) {
                    deferredGroupChannelUsers.resolve(channelList);
                } else {
                    deferredGroupChannelUsers.reject(error);
                }
            });
        } else {
            poc_output('channelListQuery.hasNext: ' + JSON.stringify(channelListQuery.hasNext));
            deferredGroupChannelUsers.reject('channelListQuery.hasNext is false');
        }

        return deferredGroupChannelUsers.promise;
    }

    function initGroupChannel(sb, userIds) {
        var deferredInitGroupChannel = Q.defer();
        poc_output('+++ Trying to create BY REUSING a group channel, composed of users: ' + userIds.join(','));

        if (currentGroupChannel) {
            deferredInitGroupChannel.resolve(currentGroupChannel);
        } else {
            var groupChannelName = ('Group ' + userIds.join(',')).substr(0, 20) + '...';
            sb.GroupChannel.createChannelWithUserIds(userIds, true, groupChannelName, 'some_cover_url', 'some_data', function (channel, error) {
                poc_output('channel: ' + JSON.stringify(channel));
                poc_output('error: ' + JSON.stringify(error));

                if (!error) {
                    currentGroupChannel = channel;
                    deferredInitGroupChannel.resolve(channel);
                } else {
                    deferredInitGroupChannel.reject(error);
                }
            });
        }

        return deferredInitGroupChannel.promise;
    }

    function sendMessageToGroupChannel(userIds, messageToSendToGroupChannel) {
        var deferredSendMessageToGroupChannel = Q.defer();
        poc_output('+++ Trying to send message to a group channel, composed of users: ' + userIds.join(','));

        if (!currentGroupChannel) {
            poc_output('currentGroupChannel: ' + JSON.stringify(currentGroupChannel));
            deferredSendMessageToGroupChannel.reject('Cannot send message, because group channel not initialised, currentGroupChannel');
        } else {
            currentGroupChannel.sendUserMessage(messageToSendToGroupChannel, 'some data for msg', function (message, error) {
                poc_output('message: ' + JSON.stringify(message));
                poc_output('error: ' + JSON.stringify(error));

                if (!error) {
                    deferredSendMessageToGroupChannel.resolve(message);
                } else {
                    deferredSendMessageToGroupChannel.reject(error);
                }
            });
        }
        return deferredSendMessageToGroupChannel.promise;
    }

    function listMessagesOfGroupChannel(userIds) {
        var deferredListMessagesOfGroupChannel = Q.defer();
        poc_output('+++ Trying to list messages of a group channel, composed of users: ' + userIds.join(','));

        if (!currentGroupChannel) {
            poc_output('currentGroupChannel: ' + JSON.stringify(currentGroupChannel));
            deferredListMessagesOfGroupChannel.reject('Cannot list messages, because group channel not initialised, currentGroupChannel');
        } else {
            var messageListQuery = currentGroupChannel.createPreviousMessageListQuery();

            messageListQuery.load(200, true, function(messageList, error) {
                poc_output('messageList: ' + JSON.stringify(messageList));
                poc_output('error: ' + JSON.stringify(error));

                if (!error) {
                    deferredListMessagesOfGroupChannel.resolve(messageList);
                } else {
                    deferredListMessagesOfGroupChannel.reject(error);
                }
            });
        }
        return deferredListMessagesOfGroupChannel.promise;
    }

</script>
<script src="/q.js"></script>
<script src="/SendBird.min.js"></script>

<div class="output_box_bottom"></div>
</body>
</html>