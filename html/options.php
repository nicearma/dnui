<div id="optionsDNUI" ng-controller="OptionsCtrl">
    <table class="wp-list-table widefat fixed">
        <tbody>
        <tr>
            <td scope="row">
                <p>
                  Plugin version
                </p>

            </td>
            <td>
               <p><b>{{options.version}}</b></p>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    Update in server
                </p>
                <p>
                    <small>
                    This plugin try to update the server status every action made, if you want the classic button save/update unchek this
                    </small>
                </p>

            </td>
            <td>
                <input type="checkbox"
                       ng-model="options.updateInServer" />
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    Backup system
                </p>
                <p>
                    <small>
                    Uncheck this if you don't want to use the backup system
                    Because this plugin will delete images and update information in the database,
                    is recommended to <b>MAKE BACKUPS EVERY TIME YOU USE THIS PLUGIN,
                    BUT THE MAIN USE OF THIS PLUGIN IS DELETE IMAGE AND UPDATE THE DATABASE,
                    SO THIS BACKUP SYSTEM IS VERY SIMPLE AND NOT BULLET PROOF,
                    SO USE ANOTHER BACKUP SYSTEM</b>
                    </small>
                </p>

            </td>
            <td>
                <input type="checkbox"
                       ng-model="options.backup" />
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    Create backup folder
                </p>
            </td>
            <td>
                <button ng-if="statusBackup.inServer<1" ng-click="makeBackupFolder()" >Create backup folder</button>
                <p style="color: #00FF00" ng-if="statusBackup.inServer>0">Backup folder exist</p>
                <p style="color: #FF0000" ng-if="statusBackup.inServer===-3">Can not create backup folder, ask for help</p>
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                   Show used image
                </p>
                <p>
                    <small>
                    This can clean the view, only show image to be deleted
                    </small>
                </p>

            </td>
            <td>
                <input type="checkbox"
                       ng-model="options.showUsedImage" />
            </td>
        </tr>
        <tr>
            <td scope="row">
                <p>
                    Only admin user
                </p>
                <p>
                    <small>
                    For the moment only Administrator can use this plugin
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
                   Ignore size list
                </p>
                <p>
                    <small>
                       This option can be used for put X size like used image, this prevent delete used image by other plugin or themes.
                        Example: The theme Basico use small-sizes but the plugin think that the small-sizes is not used, you can put small-sizes in the ignored size and the plugin will not let you delete this size
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
                    Show ignored sizes
                </p>
                <p>
                    <small>
                        This can clean the view, only show image to be deleted
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
                    Check if image is used in gallery (version 2.1)
                </p>
                <p>
                    <small>
                       If you want to check images in gallery use this option (this will decrease the performance if checked)
                    </small>
                </p>

            </td>

            <td>
                <input ng-disabled="true" type="checkbox"
                       ng-model="options.galleryCheck"/>
            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    Check in draft (version 2.1)
                </p>
                <p>
                    <small>
                        The plugin will check draft post and page
                    </small>
                </p>

            </td>

            <td>
                <input ng-disabled="true" type="checkbox"
                       ng-model="options.draftCheck"/>
            </td>

        </tr>
        <tr>
            <td scope="row">
                <p>
                    Page
                </p>
                <p>
                    <small>
                       Page number
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
                    Number of image show in page
                </p>
            </td>

            <td>
                <input ng-change="resetNumberPage()" type="number"
                       ng-model="options.imageShowInPage"/>
            </td>

        </tr>



        </tbody>

    </table>



</div>

