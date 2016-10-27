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
            window.addEventListener("load", function(event) {
                var sb = new SendBird({
                    appId: '<?= $sendbirdApplicationId ?>'
                });

                connectSendbirdUser(sb).then(function() {
                    listGroupChannels(sb);
                });

            });

        </script>

        <script>

            function poc_output(msg) {
                var elOutputBox = document.querySelector(".output_box_bottom");
                elOutputBox.innerHTML = elOutputBox.innerHTML + '<br>' + msg;
            }


            function connectSendbirdUser(sb) {
                var deferredConnectUser = Q.defer();
                poc_output('Trying to connect user ... ');
                sb.connect('<?= $userId; ?>', '<?= $userAccessToken; ?>', function(user, error) {
                    poc_output('user: ' + JSON.stringify(user));
                    poc_output('error: '+ JSON.stringify(error));

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
                poc_output('Trying to get group channels ... ');
                var channelListQuery = sb.GroupChannel.createMyGroupChannelListQuery();
                channelListQuery.includeEmpty = true;

                if (channelListQuery.hasNext) {
                    channelListQuery.next(function(channelList, error){
                        poc_output('channelList: ' + JSON.stringify(channelList));
                        poc_output('error: '+ JSON.stringify(error));

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

        </script>
        <script src="/q.js"></script>
        <script src="/SendBird.min.js"></script>

        <div class="output_box_bottom"></div>
    </body>
</html>