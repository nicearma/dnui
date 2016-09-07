<div id="optionsDNUI" ng-controller="OptionsCtrl">
    <button ng-if="!options.updateInServer"><?php _e("Save",'dnui-delete-not-used-image-wordpress'); ?></button>

    <h3><?php _e("General",'dnui-delete-not-used-image-wordpress'); ?></h3>
    <table class="wp-list-table widefat fixed">
        <tbody>


        <tr>
            <td scope="row">
                <p>
                    <?php _e("Plugin version",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

            </td>
            <td>
                <p><b>2.x</b></p>
            </td>
        </tr>

        <tr>
            <td scope="row">
                <p>
                    <?php _e("Only admin user",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("For the moment only Administrator can use this plugin",'dnui-delete-not-used-image-wordpress'); ?>
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
                    <?php _e("Update in server (make changes to database)",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("The plugin will try to update the server status every action made, if you want the classic button (bulk)save/update uncheck this",'dnui-delete-not-used-image-wordpress'); ?>
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
                    <?php _e("Backup system",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Uncheck this if you don't want to use the backup system, because this plugin will delete images and update information in the database, is recommended to MAKE BACKUPS EVERY TIME YOU USE THIS PLUGIN however the MAIN USE OF THIS PLUGIN IS DELETE IMAGE AND UPDATE THE DATABASE, SO THIS BACKUP SYSTEM IS VERY SIMPLE AND NOT BULLET PROOF, SO USE ANOTHER BACKUP SYSTEM where possible",'dnui-delete-not-used-image-wordpress'); ?>
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
                    <?php _e("Create backup folder",'dnui-delete-not-used-image-wordpress'); ?>
                </p>
            </td>
            <td>
                <button ng-if="statusBackup.inServer<1"
                        ng-click="makeBackupFolder()"> <?php _e("Create backup folder",'dnui-delete-not-used-image-wordpress'); ?></button>
                <p style="color: #00FF00"
                   ng-if="statusBackup.inServer>0"> <?php _e("Backup folder exist",'dnui-delete-not-used-image-wordpress'); ?></p>

                <p style="color: #FF0000"
                   ng-if="statusBackup.inServer===-3"> <?php _e("Can not create backup folder, ask for help",'dnui-delete-not-used-image-wordpress'); ?></p>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Page",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Page number",'dnui-delete-not-used-image-wordpress'); ?>
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
                    <?php _e("Number of images show on 1 page",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("A big number can kill your browser",'dnui-delete-not-used-image-wordpress'); ?>
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
                    <?php _e("Pagination max size",'dnui-delete-not-used-image-wordpress'); ?>

                </p>
                <p>
                    <small>
                    <?php _e("This will limit the pagination size",'dnui-delete-not-used-image-wordpress'); ?>
                    </small>
                </p>
            </td>

            <td>
               <input type="number" ng-model="options.maxSize" />

            </td>

        </tr>
         <tr>
            <td scope="row">
                <p>
                    <?php _e("Debug",'dnui-delete-not-used-image-wordpress'); ?>
                    <small>
                        <?php _e("Use this only if think the plugin is not workning fine",'dnui-delete-not-used-image-wordpress'); ?>
                        <br/>
                        <b> <?php _e("If you use this option for normal use, you will have performance issues",'dnui-delete-not-used-image-wordpress'); ?></b>
                    </small>
                </p>

            </td>

            <td>
               <input type="checkbox" ng-model="options.debug" />

            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Default options",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Restore default options",'dnui-delete-not-used-image-wordpress'); ?>
                    </small>
                </p>

            </td>

            <td>
                <button ng-click="restore()"> <?php _e("Restore",'dnui-delete-not-used-image-wordpress'); ?></button>

            </td>

        </tr>

        </tbody>
    </table>

    <h3>_
        <?php _e("Show",'dnui-delete-not-used-image-wordpress'); ?>
        
    </h3>
    <table class="wp-list-table widefat fixed">
        <tbody>

        <tr>
            <td scope="row">
                <p>
                    <?php _e("Show used image",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("This can clean the view; i.e. only show only the images you want to be deleted",'dnui-delete-not-used-image-wordpress'); ?>
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
                    <?php _e("Show ignored sizes",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("This can clean the view, i.e. it will show you only images that need to be deleted",'dnui-delete-not-used-image-wordpress'); ?>
                    </small>
                </p>

            </td>

            <td>
                <input type="checkbox"
                       ng-model="options.showIgnoreSizes"/>
            </td>

        </tr>

        <tr>
            <td scope="row">
                <p>
                    <?php _e("Ignore size list",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("This option can be used to select sizes you know are being used i.e. plugin or theme basics that do not refer to the image by name, this will prevent deletion of those sizes by this plugin at least.",'dnui-delete-not-used-image-wordpress'); ?>
                        <br/>
                        <?php _e("Example: The theme Basico uses small-sizes or thumbnails but the DNUI plugin doesn’t see a reference for this, the small-sizes or thumbnails will display as 'not used', you can put small-sizes or thumbnails in the ignored size and the plugin will not let you delete this size.",'dnui-delete-not-used-image-wordpress'); ?>
                    </small>
                </p>

            </td>
            <td>
                <select ng-model="options.ignoreSizes" multiple>
                    <option ng-repeat="size in sizes" value="{{size}}">{{size}}</option>

                </select>
            </td>
        </tr>
        </tbody>
    </table>
    <h3>
        <?php _e("Check",'dnui-delete-not-used-image-wordpress'); ?>
        <small>
            <?php _e("All this checks can decrease performance",'dnui-delete-not-used-image-wordpress'); ?>
        </small>
    </h3>
    <table class="wp-list-table widefat fixed">
        <tbody>
        <tr>


            <td scope="row">
                <p>
                    <?php _e("Check in excerpt",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Theme or plugin can use the short description to show images",'dnui-delete-not-used-image-wordpress'); ?>
                        <br/>
                        <?php _e("Search image in excerpt (short description).",'dnui-delete-not-used-image-wordpress'); ?>
                        <br/>
                        <?php _e("If you check the shorcode logic, shortcodes will be search to",'dnui-delete-not-used-image-wordpress'); ?>
                    </small>
                </p>

            </td>

            <td>
                <input type="checkbox" ng-model="options.excerptCheck"/>
            </td>

        </tr>
        <tr>


            <td scope="row">
                <p>
                    <?php _e("Check in Post meta",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Theme or plugin can use the post meta table to save information",'dnui-delete-not-used-image-wordpress'); ?>
                        <br/>
                        <?php _e("Search image in post meta, (this only will work if the image is in clear)" , 'dnui');
                        ?>
                            <br/>
                            <?php _e("(Ex: The plugin WooCommerce save products donwloabled with a direct ref like: toto.jpg, but the gallery image of the product will be save like (1,2,3...), each number is used to make reference to the image 1, image 2, image 3, etc..., so the plugin will show that the toto.jpg is used, but the image 1, image 2, image 3 will be showed like not used*" , 'dnui');
                            ?>
                             <br/>
                             <b>
                               <?php _e("Don't worry, if you have DNUI PRO and checked the WooCommerce option, the plugin will found out that the image 1, image 2, image are used",'dnui-delete-not-used-image-wordpress'); ?>
                                </b>
                    </small>
                </p>

            </td>

            <td>
                <input type="checkbox" ng-model="options.postMetaCheck"/>
            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    <?php _e("Check in gallery",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Search images in gallery",'dnui-delete-not-used-image-wordpress'); ?>
                        <br/>
                        <?php _e("Certain gallery makers generate various sizes for responsive website use.",'dnui-delete-not-used-image-wordpress'); ?>

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
                    <?php _e("Check in shortcodes",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("Search image in shortcode (the plugin will found out every shortcode used and get the html)",'dnui-delete-not-used-image-wordpress'); ?>
                        <br/>

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
                    <?php _e("Check in draft",'dnui-delete-not-used-image-wordpress'); ?>
                </p>

                <p>
                    <small>
                        <?php _e("The plugin will search in draft to",'dnui-delete-not-used-image-wordpress'); ?>
                    </small>
                </p>

            </td>

            <td>
                <input type="checkbox" ng-model="options.draftCheck"/>
            </td>

        </tr>
        </tbody>
    </table>



</div>

