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
                            $rootScope.$broadcast('refreshImages', $scope.options);


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

            OptionsResource.getSizes().$promise.then(function (sizes) {
                $scope.sizes = sizes;

            });

            $scope.resetNumberPage = function () {
                $scope.options.numberPage = 0;
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
