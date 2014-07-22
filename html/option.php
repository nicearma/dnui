<script type="text/template" id="dnui_option" >

<tbody>
<tr>
    <td scope="row"><p><?php _e('Quantity of image to search','dnui') ?></p>
    <p><small><?php _e('How many image you will see/search in the database, if you put a big number,'
            . '<br> you will have some problem with your site, see the plugin page for more information','dnui') ?></small></p></td>
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
<p><small><?php _e('Checked if You want to make some backup of the deleted image, but this will decrease the performance','dnui') ?></small></p></td>
    <td>
        <input class="dnui_check" data-dnui="backup"  <% if(option.backup) { %> checked <% } %> type="checkbox">
    </td>
</tr>
<tr>
    <td scope="row"><?php _e('Cleanup backups after','dnui') ?>(not yet)<sup>Version 1.4</sup></td>
    <td>
                <select disabled>
                     <option><?php _e('Manual','dnui') ?></option>
                     <option><?php _e('Every Day','dnui') ?></option>
                     <option><?php _e('Every Week','dnui') ?></option>

                </select>
        
    </td>
</tr>
<tr>
    <td scope="row"><?php _e('Ignore sizes','dnui') ?>(not yet)<sup>Version 1.4</sup></td>
    <td>
                <select disabled>
                     <option></option>
                     
                </select>
        
    </td>
</tr>

<tr>
    <td scope="row"><?php _e('Make only avaible for admin users','dnui') ?>(not yet)<sup>Version 1.4</sup></td>
    <td>
        <input class="dnui_check" data-dnui="admin" type="checkbox" disabled>
    </td>
</tr>

<tr>
    <td scope="row"><?php _e('Try to find match with gallery','dnui') ?>(not yet)<sup>Version 1.5</sup></td>
    <td>
        <input class="dnui_check" data-dnui="gallery" type="checkbox" disabled>
    </td>
</tr>

</tbody>
</script>