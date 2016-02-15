<div id="optionsDNUI" ng-controller="OptionsCtrl">

    <table class="wp-list-table widefat fixed">
        <tbody>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Plugin version", 'dnui'); ?>
                </p>

            </td>
            <td>
                <p><b>{{options.version}}</b></p>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Update in server (make changes to database)", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("The plugin will try to update the server status every action made, if you want the classic button (bulk)save/update uncheck this", 'dnui'); ?>
                    </small>
                </p>

            </td>
            <td>
                <input ng-disabled="true" type="checkbox"
                       ng-model="options.updateInServer"/>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Backup system", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Uncheck this if you don't want to use the backup system, because this plugin will delete images and update information in the database, is recommended to MAKE BACKUPS EVERY TIME YOU USE THIS PLUGIN however the MAIN USE OF THIS PLUGIN IS DELETE IMAGE AND UPDATE THE DATABASE, SO THIS BACKUP SYSTEM IS VERY SIMPLE AND NOT BULLET PROOF, SO USE ANOTHER BACKUP SYSTEM where possible", 'dnui'); ?>
                    </small>
                </p>

            </td>
            <td>
                <input ng-disabled="disabledBackupOption" type="checkbox"
                       ng-model="options.backup"/>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Create backup folder", 'dnui'); ?>
                </p>
            </td>
            <td>
                <button ng-if="statusBackup.inServer<1"
                        ng-click="makeBackupFolder()"> <?php _e("Create backup folder", 'dnui'); ?></button>
                <p style="color: #00FF00"
                   ng-if="statusBackup.inServer>0"> <?php _e("Backup folder exist", 'dnui'); ?></p>

                <p style="color: #FF0000"
                   ng-if="statusBackup.inServer===-3"> <?php _e("Can not create backup folder, ask for help", 'dnui'); ?></p>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Show used image", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("This can clean the view; i.e. only show only the images you want to be deleted", 'dnui'); ?>
                    </small>
                </p>

            </td>
            <td>
                <input type="checkbox"
                       ng-model="options.showUsedImage"/>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Only admin user", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("For the moment only Administrator can use this plugin", 'dnui'); ?>
                    </small>
                </p>

            </td>
            <td>
                <input ng-disabled="true" type="checkbox"
                       ng-model="options.admin" ng-disabled/>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Ignore size list", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("This option can be used to select sizes you know are being used i.e. plugin or theme basics that do not refer to the image by name, this will prevent deletion of those sizes by this plugin at least.", 'dnui'); ?>
                        <br/>
                        <?php _e("Example: The theme Basico uses small-sizes or thumbnails but the DNUI plugin doesn’t see a reference for this, the small-sizes or thumbnails will display as 'not used', you can put small-sizes or thumbnails in the ignored size and the plugin will not let you delete this size.", 'dnui'); ?>
                    </small>
                </p>

            </td>
            <td>
                <select ng-model="options.ignoreSizes" multiple>
                    <option ng-repeat="size in sizes" value="{{size}}">{{size}}</option>

                </select>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Check if image is used in gallery", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("If you want to check images in gallery use this option (this will decrease the performance if checked and is call to database is done only once, if you add one gallery, you have to refresh this plugin)", 'dnui'); ?>
                        <br/>
                        <?php _e("To be clear if you make any galleries after using this plugin you will have to use the plugin again as WordPress and certain gallery makers generate various sizes for responsive website use.", 'dnui'); ?>

                    </small>
                </p>

            </td>

            <td>
                <input type="checkbox" ng-model="options.galleryCheck"/>
            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Check if image is used in shortcodes", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("If you want to check images in shortcodes use this option (this will decrease the performance if checked and is call to database is done only once, if you add one shortcode, you have to refresh this plugin)", 'dnui'); ?>
                        <br/>
                        <?php _e("To be clear if you make any galleries after using this plugin you will have to use the plugin again as WordPress and certain shortcode makers generate various sizes for responsive website use.", 'dnui'); ?>
                    </small>
                </p>

            </td>

            <td>
                <input type="checkbox" ng-model="options.shortCodeCheck"/>
            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Check in draft", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("The plugin will check draft post and page", 'dnui'); ?>
                    </small>
                </p>

            </td>

            <td>
                <input type="checkbox" ng-model="options.draftCheck"/>
            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Page", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Page number", 'dnui'); ?>
                    </small>
                </p>

            </td>

            <td>
                {{options.numberPage}}

            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Number of images show on 1 page", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("A big number can kill your browser", 'dnui'); ?>
                    </small>
                </p>
            </td>

            <td>
                <input ng-change="resetNumberPage()" type="number"
                       ng-model="options.imageShowInPage"/>
            </td>

        </tr>

        <tr>
            <td scope="row">
                <p>
                    <?php _e("Default options", 'dnui'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Restore default options", 'dnui'); ?>
                    </small>
                </p>

            </td>

            <td>
                <button ng-click="restore()"> <?php _e("Restore", 'dnui'); ?></button>

            </td>

        </tr>

        </tbody>

    </table>


</div>

