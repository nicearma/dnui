'use strict';


angular.module('dnuiPlugin')
    .controller('OptionsCtrl', ['$scope', '$rootScope', 'OptionsResource','BackupResource',
        function ($scope, $rootScope, OptionsResource,BackupResource) {

            OptionsResource.get().$promise.then(function (options) {
                $scope.options = options;

                $scope.$watchCollection('options', function (newCollection, oldCollection, scope) {
                    OptionsResource.update($scope.options);
                });

                $scope.$watch('options.numberPage', function () {
                    $rootScope.$broadcast('pageChange', $scope.options.numberPage);
                });


                $rootScope.$broadcast('options', $scope.options);

            });

            $scope.sizes = OptionsResource.getSizes();

            $scope.resetNumberPage = function () {
                $scope.options.numberPage = 0;
            };

            BackupResource.existsBackupFolder().$promise.then(function(statusBackup){
                $scope.statusBackup=statusBackup;
                if(statusBackup.inServer<1){
                    $scope.options.backup=false;
                }
            });

            $scope.makeBackupFolder=function(){
                BackupResource.makeBackupFolder().$promise.then(function(statusBackup){
                    $scope.statusBackup=statusBackup;
                    if(statusBackup.inServer<1){

                        $scope.options.backup=false;
                    }else{
                        //TODO
                    }
                });
            };

        }
    ]);
