'use strict';


angular.module('dnuiPlugin')
    .controller('OptionsCtrl', ['$scope', '$rootScope', 'OptionsResource', 'BackupResource',
        function ($scope, $rootScope, OptionsResource, BackupResource) {

            var inutilCallOptions = true;
            var inutilCallNumberPage = true;

            OptionsResource.get().$promise.then(function (options) {
                $scope.options = options;



                $scope.$watchCollection('options', function (newCollection, oldCollection, scope) {

                    //first call
                    if (inutilCallOptions) {
                        inutilCallOptions = false;
                    } else {
                        OptionsResource.update($scope.options);
                    }

                });

                $scope.$watch('options.numberPage', function () {

                    //first call
                    if (inutilCallNumberPage) {
                        inutilCallNumberPage = false;
                    } else {
                        $rootScope.$broadcast('pageChange', $scope.options.numberPage);
                    }

                });

                BackupResource.existsBackupFolder().$promise.then(function (statusBackup) {
                        $scope.statusBackup = statusBackup;

                        if (statusBackup.inServer < 1) {
                            $scope.disabledBackupOption=true;
                            $scope.options.backup = false;
                        }else{
                            $scope.disabledBackupOption=false;
                        }
                    }
                );

                $rootScope.$broadcast('options', $scope.options);

            });

            $scope.sizes = OptionsResource.getSizes();

            $scope.resetNumberPage = function () {
                $scope.options.numberPage = 0;
            };



            $scope.makeBackupFolder = function () {
                BackupResource.makeBackupFolder().$promise.then(function (statusBackup) {
                    $scope.statusBackup = statusBackup;
                    if (statusBackup.inServer < 1) {

                        $scope.options.backup = false;

                    } else {
                        $scope.disabledBackupOption=false;
                    }
                });
            };

        }
    ]);
