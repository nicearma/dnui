<div id="imagesDNUI" ng-controller="ImagesCtrl">
    <table class="wp-list-table widefat fixed">
        <thead>
        <tr>

            <td class="manage-column column-title"><?php _e('Name', 'dnui') ?></td>
            <td class="manage-column column-title"><?php _e('Id', 'dnui') ?></td>
            <td class="manage-column column-title"><?php _e('Size', 'dnui') ?></td>
            <td class="manage-column column-title"><?php _e('Source', 'dnui') ?></td>
            <td class="manage-column column-title"><?php _e('In server', 'dnui') ?></td>
            <td class="manage-column column-title"><?php _e('Status', 'dnui') ?></td>
            <th class="manage-column column-title" ><button class="button action" style="color: #c50000" ng-if="deleteAllButton" ng-click="deleteAll()" >Delete all</button></th>
        </tr>
        </thead>
        <tbody ng-repeat="image in images">
        <tr ng-hide="!options.showUsedImage&&image.status.used==1" class="dnui_original">

            <td>{{image.name}}</td>
            <td>({{image.id}}) {{image.sizeName}}</td>
            <td>{{image.resolution}}</td>
            <td>{{image.srcOriginalImage}}</td>
            <td>
                <span ng-show="image.status.inServer==-2"><?php _e('Unknown', 'dnui') ?></span>
                <span ng-show="image.status.inServer==-1"><?php _e('Pending', 'dnui') ?></span>
                <span ng-show="image.status.inServer==1" style="color: #00c700">&#10003;</span>
                <span ng-show="image.status.inServer==0" style="color: #FF0000">X</span>
                <span ng-show="image.status.inServer==4" style="color: #0000FF"><?php _e('Backup...', 'dnui') ?></span>
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
            <td><button  ng-if="image.status.used==0 && options.ignoreSizes.length==0" class="button action" style="color: #c50000" ng-click="delete(image.id,image.sizeName,image)" >Delete</button></td>
        </tr>

        <tr   ng-repeat="imageSize in image.imageSizes" ng-hide="!options.showUsedImage&&imageSize.status.used==1 || ( !options.showIgnoreSizes && options.ignoreSizes.indexOf(imageSize.sizeName)>-1)">
            <td>{{imageSize.name}}</td>
            <td>({{image.id}}) {{imageSize.sizeName}}</td>
            <td>{{imageSize.resolution}}</td>
            <td>{{imageSize.srcSizeImage}}</td>
            <td>
                <span ng-show="imageSize.status.inServer==-2"><?php _e('Unknown', 'dnui') ?></span>
                <span ng-show="imageSize.status.inServer==-1"><?php _e('Asking...', 'dnui') ?></span>
                <span ng-show="imageSize.status.inServer==1" style="color: #00c700">&#10003;</span>
                <span ng-show="imageSize.status.inServer==0" style="color: #FF0000">X</span>
                <span ng-show="imageSize.status.inServer==4" style="color: #0000FF"><?php _e('Backup...', 'dnui') ?></span>
            </td>
            <td>
                <span ng-show="imageSize.status.used==-3" style="color: #c50000"><?php _e('Error', 'dnui') ?></span>
                <span ng-show="imageSize.status.used==-2"><?php _e('Unknown', 'dnui') ?></span>
                <span ng-show="imageSize.status.used==-1"><?php _e('Asking...', 'dnui') ?></span>
                <span ng-show="imageSize.status.used==1" style="color: #00c700"><?php _e('Used', 'dnui') ?></span>
                <span ng-show="imageSize.status.used==0" style="color: #c50000"><?php _e('Not used', 'dnui') ?></span>
                <span ng-show="imageSize.status.used==2" style="color: #FF0000"><?php _e('Deleted', 'dnui') ?></span>
                <span ng-show="imageSize.status.used==3" style="color: #FFFF00"><?php _e('Erasing...', 'dnui') ?></span>
                <span ng-show="imageSize.status.used==4" style="color: #0000FF"><?php _e('Backup...', 'dnui') ?></span>
            </td>
            <td><button ng-if="imageSize.status.used==0 && !(options.ignoreSizes.indexOf(imageSize.sizeName)>-1)" class="button action" style="color: #c50000" ng-click="delete(image.id,imageSize.sizeName,imageSize)" >Delete</button></td>
        </tr>

        </tbody>


    </table>

    <uib-pagination total-items="totalImages" ng-model="options.numberPage" ng-change="changeNumberPage()" items-per-page="options.imageShowInPage" class="pagination" boundary-link-numbers="true" rotate="false"></uib-pagination>
</div>