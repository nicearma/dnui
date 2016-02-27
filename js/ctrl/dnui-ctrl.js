'use strict';


angular.module('dnuiPlugin')
    .controller('DnuiCtrl', ['$scope', '$rootScope','$uibModal',
        function ($scope, $rootScope,$uibModal) {

            $scope.options= {backup:-1};
            //go to tabImage
            $scope.tabImages=function(){
                $rootScope.$broadcast('tabImages', $scope.options);
            };
            //go to tabBackup
            $scope.tabBackups=function(){
                $rootScope.$broadcast('tabBackups', $scope.options);
            };
            //go to tab options
            $scope.tabOptions=function(){
                $rootScope.$broadcast('tabOptions', $scope.options);
            };

            //get options, help full to tell if you have the backup system active
            $rootScope.$on('options', function (event, options) {

                $scope.options = options;

            });

        }
    ]);

