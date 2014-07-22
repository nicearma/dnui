<script type="text/template" id="dnui_table" >
    <thead> 
    <tr>
    <th class="check-column" scope="col"><input id="dnui-select-all" type="checkbox" ></th>
    <td class="manage-column column-title"><?php _e('Name', 'dnui') ?></td>
        <td class="manage-column column-title"><?php _e('id', 'dnui') ?></td>
        <td class="manage-column column-title"><?php _e('size', 'dnui') ?></td>
    <td class="manage-column column-title"><?php _e('Options', 'dnui') ?></td>
    <td class="manage-column column-title"><?php _e('Status', 'dnui') ?></td>
    </tr>
    </thead>
</script>


<script type="text/template" id="dnui_button">

<h2>
<button class="button-primary dnui_next"  <% if(option.get("cantInPage")!=lastValue){ %> disabled <% } %>type="button"><?php _e('Next','dnui') ?> </button> 
<button class="button-primary dnui_before" <%if(option.get("page") <= 0){ %> disabled <% } %>type="button"><?php _e('Before','dnui') ?> </button> 
<button class="button-primary dnui_delete right"  type="button"><?php _e('Delete all selected','dnui') ?> </button> 
</h2>
</script>

<script id="dnui_tbody">
    <% 
    var father,son,keys,image,href,id,base;
    var use="use";
    var dis="disabled";
    var notUse="not-use";
    father= image.meta_value;
	
    id=image.id;
    base=image.base; 
        
    %>

        <tr class="dnui_original" >
			<th class="check-column validate" scope="row">
                        
                        <input  data-id="<%= id %>" data-base="" data-type="original" class="dnui_father" type="checkbox" <% if(father.use||_.contains(father.sizes,true)) { %> disabled <% } %> >
                        
                        </th> 
			
			<td><%= father.file %></td>
			<td><%= '('+id+') '+ 'original' %></td>
			<td><%= father.width +'x'+father.height %></td>
			<td>
				<div id="<%= 'original_'+id %>" style="display:none;">
					<img src="<%= base+'/'+father.file %>" />
				</div>
			<% href='#TB_inline?width='+father.width+'&height='+father.height+'&inlineId=original_'+id; %>
				<a href="<%= href %>" class="thickbox"><span class="wp-menu-image dashicons-before dashicons-admin-comments"></span></a>
				<a href="<%= base+'/'+father.file %>" target="_blank" ><span class="wp-menu-image dashicons-before dashicons-admin-page"></span></a>
			</td>
			<td <% if ( father.use) { classUse=use;  }else{ classUse=notUse; } %> class="dnui <%= classUse %>"><%= classUse %></td>
			</tr>
		<%
		keys=_.pairs(father.sizes);
		_.each(keys, function(son){
                    
				%>
				<tr>
					<th class="check-column" scope="row"><input class="dnui_sons" data-id="<%= id %>" data-type="<%= son[0] %>" type="checkbox" <% if(son[1].use) { %> disabled <% } %>  ></th>
					<% href='#TB_inline?width='+son[1].width+'&height='+son[1].height+'&inlineId='+son[0]+'_'+id; %>
					<td><%= son[1].file %></td>
					<td><%= '('+id+') '+son[0] %></td>
					<td><%= son[1].width +'x'+son[1].height  %></td>
					<td>
						<div id="<%= son[0]+'_'+id %>" style="display:none;">
							<img src="<%= base+'/'+son[1].file %>" />
						</div>
						<a href="<%= href %>"  class="thickbox"><span class="wp-menu-image dashicons-before dashicons-admin-comments"></span></a>
						<a href="<%=  base+'/'+son[1].file %>" target="_blank" ><span class="wp-menu-image dashicons-before dashicons-admin-page"></span></a>
					</td>
					<td <% if ( son[1].use) { classUse=use;  }else{ classUse=notUse; } %> class="dnui <%= classUse %>"><%= classUse %></td>
			</tr>
    
    <% }); %>
  
    <% } 
     
    
    </script>
    
    <script id="dnui_wait">
    <h3><?php _e("searching in server, please wait some moment, if this action take a lots of time please change some values in the option area</b>","dnui") ?></h3>
 
            </script>
  <script id="dnui_delete">
    <h3><?php _e("Deleting images from server and database, please wait some moment","dnui") ?></h3>
  </script>