<script type="text/template" id="dnui_table_backup">
<thead>
    <tr>
        <th><?php _e('Restore','dnui') ?></th>
        <th><?php _e('ID','dnui') ?></th>
        <th><?php _e('Image of reference','dnui') ?></th>
    </tr>
</thead>
</script>


<script type="text/template" id="dnui_tbody_backup">
    <% var src; %>
    <tr>
        <td><input type="checkbox" class="backup" data-id="<%= backup.id %>" /></td>
        <td><%= backup.id %></td>
            
        <td>
        <%
        var only=true;            
        _.each(backup.files,function(file){
            if(file.indexOf('.backup')==-1){
                if(only){

                only=false;
                src=backup.urlBase+'/'+backup.id+'/'+file;
               %>
               <a href="<%= src %>">
            <img width="40px" height="40px" src="<%= src %>" />  </a>
                    <%
            }
            }
        }); %>    
          </td>
    </tr>

</script>


<script type="text/template" id="dnui_button_backup">
   
<h2>
<button class="button-primary dnui_restore_backup" type="button"><?php _e('Restore selected','dnui') ?> </button> 
<button class="button-primary dnui_cleanup_backup right" type="button"><?php _e('Cleanup all backup','dnui') ?> </button> 
<button class="button-primary dnui_delet_backup right"  type="button"><?php _e('Delete selected','dnui') ?> </button> 
</h2>

</script>