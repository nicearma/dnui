<div id="backupDNUI" ng-controller="BackupCtrl">

    <div ng-if="callServerStatus==1">
        <p><?php _e('Fetching server...','dnui-delete-not-used-image-wordpress'); ?></p>
    </div>
    <div ng-if="(callServerStatus==2)&&(backups.length==0)">
        <p> <?php _e('Any backup was found','dnui-delete-not-used-image-wordpress'); ?></p>
    </div>
    <div ng-if="(callServerStatus==2)&&(backups.length>0)">

        <table class="wp-list-table widefat fixed">
            <thead>
            <tr>
                <td class="manage-column column-title"><?php _e('Backup ID (Image ID)','dnui-delete-not-used-image-wordpress'); ?></td>
                <td class="manage-column column-title"><?php _e('Restore','dnui-delete-not-used-image-wordpress'); ?></td>
                <td class="manage-column column-title"><?php _e('Delete','dnui-delete-not-used-image-wordpress'); ?></td>

            </tr>
            </thead>

            <tbody>
            <tr ng-repeat="backup in backups">
                <td>{{backup.id}} {{}}</td>
                <td>
                    <button class="button button-primary" ng-if="backup.status.inServer==1"
                            ng-click="restore(backup)"><?php _e('Restore','dnui-delete-not-used-image-wordpress'); ?></button>
                    <span ng-if="backup.status.inServer==4"><?php _e('Restoring...','dnui-delete-not-used-image-wordpress'); ?></span>
                    <span ng-if="backup.status.inServer==7"><?php _e('Restored and deleting...','dnui-delete-not-used-image-wordpress'); ?></span>
                    <span ng-if="backup.status.inServer==2 || backup.status.inServer==6"><?php _e('Restored','dnui-delete-not-used-image-wordpress'); ?></span>
                </td>
                <td>
                    <button class="button button-warning" ng-if="backup.status.inServer==1"
                            ng-click="deleteById(backup)"><?php _e('Delete','dnui-delete-not-used-image-wordpress'); ?></button>
                    <span ng-if="backup.status.inServer==3 || backup.status.inServer==6" ><?php _e('Deleted','dnui-delete-not-used-image-wordpress'); ?></span>
                    <span ng-if="backup.status.inServer==5 || backup.status.inServer==7"><?php _e('Deleting...','dnui-delete-not-used-image-wordpress'); ?></span>
                </td>

            </tr>
            </tbody>
        </table>
    </div>

</div>
