<script type="text/template" id="dnui_table" >
    <thead> 
    <tr>
    <th class="check-column" scope="col"><input id="dnui-select-all" type="checkbox" ></th>
    <td class="manage-column column-title"><?php _e('Name', 'dnui') ?></td>
    <td class="manage-column column-title"><?php _e('id', 'dnui') ?></td>
    <td class="manage-column column-title"><?php _e('size', 'dnui') ?></td>
    <td class="manage-column column-title"><?php _e('Options', 'dnui') ?></td>
    <td class="manage-column column-title"><?php _e('Exists', 'dnui') ?></td>
    <td class="manage-column column-title"><?php _e('Status', 'dnui') ?></td>
    </tr>
    </thead>
</script>


<script type="text/template" id="dnui_button">
<h2>
<button class="button-primary dnui_next"  <% if(option.get("cantInPage")!=lastValue){ %> disabled <% } %> type="button"><?php _e('Next','dnui') ?> </button> 
<button class="button-primary dnui_before"  <% if(option.get("page") > 0){ %> enabled <% }else { %> disabled <% } %> type="button"><?php _e('Before','dnui') ?> </button> 
<button class="button-primary dnui_delete right"  type="button"><?php _e('Delete all selected','dnui') ?> </button> 
</h2>
</script>

<script type="text/template" id="dnui_tbody">
    <% 
    var original,size,keys,image,href,id,base;
    var disable,contain;
    var use="use";
    
    var notUse="not-use";
    original= image.meta_value;
    
    id=image.id;
    base=image.base; 
    
    var ingoreB=false;
    
    if(!_.isUndefined(original.sizes)){
        for(var i in ignore){

        if(!_.isUndefined(original.sizes[ignore[i]])){
                ingoreB=true; 
        }

    }

    }
    
    disable=(original.use||ingoreB);
    
    %>

        <tr class="dnui_original"  <% if(show&&disable) { %> hidden <%  } %> > 
                        
			<th class="check-column  validate" scope="row"  >
                        
                        <input  data-id="<%= id %>" data-base="" data-type="original" class="dnui_original_check" type="checkbox" <% if(disable) { %> disabled <% } %> >
                        
                        </th> 
			
			<td><%= original.file %></td>
			<td><%= '('+id+') '+ 'original' %></td>
			<td><%= original.width +'x'+original.height %></td>
			<td>
				<div id="<%= 'original_'+id %>" style="display:none;">
					<img src="<%= base+'/'+original.file %>" />
				</div>
                                <% href='#TB_inline?width='+original.width+'&height='+original.height+'&inlineId=original_'+id; %>
				<a href="<%= href %>" class="thickbox"><span class="wp-menu-image dashicons-before dashicons-admin-comments"></span></a>
				<a href="<%= base+'/'+original.file %>" target="_blank" ><span class="wp-menu-image dashicons-before dashicons-admin-page"></span></a>
			       	<% if (!_.isUndefined(original.url)){ %>
                                <a href="<%= original.url %>" target="_blank"><span class="wp-menu-image dashicons-before dashicons-admin-post"></span></a>
                               <% } %>
                </td>
        <td <% if ( original.exists) { classUse='YES';  }else{ classUse='NO'; } %> class="dnui <%= classUse %>"><%= classUse %></td>
			<td <% if ( original.use) { classUse=use;  }else{ classUse=notUse; } %> class="dnui <%= classUse %>"><%= classUse %></td>
        </tr>
		<%
                            
                 console.log(original.sizes);
		if(!_.isUndefined(original.sizes)){
                   keys=_.pairs(original.sizes);
                
  
		_.each(keys, function(size){
                                contain=_.contains(ignore,size[0]);
                                disable=size[1].use||contain;
				%>
				<tr <% if(show&&disable||(contain&&showIgnore))  { %> hidden <%  } %>  >
					<th class="check-column" scope="row"><input class="dnui_sizes" data-id="<%= id %>" data-type="<%= size[0] %>" type="checkbox" <% if(disable) { %> disabled <% } %>  ></th>
					<% href='#TB_inline?width='+size[1].width+'&height='+size[1].height+'&inlineId='+size[0]+'_'+id; %>
					<td><%= size[1].file %></td>
					<td><%= '('+id+') '+size[0] %></td>
					<td><%= size[1].width +'x'+size[1].height  %></td>
					<td>
						<div id="<%= size[0]+'_'+id %>" style="display:none;">
							<img src="<%= base+'/'+size[1].file %>" />
						</div>
						<a href="<%= href %>"  class="thickbox"><span class="wp-menu-image dashicons-before dashicons-admin-comments"></span></a>
						<a href="<%=  base+'/'+size[1].file %>" target="_blank" ><span class="wp-menu-image dashicons-before dashicons-admin-page"></span></a>
                                                <% if (!_.isUndefined(size[1].url)){  %>
                                                    <a href="<%= size[1].url %>" target="_blank"><span class="wp-menu-image dashicons-before dashicons-admin-post"></span></a>
                                                <% } %>
                                        </td>
                                <td <% if ( size[1].exists) { classUse='YES';  }else{ classUse='NO'; } %> class="dnui <%= classUse %>"><%= classUse %></td>
	
					<td <% if ( size[1].use) { classUse=use;  }else{ classUse=notUse; } %> class="dnui <%= classUse %>"><%= classUse %></td>
			</tr>
    
    <% }); } %>

</script>
 
    <script type="text/template" id="dnui_wait">

    <h3><?php _e("searching in server, please wait some moment, if this action take a lots of time please change some values in the option area</b>","dnui") ?></h3>
</script>
    

<script type="text/template" id="dnui_delete">
    <h3><?php _e("Deleting images from server and database, please wait some moment","dnui") ?></h3>
</script>