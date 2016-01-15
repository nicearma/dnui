'use strict';


angular.module('dnuiPlugin')
    .controller('DnuiCtrl', ['$scope', '$rootScope',
        function ($scope, $rootScope) {

            $scope.tabImages=function(){
                $rootScope.$broadcast('tabImages', $scope.options);
            };

            $scope.tabBackups=function(){
                $rootScope.$broadcast('tabBackups', $scope.options);
            };

            $scope.tabOptions=function(){
                $rootScope.$broadcast('tabOptions', $scope.options);
            };

        }
    ]);

