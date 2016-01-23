'use strict';


angular.module('dnuiPlugin')
    .controller('BackupCtrl', ['$scope', '$rootScope', 'BackupResource',
        function ($scope, $rootScope, BackupResource) {

            $scope.callServerStatus=2;

            $scope.backups = [];

            $rootScope.$on('tabBackups', function () {
                $scope.backups = [];
                $scope.callServerStatus=1;
                BackupResource.get().$promise.then(function (backupIds) {



                    angular.forEach(backupIds, function (backupId, key, obj) {

                        if (key != '$resolved' && key != '$promise') {
                            $scope.backups.push({id: backupId, status: {inServer: 1}});
                        }

                    });

                    $scope.callServerStatus=2;

                });
            });


            $scope.restore = function (backup) {

                $rootScope.$broadcast('backup', {});

                if (backup.status.inServer == 1) {
                    backup.status.inServer = 4;
                    BackupResource.restore({id: backup.id}).$promise.then(function (status) {
                        if (status.inServer == 2) {
                            backup.status = status;
                            $scope.deleteById( backup);
                        } else {
                            //TODO error
                        }

                    });
                }

            };

            $scope.deleteAll = function () {

                $rootScope.$broadcast('backup', {});

                angular.forEach($scope.backups, function (backup) {
                    $scope.deleteById(backup);
                });

            };

            $scope.deleteById = function (backup) {

                $rootScope.$broadcast('backup', {});
                if (backup.status.inServer == 1 || backup.status.inServer == 2) {
                    backup.status.inServer = 5;
                    BackupResource.deleteById({id: backup.id}).$promise.then(function (status) {
                        if (status.inServer == 3) {
                            backup.status = status;
                        } else {
                            //TODO: error
                        }
                    });
                }

            };


        }
    ]);
