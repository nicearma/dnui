<script type="text/template" id="dnui_option" >
<p>For any other information about how work this plugin you can go to <a href="http://www.nicearma.com/delete-not-used-image-wordpress-dnui/" >Nicearma DNUI page</a>
<tbody>
<tr>
    <td scope="row"><p><?php _e('Quantity of image to search','dnui') ?></p>
    <p><small><?php _e('How many image you will see/search in the database, if you put a big number,'
            . '<br> you will have some problem with your site, see the plugin page for more information ( Advice use max:100, min:25 )','dnui') ?></small></p></td>
    <td><input class="dnui_cant" data-dnui="cantInPage" type="number" min="1" name="quantity" value="<%= option.cantInPage %>"> </td>
</tr>
<tr>
    <td scope="row"><p><?php _e('Order','dnui') ?> </p>
            <p><small><?php _e('First : the oldest will be displayed','dnui') .'<br>'. _e('Last : the newest will be displayed','dnui') ?></small></p></td>
    <td>
        <select class="dnui_select"  data-dnui="order">
            <option <% if(option.order==0){  %>  selected <% } %> value="0">
            <?php _e('First','dnui') ?>
            </option>

            <option <%  if(option.order==1){ %>  selected <% } %> value="1">
            <?php _e('Last','dnui') ?>
            </option>
        </select>
    </td>
</tr>
<tr>
    <td scope="row"><?php _e('Update database if image is not in server (use with precaution)','dnui') ?>
         <p><small><?php _e('Use if you know that the image does not exist on the server, if not checked '
                 . '<br> the plugin will NOT update the database, so you will see the image again in the list'
                 . '<br> THIS ONLY WORK FOR THE CHILD SIZE','dnui') ?></small></p></td>
    <td>
        <input class="dnui_check" type="checkbox" data-dnui="updateInServer"  <% console.log(option.updateInServer); if(option.updateInServer) { %> checked <% } %> >
    </td>
</tr>
<tr>     
    <td scope="row"><p><?php _e('Number of page','dnui') ?></p>
        <p><small><?php _e('If you have 100 image in your server, and in Quantity 20 and here 2, the plugin will show the list from 40 to 60','dnui') ?></small></p></td>
    <td>
        <input type="number" value="<%= option.page  %>" disabled/>
        <button data-dnui="page" class="button-primary dnui_reset"  ><?php _e('Reset','dnui') ?></button>
    </td>
</tr>
<tr>
    <td scope="row"><p><?php _e('Make backup','dnui') ?></p>
<p><small><?php _e('Checked if you want  backup  the deleted image, (this will decrease the performance and a very simple logic,<br>'
        . ' so if you want a very good backup system use another backup plugin)','dnui') ?></small></p></td>
    <td>
        <input class="dnui_check" data-dnui="backup"  <% if(option.backup) { %> checked <% } %> type="checkbox">
    </td>
</tr>
<tr>
    <td scope="row"><?php _e('Cleanup backups after','dnui') ?>
             <p><small><?php _e('If you use the backup system and you dont want to delete every time the backup folder, use the option every day<br>'
                     . 'The day will be one day after the use of this plugin','dnui') ?></small></p>       
            </td>
    <td>
                <select disabled class="dnui_select"  data-dnui="cron">
                     <option <%  if(option.cron==0){ %>  selected <% } %> value="0"><?php _e('Manual','dnui') ?></option>
                     <option  <%  if(option.cron==1){ %>  selected <% } %> value="1"><?php _e('Every Day','dnui') ?></option>
                </select>
        
    </td>
</tr>
<tr>
    <td scope="row"><?php _e('Show only image not used','dnui') ?></td>
    <td>
        <input class="dnui_check" data-dnui="show" type="checkbox" <% if(option.show) { %> checked <% } %> >
    </td>
</tr>
<tr>
    <td scope="row"><p><?php _e('Ignore sizes','dnui') ?></p>
             <p><small><?php _e('If you select size from this list, the plugin will not very the image, is like the sizes selected are used,<br>'
                     . ' (the size selected and the original image can\'t be delete)'
                     . ' <br>Secret: Use "ctrl" or "shift" :)','dnui') ?></small></p>    
            </td>
    <td>
                <select multiple class="dnui_ignore">
                         
                            <?php $sizes= get_intermediate_image_sizes();
 foreach ($sizes as $size) {
     echo '<option <% if(_.contains(option.ignore,"'.$size.'")) { %> selected <% } %>  data-ignore="'.$size.'">'.$size.'</option>';
 } ?>
                     
                     
                </select>
        
    </td>
</tr>

<tr>
    <td scope="row"><?php _e('Not show images from ignore size','dnui') ?>
             <p><small><?php _e('Normally all sizes will show, but if you this option is checked you will only see the other sizes not selected in the list (only for visual effect)','dnui') ?></small></p>    
    
            </td>
    <td>
        <input class="dnui_check" data-dnui="showIgnore" type="checkbox" <% if(option.showIgnore) { %> checked <% } %> >
    </td>
    </tr>
<tr>
    <td scope="row"><?php _e('Make only avaible for admin users','dnui') ?></td>
    <td>
        <input class="dnui_check" data-dnui="admin" type="checkbox"  <% if(option.admin) { %> checked <% } %>>
                    
    </td>
    
</tr>

<tr>
    <td scope="row"><?php _e('Try to find match with gallery','dnui') ?>
             <p><small><?php _e('Use this option if you have put some times galleries in the post or page,<br>'
                     . 'this way the plugin will try to find all the galleries and put the image like used (work with the native gallery)<br>'
                     . 'This option will consume more resources of you server','dnui') ?></small></p>    
    
            </td>
    <td>
        <input class="dnui_check" data-dnui="galleryCheck" type="checkbox"  <% if(option.galleryCheck) { %> checked <% } %>>
       
                    
    </td>
</tr>
<tr>
    <td scope="row"><?php _e('SQL: Don\'t use revision and draft for the search','dnui') ?>
             <p><small><?php _e('If you want to make a search only in posts and pages published; uncheck this<br>'
                     . 'If this option is checked the result will be more accurate but the search will be lest performance<br>'
                     . 'So you have to chose','dnui') ?></small></p>    
    
            </td>
    <td>
        <input class="dnui_check" data-dnui="without" type="checkbox"  <% if(option.without) { %> checked <% } %>>
       
                    
    </td>
</tr>


</tbody>
</script>