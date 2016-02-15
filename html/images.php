<div id="imagesDNUI" ng-controller="ImagesCtrl">

    <div ng-if="callServerStatus==1">
        <p><?php _e('Fetching server...', 'dnui') ?></p>
    </div>
    <div ng-if="(callServerStatus==2)&&(images.length==0)">
        <p> <?php _e('Any pictures was found, try another page', 'dnui') ?></p>
    </div>

    <div ng-if="(callServerStatus==2)&&(images.length>0)">
        <table class="wp-list-table widefat fixed">
            <thead>
            <tr>

                <td class="manage-column column-title"><?php _e('Name', 'dnui') ?></td>
                <td class="manage-column column-title"><?php _e('Id', 'dnui') ?></td>
                <td class="manage-column column-title"><?php _e('Size', 'dnui') ?></td>
                <td class="manage-column column-title"><?php _e('Source', 'dnui') ?></td>
                <td class="manage-column column-title"><?php _e('In server', 'dnui') ?></td>
                <td class="manage-column column-title"><?php _e('Status', 'dnui') ?></td>
                <th class="manage-column column-title">
                    <button class="button action" style="color: #c50000" ng-if="deleteAllButton==1"
                            ng-click="deleteAll()">Delete all
                    </button>
                    <span ng-if="deleteAllButton==2" ng-click="deleteAll()"><?php _e('Waiting...', 'dnui') ?> </span>

                </th>
            </tr>
            </thead>
            <tbody ng-repeat="image in images">
            <tr ng-hide="!options.showUsedImage&&image.status.used==1" class="dnui_original">

                <td>{{image.name}}</td>
                </td>
                <td>({{image.id}}) {{image.sizeName}}
                    <span ng-if="options.ignoreSizes.indexOf(image.sizeName)>-1">&#9888;</span></td>
                <td>{{image.resolution}}</td>
                <td>{{image.srcOriginalImage}}</td>
                <td>
                    <span ng-show="image.status.inServer==-2"><?php _e('Unknown', 'dnui') ?></span>
                    <span ng-show="image.status.inServer==-1"><?php _e('Pending', 'dnui') ?></span>
                    <span ng-show="image.status.inServer==1" style="color: #00c700">&#10003;</span>
                    <span ng-show="image.status.inServer==0" style="color: #FF0000">X</span>
                    <span ng-show="image.status.inServer==4"
                          style="color: #0000FF"><?php _e('Backup...', 'dnui') ?></span>
                </td>
                <td>
                    <span ng-show="image.status.used==-3" style="color: #00c700"><?php _e('Error', 'dnui') ?></span>
                    <span ng-show="image.status.used==-2"><?php _e('Unknown', 'dnui') ?></span>
                    <span ng-show="image.status.used==-1"><?php _e('Pending', 'dnui') ?></span>
                    <span ng-show="image.status.used==1" style="color: #00c700"><?php _e('Used', 'dnui') ?></span>
                    <span ng-show="image.status.used==0" style="color: #c50000"><?php _e('Not used', 'dnui') ?></span>

                    <span ng-show="image.status.used==2" style="color: #FF0000"><?php _e('Deleted', 'dnui') ?></span>
                    <span ng-show="image.status.used==3" style="color: #FFFF00"><?php _e('Erasing...', 'dnui') ?></span>
                    <span ng-show="image.status.used==4" style="color: #0000FF"><?php _e('Backup...', 'dnui') ?></span>
                </td>
                <td>
                    <button
                        ng-if="image.status.used==0 && options.ignoreSizes.length==0 && unusedImageSizesForOriginal(image)"
                        class="button action"
                        style="color: #c50000" ng-click="delete(image.id,image.sizeName,image)">Delete
                    </button>
                </td>
            </tr>

            <tr ng-repeat="imageSize in image.imageSizes"
                ng-hide="!options.showUsedImage&&imageSize.status.used==1 || ( !options.showIgnoreSizes && options.ignoreSizes.indexOf(imageSize.sizeName)>-1)">
                <td>{{imageSize.name}}
                    </td>
                </td>
                <td>({{image.id}}) {{imageSize.sizeName}}
                    <span ng-if="options.ignoreSizes.indexOf(imageSize.sizeName)>-1">&#9888;</span></td>
                <td>{{imageSize.resolution}}</td>
                <td>{{imageSize.srcSizeImage}}</td>
                <td>
                    <span ng-show="imageSize.status.inServer==-2"><?php _e('Unknown', 'dnui') ?></span>
                    <span ng-show="imageSize.status.inServer==-1"><?php _e('Asking...', 'dnui') ?></span>
                    <span ng-show="imageSize.status.inServer==1" style="color: #00c700">&#10003;</span>
                    <span ng-show="imageSize.status.inServer==0" style="color: #FF0000">X</span>
                    <span ng-show="imageSize.status.inServer==4"
                          style="color: #0000FF"><?php _e('Backup...', 'dnui') ?></span>
                </td>
                <td>
                    <span ng-show="imageSize.status.used==-3" style="color: #c50000"><?php _e('Error', 'dnui') ?></span>
                    <span ng-show="imageSize.status.used==-2"><?php _e('Unknown', 'dnui') ?></span>
                    <span ng-show="imageSize.status.used==-1"><?php _e('Asking...', 'dnui') ?></span>
                    <span ng-show="imageSize.status.used==1" style="color: #00c700"><?php _e('Used', 'dnui') ?></span>
                    <span ng-show="imageSize.status.used==0"
                          style="color: #c50000"><?php _e('Not used', 'dnui') ?></span>
                    <span ng-show="imageSize.status.used==2"
                          style="color: #FF0000"><?php _e('Deleted', 'dnui') ?></span>
                    <span ng-show="imageSize.status.used==3"
                          style="color: #FFFF00"><?php _e('Erasing...', 'dnui') ?></span>
                    <span ng-show="imageSize.status.used==4"
                          style="color: #0000FF"><?php _e('Backup...', 'dnui') ?></span>
                </td>
                <td>
                    <button ng-if="imageSize.status.used==0 && !(options.ignoreSizes.indexOf(imageSize.sizeName)>-1))"
                            class="button action" style="color: #c50000"
                            ng-click="delete(image.id,imageSize.sizeName,image)">Delete
                    </button>
                </td>
            </tr>

            </tbody>


        </table>
        <div style="center">
            <uib-pagination total-items="totalImages" ng-model="options.numberPage" ng-change="changeNumberPage()"
                            items-per-page="options.imageShowInPage" class="pagination" boundary-link-numbers="true"
                            rotate="false"></uib-pagination>
        </div>
    </div>

    <script type="text/ng-template" id="deleteAllImageValidation.html">
        <div class="modal-header">
            <h3 class="modal-title"><?php _e('Are you sure you want to delete the images?', 'dnui') ?></h3>
        </div>
        <div class="modal-body">
            <?php _e('this will be automatically continue in', 'dnui') ?> {{time}} [s]

        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()"> <?php _e('Yes delete all', 'dnui') ?></button>
            <button class="btn btn-warning" type="button" ng-click="cancel()"><?php _e('Cancel', 'dnui') ?></button>
        </div>
    </script>
</div>
