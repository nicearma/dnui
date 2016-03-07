'use strict';


angular.module('dnuiPlugin')
    .controller('LogCtrl', ['$scope', '$rootScope', 'logFactoroy',
        function ($scope, $rootScope, logFactoroy) {

            $scope.logs = [];
            $scope.errors = [];
            //tab image is call

            $rootScope.$on('tabLogs', function () {
                $scope.errors = logFactoroy.getErrors();
                $scope.logs = logFactoroy.getLogs();
            });


        }]
);