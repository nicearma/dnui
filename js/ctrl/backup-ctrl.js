'use strict';


angular.module('dnuiPlugin')
    .controller('BackupCtrl', ['$scope', '$rootScope','$uibModal', 'BackupResource',
        function ($scope, $rootScope,$uibModal, BackupResource) {

            //this help to show the button
            $scope.callServerStatus = 2;

            $scope.backups = [];



            $rootScope.$on('tabBackups', function () {
                $scope.backups = [];
                //calling server
                $scope.callServerStatus = 1;

                BackupResource.get().$promise.then(function (backupIds) {
                    //clean the object
                    delete backupIds.$resolved;
                    delete backupIds.$promise;
                    delete backupIds.$cancelRequest;
                    //
                    //create the backupId Object, all file can be deleted
                    angular.forEach(backupIds, function (backupId, key, obj) {

                            $scope.backups.push({id: backupId, status: {inServer: 1}});
                        
                    });

                    $scope.callServerStatus = 2;


                });

            });


            $scope.restore = function (backup) {
                //refresh images
                $rootScope.$broadcast('refreshImage', {});
                //i can delete this backup
                if (backup.status.inServer == 1) {
                    //restoring.. status
                    backup.status.inServer = 4;

                    //restore each backup
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


             //delete one backup by backup and if this one was restored
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
