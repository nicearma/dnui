'use strict';


angular.module('dnuiPlugin')
    .controller('OptionsCtrl', ['$scope', '$rootScope', 'OptionsResource', 'BackupResource','logFactoroy',
        function ($scope, $rootScope, OptionsResource, BackupResource,logFactoroy) {

            var inutilCallOptions = true;

            OptionsResource.get().$promise.then(function (options) {

                $scope.options = options;
                logFactoroy.setLog($scope.options.debug);
                OptionsResource.getSizes().$promise.then(function (sizes) {
                    $scope.sizes = sizes;

                });

                $scope.$watchCollection('options', function (newCollection, oldCollection, scope) {

                    logFactoroy.setLog($scope.options.debug);
                    //first call
                    if (inutilCallOptions) {
                        inutilCallOptions = false;
                    } else {

                            OptionsResource.update($scope.options);
                            $rootScope.$broadcast('refreshImage',{});
                            logFactoroy.setLog($scope.options.debug);

                    }

                });

                BackupResource.existsBackupFolder().$promise.then(function (statusBackup) {
                        $scope.statusBackup = statusBackup;

                        if (statusBackup.inServer < 1) {
                            $scope.disabledBackupOption = true;
                            $scope.options.backup = false;
                        } else {
                            $scope.disabledBackupOption = false;
                        }
                    }
                );

                $rootScope.$broadcast('options', $scope.options);

            });


            $scope.resetNumberPage = function () {
                $scope.options.numberPage = 1;
                $rootScope.$broadcast('refreshImage', $scope.options.numberPage);
            };


            $scope.makeBackupFolder = function () {
                BackupResource.makeBackupFolder().$promise.then(function (statusBackup) {
                    $scope.statusBackup = statusBackup;
                    if (statusBackup.inServer < 1) {

                        $scope.options.backup = false;

                    } else {
                        $scope.disabledBackupOption = false;
                    }
                });
            };

            $scope.restore = function () {

                $scope.options = OptionsResource.restore();
                $rootScope.$broadcast('restore',{});
                $rootScope.$broadcast('options', $scope.options);
            };
        }
    ]).directive('convertToNumber', function () {
        return {
            require: 'ngModel',
            link: function (scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function (val) {
                    return parseInt(val, 10);
                });
                ngModel.$formatters.push(function (val) {
                    return '' + val;
                });
            }
        };
    });
;
