'use strict';


angular.module('dnuiPlugin')
    .controller('DnuiCtrl', ['$scope', '$rootScope','$uibModal',
        function ($scope, $rootScope,$uibModal) {

            $scope.options= {backup:-1};

            $scope.tabImages=function(){
                $rootScope.$broadcast('tabImages', $scope.options);
            };

            $scope.tabBackups=function(){
                $rootScope.$broadcast('tabBackups', $scope.options);
            };

            $scope.tabOptions=function(){
                $rootScope.$broadcast('tabOptions', $scope.options);
            };


            $rootScope.$on('options', function (event, options) {

                $scope.options = options;

            });

        }
    ]);

