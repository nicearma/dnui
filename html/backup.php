<div id="backupDNUI" ng-controller="BackupCtrl">

    <div ng-if="callServerStatus==1">
        <p><?php _e('Fetching server...', 'dnui') ?></p>
    </div>
    <div ng-if="(callServerStatus==2)&&(backups.length==0)">
        <p> <?php _e('Any backup was found', 'dnui') ?></p>
    </div>
    <div ng-if="(callServerStatus==2)&&(backups.length>0)">

        <table class="wp-list-table widefat fixed">
            <thead>
            <tr>
                <td class="manage-column column-title"><?php _e('Backup ID (Image ID)', 'dnui') ?></td>
                <td class="manage-column column-title"><?php _e('Restore', 'dnui') ?></td>
                <td class="manage-column column-title"><?php _e('Delete', 'dnui') ?></td>
            </tr>
            </thead>

            <tbody>
            <tr ng-repeat="backup in backups">
                <td>{{backup.id}} {{}}</td>
                <td>
                    <button ng-if="backup.status.inServer==1"
                            ng-click="restore(backup)"><?php _e('Restore', 'dnui') ?></button>
                </td>
                <td>
                    <button ng-if="backup.status.inServer==1"
                            ng-click="deleteById(backup)"><?php _e('Delete', 'dnui') ?></button>
                </td>

            </tr>
            </tbody>
        </table>
    </div>

</div>
