'use strict';


angular.module('dnuiPlugin')
    .controller('ImagesCtrl', ['$scope', '$rootScope', '$timeout', '$q', '$uibModal', 'ImagesResource', 'BackupResource', '$uibModal',
        function ($scope, $rootScope, $timeout, $q, $uibModal, ImagesResource, BackupResource) {

            //this is use for refresh the image list
            var refreshImages = true;

            /*
             0 => unknown
             1 => fetching..
             2 => ok
             */
            $scope.callServerStatus = 0;

            var currentsCalls=[];

            var resetCurrentsCalls=function(){
                if(currentsCalls.length>0){
                    currentsCalls.forEach(function(call){
                        call.$cancelRequest();
                    });
                }
            };

            $scope.changeShowUsedImage = function () {
                $scope.options.showUsedImage = !$scope.options.showUsedImage;
            };

            var resetCountStatus = function () {
                $scope.totalImageUsed = 0;
                $scope.totalImageNotUsed = 0;
            };


            var countStatus = function (status) {
                if (status.used == 1) {
                    $scope.totalImageUsed++;
                } else {
                    $scope.totalImageNotUsed++;
                }
            };

            var getImages = function (numberPage) {
                resetCountStatus();
                $scope.callServerStatus = 1;
                //refreshing so change to false
                refreshImages = false;

                //desactivate the delete all button, only will be visible if all image where verified
                $scope.deleteAllButton = 0;
                $scope.images = [];
                //if is undefined empty object
                if (_.isUndefined(numberPage)) {
                    numberPage = {};
                } else {
                    numberPage = {numberPage: numberPage};
                }

               resetCurrentsCalls();



                //important array, this will help to sync all ansync call to the server, use for the delete all logic
                var syncCall = [];
                //call to server
                ImagesResource.readByOptions(numberPage).$promise.then(function (images) {
                        //nothing was found, but if i'm not at the first page, i will try to go back, until i found something or i'm in the first page
                        if (images.length == 0 && $scope.options.numberPage > 1) {
                            //recount image, this can be happen if the last page was deleted
                            ImagesResource.count().$promise.then(function (count) {
                                $scope.totalImages = count['0'];
                            });
                            //back one page
                            $scope.options.numberPage = $scope.options.numberPage - 1;
                            getImages($scope.options.numberPage);
                            return;
                        }


                        $scope.images = images;
                        //help with the status of call server, this will update after all call are made
                        $scope.callServerStatus = 2;
                        $scope.deleteAllButton = 2; //waiting all image status

                        //begin of the verification logic
                        angular.forEach($scope.images, function (image, key) {

                            //original image status = checking
                            image.status.used = -1;
                            image.status.inServer = -1;
                            //sizes image status = checking
                            angular.forEach(image.imageSizes, function (imageSize) {
                                imageSize.status.used = -1;
                                imageSize.status.inServer = -1;
                            });

                            var call=ImagesResource.verifyUsedById({id: image['id']});
                            currentsCalls.push(call);

                            //all ansync call of verify status added to the sync help array
                            //all image going to be verified
                            syncCall.push(call.$promise.then(function (statusSizes) {

                                    //try to fix thumbnail or size used in gallery but not found in image
                                    if ($scope.options.galleryCheck) {

                                        if (!_.isUndefined($scope.galleriesSizes[image['id']]) && !_.isUndefined($scope.galleriesSizes[image['id']]['sizes'])) {

                                            angular.forEach($scope.galleriesSizes[image['id']]['sizes'], function (sizeName) {
                                                //if one sizeName not exit, i can not let delete the original image, normally this status will be complete with the tag logic
                                                if (_.isUndefined(image.imageSizes[sizeName])) {
                                                    image.status.used = 1;
                                                }
                                            });


                                        }
                                    }


                                    //the statusSizes is by ID and contain the status from one size name, include the original size
                                    _.each(statusSizes[image['id']], function (status, sizeName) {
                                        //again the logic for the gallery check, but this time will be direct
                                        if ($scope.options.galleryCheck) {

                                            if (!_.isUndefined($scope.galleriesSizes[image['id']]) && !_.isUndefined($scope.galleriesSizes[image['id']]['sizes']) && status.used != 1) {

                                                if ($scope.galleriesSizes[image['id']]['sizes'].indexOf(sizeName) > -1) {
                                                    status.used = 1;
                                                }
                                            }
                                        }

                                        //original image
                                        if (sizeName === 'original') {
                                            //the shortcode logic is active and the status is not used, verify if the image is used in any tag
                                            if ($scope.options.shortCodeCheck && status.used != 1) {
                                                //go every tag, if something is one is found, i will go out of the for
                                                for (var key in $scope.htmlShortcodes) {
                                                    if ($scope.htmlShortcodes[key].indexOf(image.name) > -1) {

                                                        status.used = 1;
                                                        break;
                                                    }
                                                }

                                            }
                                            //update the status of the original image
                                            image['status'] = status;
                                            countStatus(status);

                                        } else {
                                            //found the imageSize from the image.imageSizes
                                            var imageSize = _.findWhere(image.imageSizes, {sizeName: sizeName});

                                            //the same search in from tag
                                            if ($scope.options.shortCodeCheck && status.used != 1) {
                                                for (var key in $scope.htmlShortcodes) {
                                                    if ($scope.htmlShortcodes[key].indexOf(imageSize.name) > -1) {
                                                        status.used = 1;

                                                        break;
                                                    }
                                                }
                                            }
                                            imageSize['status'] = status;
                                            countStatus(status);


                                        }

                                    });


                                })
                            )
                            ;


                        });
                        //after all sync call are finish do this
                        $q.all(syncCall).then(function () {

                            $scope.deleteAllButton = 1; //delete all button

                        });
                    }, function(result){
                        alert("Something go wrong, please activate the debug option, see the last 'tranform' and 'response: dnui_get_all_by_options_image', you can see to the console 'shift+ctrl+i'");
                        console.log('Error fetching server...:',result);
                    }
                );
            };

            //if options is send from the optionsCtrl
            $rootScope.$on('options', function (event, options) {

                $scope.options = options;

                refreshImages = true;


            });

            var getChecks = function (numberPage) {
                var syncCall = [];

                //i have to refresh the image tab
                if (refreshImages) {
                    //it better count
                    ImagesResource.count().$promise.then(function (count) {
                        $scope.totalImages = count['0'];
                    });

                    //gallery active
                    if ($scope.options.galleryCheck) {
                        //do it only one time, this call is expensive, maybe put this in options
                        if (_.isUndefined($scope.galleriesSizes)) {


                            $scope.callServerStatus = 1;
                            //call the server for get the galleries information
                            syncCall.push(ImagesResource.galleries().$promise.then(function (galleriesSizes) {

                                delete galleriesSizes.$promise;
                                delete galleriesSizes.$resolved;
                                delete galleriesSizes.$cancelRequest;
                                $scope.galleriesSizes = galleriesSizes;

                            }));
                        }

                    }

                    if (_.isUndefined($scope.htmlShortcodes)) {

                        if ($scope.options.shortCodeCheck) {
                            syncCall.push(ImagesResource.shortcodes().$promise.then(function (htmlShortcodes) {
                                delete htmlShortcodes.$promise;
                                delete htmlShortcodes.$resolved;
                                delete htmlShortcodes.$cancelRequest;
                                $scope.htmlShortcodes = htmlShortcodes;

                            }));
                        }
                    }


                    if (syncCall.length > 0) {
                        $q.all(syncCall).then(function () {
                            getImages(numberPage);
                        });
                    } else {
                        getImages(numberPage);
                    }

                }
            }


            //tab image is call
            $rootScope.$on('tabImages', function () {

                getChecks();

            });

            //call if the numberPage change
            $scope.changeNumberPage = function () {
                refreshImages = true;
                resetCurrentsCalls();
                getChecks($scope.options.numberPage);
            };

            //refresh the image tab went is call
            $rootScope.$on('refreshImage', function () {
                refreshImages = true;
            });

            //restore
            $rootScope.$on('restore', function () {
                //fix bug restore...
                ImagesResource.count().$promise.then(function (count) {
                    $scope.totalImages = count['0'];
                });

            });

            //not delete original image if one size is used
            $scope.unusedImageSizesForOriginal = function (image) {

                //special case of original
                if (image.sizeName === 'original') {
                    //if is used, or the ignoreImage array have one ref, this image is used or ignored
                    if (image.status.used == 1 || image.status.used == 2) {
                        return false;
                    }
                    //if one size is used, the origina image have to be ignored
                    for (var imageSize in image.imageSizes) {
                        //image size used or this is ignored, the original image have to be ignored
                        if (image.imageSizes[imageSize].status.used == 1) {
                            return false;
                        }
                    }

                }
                //all case said, that the original image is unused or not have to be ignored
                return true;
            };

            //delete images, by id, sizeNames (if delete all is call this will contain 1+ sizeName, this is important for keep sync the DB, normally this can contain original or the other sizes name, but not both), and the reference of the original image
            var deleteFunction = function (id, sizeNames, image) {

                //add status before delete
                angular.forEach(sizeNames, function (sizeName) {
                    if (sizeName == 'original') {
                        //put all the imageSize in deleting status
                        angular.forEach(image.imageSizes, function (imageSize) {
                            imageSize.status.used = 3;
                        });
                    } else {
                        image.imageSizes[sizeName].status.used = 3;
                    }
                });

                //call the server for delete the image, the only values send are the id and sizeNames
                return ImagesResource.deleteByIdAndSize({id: id, sizeNames: sizeNames}).
                    $promise.then(function (statusBySize) {
                        //for each status, update the image status
                        angular.forEach(sizeNames, function (sizeName) {

                            if (sizeName == 'original') {

                                image.status = statusBySize[sizeName];
                                //if original is delete, all imageSize are deleted
                                angular.forEach(image.imageSizes, function (imageSize) {
                                    imageSize.status = statusBySize[sizeName];
                                });

                            } else {
                                //update only the imageSize
                                image.imageSizes[sizeName].status = statusBySize[sizeName];
                            }

                        });

                    });
            };

            //backup logic
            var makeBackupAndDelete = function (id, sizeNames, image) {
                //call the server for make the backup
                return BackupResource.make({id: id, sizeNames: sizeNames}).$promise.then(function (statusBySize) {

                    //backup made, see if all was good, if one size had problem with the update, this can not be delete
                    var sizeNamesAllGood = [];
                    //update the status
                    for (var key in sizeNames) {

                        if (sizeNames[key] == 'original') {
                            angular.forEach(image.imageSizes, function (imageSize, sizeName) {
                                imageSize.status = statusBySize[sizeNames[key]];
                            });

                        } else {
                            image.imageSizes[sizeNames[key]].status = statusBySize[sizeNames[key]];
                        }
                        //if all was good, i can delete the image
                        if (statusBySize[sizeNames[key]].inServer > 0) {
                            sizeNamesAllGood.push(sizeNames[key]);
                        }
                    }

                    deleteFunction(id, sizeNamesAllGood, image);

                });
            }

            //delete image by id, sizeName
            $scope.delete = function (id, sizeName, image) {

                if (sizeName == 'original') {
                    //nothing to do here
                    if ((image.status.used == 1 || !$scope.unusedImageSizesForOriginal(image))) {
                        return;
                    }
                } else if (image.imageSizes[sizeName].status.used == 1) { //nothing to do here
                    return;
                }

                refreshImages = true;

                //do backup if the backu functon is active
                if ($scope.options.backup) {

                    if (sizeName == 'original') {
                        image.status.used = 4;
                        angular.forEach(image.imageSizes, function (imageSize) {
                            imageSize.status.used = 4;
                        });
                    } else {
                        image.imageSizes[sizeName].status.used = 4;
                    }


                    return makeBackupAndDelete(id, [sizeName], image);

                } else {

                    return deleteFunction(id, [sizeName], image);

                }

            };


            $scope.deleteAll = function () {
                //desactivate the deleteAll button
                $scope.deleteAllButton = 0;

                var modalInstance = $uibModal.open({
                    animation: $scope.animationsEnabled,
                    templateUrl: 'deleteAllImageValidation.html',
                    controller: 'deleteAllImageCtrl'
                });


                modalInstance.result.then(function (validation) {

                    if (validation) {


                        var syncCall = [];

                        //search all image that can be deleted
                        angular.forEach($scope.images, function (image, key) {

                            //original image, if any imageSize in the ignore size in the list, and any other image used
                            if (image.sizeName === 'original' && $scope.options.ignoreSizes.length === 0 && $scope.unusedImageSizesForOriginal(image)) {

                                //i can delete the original image
                                syncCall.push($scope.delete(image.id, image.sizeName, image));

                            } else {

                                //search image sizes
                                var sizeNames = [];

                                for (var key in image.imageSizes) {
                                    //searching all image than can be deleted
                                    if ($scope.options.ignoreSizes.indexOf(image.imageSizes[key].sizeName) == -1
                                        && image.imageSizes[key].status.used == 0

                                    ) {
                                        sizeNames.push(image.imageSizes[key].sizeName);
                                    }
                                }

                                //nothing to do
                                if (sizeNames.length == 0) {

                                } else if ($scope.options.backup) {
                                    syncCall.push(makeBackupAndDelete(image.id, sizeNames, image));

                                } else {
                                    //skip the validation and go to delete function
                                    syncCall.push(deleteFunction(image.id, sizeNames, image));
                                }


                            }

                        });

                        $q.all(syncCall).then(function () {
                            //TODO: reactivate button
                        });
                    } else {
                        $scope.deleteAllButton = 1;
                    }

                }, function () {
                    $scope.deleteAllButton = 1;
                });


            };


        }
    ]).
    controller('deleteAllImageCtrl', function ($scope, $uibModalInstance, $interval) {

        $scope.time = 5;

        var timeCount = $interval(function () {
            $scope.time--;
            if (0 >= $scope.time) {
                stopTimeCount()
                $scope.ok();
            }

        }, 1000);

        var stopTimeCount = function () {

            if (angular.isDefined(timeCount)) {
                $interval.cancel(timeCount);
            }
        };


        $scope.ok = function () {
            $uibModalInstance.close(true);
        };

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    });



