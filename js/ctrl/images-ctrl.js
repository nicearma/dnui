'use strict';


angular.module('dnuiPlugin')
    .controller('ImagesCtrl', ['$scope', '$rootScope', 'ImagesResource', 'BackupResource',
        function ($scope, $rootScope, ImagesResource, BackupResource) {

            var callInit = 0;



            var getImages = function (numberPage) {
                $scope.deleteAllButton=true;
                $scope.images=[];
                if (_.isUndefined(numberPage)) {
                    numberPage = {};
                } else {
                    numberPage = {numberPage: numberPage};
                }
                ImagesResource.readByOptions(numberPage).$promise.then(function (images) {

                    $scope.images = images;

                    angular.forEach($scope.images, function (image, key) {

                        //checking status
                        image.status.used = -1;
                        image.status.inServer = -1;
                        angular.forEach(image.imageSizes, function (imageSize) {
                            imageSize.status.used = -1;
                            imageSize.status.inServer = -1;
                        });

                        ImagesResource.verifyUsedById({id: image['id']}).$promise.then(function (statusSizes) {
                            _.each(statusSizes[image['id']], function (status, sizeName) {
                                if (sizeName === 'original') {
                                    image['status'] = status;
                                } else {
                                    _.findWhere(image.imageSizes, {sizeName: sizeName})['status'] = status;

                                }
                            });

                        });

                    });
                });
            };


            $rootScope.$on('options', function (event, options) {
                $scope.options = options;
                callInit++;

                if (callInit > 2) {
                    getImages();
                }

            });

            $rootScope.$on('tabImages', function () {

                ImagesResource.count().$promise.then(function (count) {
                    $scope.totalImages = count['0'];
                });

                getImages();

            });

            $rootScope.$on('pageChange', function (event, numberPage) {

                callInit++;

                if (callInit > 2) {
                    getImages(numberPage);
                }

            });


            $scope.delete = function (id, sizeName, image) {

                var deleteFunction = function (id, sizeName, image) {
                    image.status.used = 3;
                    angular.forEach(image.imageSizes, function (imageSize, sizeName) {
                        imageSize.status.used = 3;
                    });

                    ImagesResource.deleteByIdAndSize({id: id, sizeName: sizeName}).$promise.then(function (status) {
                        image.status = status;
                        if (sizeName == 'original') {
                            angular.forEach(image.imageSizes, function (imageSize, sizeName) {
                                imageSize.status = status;
                            });
                        }
                    });
                };

                //only if image is not used
                if (image.status.used == 0) {


                    if ($scope.options.backup) {
                        image.status.used = 4;
                        BackupResource.make({id: id, sizeName: sizeName}).$promise.then(function (status) {
                            if (status.inServer > 0) {
                                deleteFunction(id, sizeName, image);
                            } else {
                                image.status = status;
                            }
                        });
                    } else {
                        deleteFunction(id, sizeName, image);
                    }
                }
            };


            $scope.deleteAll = function () {

                $scope.deleteAllButton=false;

                angular.forEach($scope.images, function (image, key) {
                    if (image.sizeName === 'original' && $scope.options.ignoreSizes.length === 0) {

                        $scope.delete(image.id, image.sizeName, image);

                    } else {
                        angular.forEach(image.imageSizes, function (imageSize, sizeName) {
                            if ($scope.options.ignoreSizes.indexOf(imageSize.sizeName) == -1) {
                                $scope.delete(image.id, imageSize.sizeName, imageSize);
                            }
                        });

                    }
                });
            };


        }
    ]);
