'use strict';


angular.module('dnuiPlugin')
    .controller('ImagesCtrl', ['$scope', '$rootScope', 'ImagesResource', 'BackupResource',
        function ($scope, $rootScope, ImagesResource, BackupResource) {

            //this is use for refresh the image list
            var refreshImages = true;

            /*
             0 => unknown
             1 => fetching..
             2 => ok
             */
            $scope.callServerStatus = 0;

            var getImages = function (numberPage) {
                $scope.callServerStatus = 1;
                //refreshing so change to false
                refreshImages = false;

                //desactivate the delete all button, only will be visible if all image where verified
                $scope.deleteAllButton = 0;
                $scope.images = [];
                if (_.isUndefined(numberPage)) {
                    numberPage = {};
                } else {
                    numberPage = {numberPage: numberPage};
                }

                var countButtonDeleteAllButton = 0; //begin of the count

                ImagesResource.readByOptions(numberPage).$promise.then(function (images) {

                    if (images.length == 0 && $scope.options.numberPage > 1) {
                        ImagesResource.count().$promise.then(function (count) {
                            $scope.totalImages = count['0'];
                        });
                        $scope.options.numberPage = $scope.options.numberPage - 1;
                        return;
                    }


                    $scope.images = images;
                    $scope.callServerStatus = 2;
                    countButtonDeleteAllButton = images.length; //all original images
                    $scope.deleteAllButton = 2; //waiting all image status
                    angular.forEach($scope.images, function (image, key) {

                        //checking status
                        image.status.used = -1;
                        image.status.inServer = -1;
                        angular.forEach(image.imageSizes, function (imageSize) {
                            imageSize.status.used = -1;
                            imageSize.status.inServer = -1;
                            $scope.deleteAllButton++; //all sizes
                        });

                        ImagesResource.verifyUsedById({id: image['id']}).$promise.then(function (statusSizes) {

                            _.each(statusSizes[image['id']], function (status, sizeName) {

                                if ($scope.options.galleryCheck) {

                                    if (!_.isUndefined($scope.galleriesSizes[image['id']]) && !_.isUndefined($scope.galleriesSizes[image['id']]['sizes'])) {

                                        if ($scope.galleriesSizes[image['id']]['sizes'].indexOf(sizeName) > -1) {
                                            status.used = 1;
                                        }

                                    }
                                }

                                if (sizeName === 'original') {
                                    image['status'] = status;
                                } else {
                                    _.findWhere(image.imageSizes, {sizeName: sizeName})['status'] = status;
                                }
                                countButtonDeleteAllButton--;
                                if (countButtonDeleteAllButton == 0) {
                                    $scope.deleteAllButton = 1; //delete all button
                                }

                            });

                            //try to fix thumbnail or size used in gallery but not found in image
                            if ($scope.options.galleryCheck) {

                                if (!_.isUndefined($scope.galleriesSizes[image['id']]) && !_.isUndefined($scope.galleriesSizes[image['id']]['sizes'])) {

                                    angular.forEach($scope.galleriesSizes[image['id']]['sizes'], function (sizeName) {
                                        if (_.isUndefined(image.imageSizes[sizeName])) {
                                            image.status.used = true;
                                        }
                                    });


                                }
                            }



                        });

                    });
                });
            };


            $rootScope.$on('options', function (event, options) {

                $scope.options = options;

                refreshImages = true;


            });

            $rootScope.$on('tabImages', function () {

                if (refreshImages) {
                    ImagesResource.count().$promise.then(function (count) {
                        $scope.totalImages = count['0'];
                    });

                    if ($scope.options.galleryCheck) {

                        if (_.isUndefined($scope.galleriesSizes)) {
                            $scope.callServerStatus = 1;
                            ImagesResource.galleries().$promise.then(function (galleriesSizes) {

                                $scope.galleriesSizes = galleriesSizes;
                                getImages();
                            });
                        } else {
                            getImages();
                        }

                    } else {
                        getImages();
                    }

                }
            });

            $rootScope.$on('pageChange', function (event, numberPage) {

                getImages(numberPage);

            });

            $rootScope.$on('backup', function () {

                refreshImages = true;

            });


            var deleteFunction = function (id, sizeNames, image) {

                //add status before delete
                angular.forEach(sizeNames, function (sizeName) {
                    if (sizeName == 'original') {
                        angular.forEach(image.imageSizes, function (imageSize) {
                            imageSize.status.used = 3;
                        });
                    } else {
                        image.imageSizes[sizeName].status.used = 3;
                    }
                });


                ImagesResource.deleteByIdAndSize({id: id, sizeNames: sizeNames}).
                    $promise.then(function (statusBySize) {

                        angular.forEach(sizeNames, function (sizeName) {

                            if (sizeName == 'original') {

                                image.status = statusBySize[sizeName];

                                angular.forEach(image.imageSizes, function (imageSize) {
                                    imageSize.status = statusBySize[sizeName];
                                });

                            } else {
                                image.imageSizes[sizeName].status = statusBySize[sizeName];
                            }

                        });

                    });
            };

            //not delete original image if one size is used
            $scope.unusedImageSizesForOriginal = function (image) {

                //special case of original
                if (image.sizeName === 'original') {

                    if (image.status.used == 1) {
                        return false;
                    }

                    for (var imageSize in image.imageSizes) {
                        if (image.imageSizes[imageSize].status.used == 1) {
                            return false;
                        }
                    }

                }

                return true;
            };

            var makeBackupAndDelete = function (id, sizeNames, image) {

                BackupResource.make({id: id, sizeNames: sizeNames}).$promise.then(function (statusBySize) {

                    //backup made, see if all was good
                    var sizeNamesAllGood = [];

                    for (var key in sizeNames) {

                        if (sizeNames[key] == 'original') {
                            angular.forEach(image.imageSizes, function (imageSize, sizeName) {
                                imageSize.status = statusBySize[sizeNames[key]];
                            });

                        } else {
                            image.imageSizes[sizeNames[key]].status = statusBySize[sizeNames[key]];
                        }

                        if (statusBySize[sizeNames[key]].inServer > 0) {
                            sizeNamesAllGood.push(sizeNames[key]);
                        }
                    }

                    deleteFunction(id, sizeNamesAllGood, image);


                });
            }


            $scope.delete = function (id, sizeName, image) {

                if (sizeName == 'original') {
                    if ((image.status.used == 1 || !$scope.unusedImageSizesForOriginal(image))) {
                        return;
                    }
                } else if (image.imageSizes[sizeName].status.used == 1) {
                    return;
                }

                //do backup
                if ($scope.options.backup) {

                    if (sizeName == 'original') {
                        image.status.used = 4;
                        angular.forEach(image.imageSizes, function (imageSize) {
                            imageSize.status.used = 4;
                        });
                    } else {
                        image.imageSizes[sizeName].status.used = 4;
                    }


                    makeBackupAndDelete(id, [sizeName], image);

                } else {

                    deleteFunction(id, [sizeName], image);

                }

                refreshImages = true;

            };


            $scope.deleteAll = function () {
                //desactivate the deleteAll button
                $scope.deleteAllButton = false;

                //search all image that can be deleted
                angular.forEach($scope.images, function (image, key) {
                    //original image, if any imageSize in the ignore size in the list, and any other image used
                    if (image.sizeName === 'original' && $scope.options.ignoreSizes.length === 0 && $scope.unusedImageSizesForOriginal(image)) {

                        //i can delete the original image
                        $scope.delete(image.id, image.sizeName, image);

                    } else {
                        //search image sizes
                        var sizeNames = [];

                        for (var key in image.imageSizes) {
                            //searching all image than can be deleted
                            if ($scope.options.ignoreSizes.indexOf(image.imageSizes[key].sizeName) == -1 && image.imageSizes[key].status.used == 0) {
                                sizeNames.push(image.imageSizes[key].sizeName);
                            }
                        }

                        //nothing to do
                        if (sizeNames.length == 0) {
                            return;
                        }

                        //skip the validation and go to delete function
                        makeBackupAndDelete(image.id, sizeNames, image);

                    }
                });
            };


        }
    ])
;
