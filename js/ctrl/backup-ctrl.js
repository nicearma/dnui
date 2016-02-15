'use strict';


angular.module('dnuiPlugin')
    .controller('BackupCtrl', ['$scope', '$rootScope','$uibModal', 'BackupResource',
        function ($scope, $rootScope,$uibModal, BackupResource) {

            $scope.callServerStatus = 2;

            $scope.backups = [];

            $rootScope.$on('tabBackups', function () {
                $scope.backups = [];
                $scope.callServerStatus = 1;

                BackupResource.get().$promise.then(function (backupIds) {


                    angular.forEach(backupIds, function (backupId, key, obj) {

                        if (key != '$resolved' && key != '$promise') {
                            $scope.backups.push({id: backupId, status: {inServer: 1}});
                        }

                    });

                    $scope.callServerStatus = 2;

                });

            });


            $scope.restore = function (backup) {

                $rootScope.$broadcast('refreshImage', {});

                if (backup.status.inServer == 1) {

                    backup.status.inServer = 4;

                    BackupResource.restore({id: backup.id}).$promise.then(function (status) {
                        if (status.inServer == 2) {
                            status.inServer=7;
                            backup.status = status;
                            $scope.deleteById(backup,true);
                        } else {
                            //TODO error
                        }

                    });
                }

            };


            $scope.deleteById = function (backup,backupMade) {

                $rootScope.$broadcast('refreshImage', {});
                if (backup.status.inServer == 1 || backup.status.inServer == 2 || backup.status.inServer ==7) {
                    if(backup.status.inServer !=7){
                        backup.status.inServer = 5;
                    }


                    BackupResource.deleteById({id: backup.id}).$promise.then(function (status) {

                        if (status.inServer == 3) {

                            if(!_.isUndefined(backupMade)){
                                 status.inServer=6;
                            }
                            backup.status = status;

                        } else {
                            //TODO: error
                        }
                    });
                }

            };


        }
    ]);