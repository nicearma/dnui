'use strict';


angular.module('dnuiPlugin')
    .controller('DnuiCtrl', ['$scope', '$rootScope','OptionsResource',
        function ($scope, $rootScope,OptionsResource) {

            $scope.options= {backup:-1};
            $scope.wc={active:-1};
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
            
			$scope.tabLogs=function() {
                $rootScope.$broadcast('tabLogs', $scope.options);
            };

            //get options, help full to tell if you have the backup system active
            $rootScope.$on('options', function (event, options) {

                $scope.options = options;
                $scope.wc=OptionsResource.haveWC();

            });

        }
    ]);

