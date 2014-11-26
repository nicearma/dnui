var DnuiVGeneral=Backbone.View.extend({
    el:"div#dnui_general"
});



/*--------------------------------------------BACKUP---------------------------------------------------------------------*/


var DnuiCBackupImage = Backbone.Collection.extend({
    url:ajaxurl
});

var DnuiCBackupSelected = Backbone.Collection.extend({
    url:ajaxurl
});



var DnuiVTabsBackup=Backbone.View.extend({
    el:"div#dnui_tabs_backup"
});

var DnuiVUlTabs=Backbone.View.extend({
    el:"ul#dnui_tabs_button"
    , events: {
        "click .dnui_db": "db",
        "click .dnui_bp": "bp"      
    },db:function(){
        this.dnuiVTableImage.render().$el.html(jQuery("#dnui_wait").html());
        this.dnuiCImageServer.fetch({type:'POST',data:{action: "dnui_all",option:this.dnuiMOption.attributes}});
    },bp:function(){
       this.dnuiCBackupImage.fetch({type:'POST',data:{action: "dnui_get_backup"}});
    }
});

var DnuiVBackupButton = Backbone.View.extend({
 
    tagName:"div",
    template: _.template(jQuery("#dnui_button_backup").html()),
    render: function() {
        
       this.$el.html(this.template());
      
       return this;
    },
    events: {
        "click .dnui_restore_backup": "restore",
        "click .dnui_delet_backup": "delet",
        "click .dnui_cleanup_backup": "cleanup"
    },
    restore:function(){
     
      this.dnuiCBackupSelected.fetch({type:'POST',data:{action: "dnui_restore_backup",restore:this.getSelected("input:checked.backup").toJSON()}});
    },
    delet:function(){
        this.dnuiCBackupSelected.fetch({type:'POST',data:{action: "dnui_delete_backup",delet:this.getSelected("input:checked.backup").toJSON()}});
    
    },
    cleanup:function(){
        this.dnuiCBackupSelected.fetch({type:'POST',data:{action: "dnui_cleanup_backup"}});
    
    },
    getSelected:function(ref){
        
        var dnuiCBackupSelected= new DnuiCBackupSelected();
        jQuery(ref).each(function(index,element){
            console.log(jQuery(element).data('id'));
            dnuiCBackupSelected.add({backup:jQuery(element).data('id')});
        });
        return dnuiCBackupSelected;
        
    }
    });
    

var DnuiVTableBackup=Backbone.View.extend({
    className:"wp-list-table widefat fixed",
   tagName:'table',
   id:'dnui_backup',
   template:_.template(jQuery("#dnui_table_backup").html()),
   render:function(){
       this.$el.html(this.template());
       return this;
    }
});


var DnuiVTbodyBackup=Backbone.View.extend({
   tagName:'tbody',
   template:_.template(jQuery("#dnui_tbody_backup").html()),
   render:function(){
       this.$el.html(this.template({backup:this.model}));
       return this;
    }
});



/********************************************ENDBACKUP*********************************************************************/


/*--------------------------------------------OPTION---------------------------------------------------------------------*/

/**
 * 
 * @type @exp;Backbone@pro;Model@call;extend
 */

var DnuiMOption = Backbone.Model.extend({
    url:ajaxurl
});



/**
 * Element for tabs html of div#dnui_tabs_option
 * @type @exp;Backbone@pro;View@call;extend
 */
var DnuiVTabsOption=Backbone.View.extend({
    el:"div#dnui_tabs_option"
});




/**
 * Table html with the option
 * @type @exp;Backbone@pro;View@call;extend
 */

var DnuiVTableOption=Backbone.View.extend({
    className:'form-table',
    tagName:'table',
     template: _.template(jQuery("#dnui_option").html()),
    render:function(){
       this.$el.html(this.template({option:this.dnuiMOption.attributes}));
       return this;
    },events:{
      "change .dnui_check" :"updateCheck",
      "change .dnui_select" :"updateSelect",
      "change .dnui_cant":"updateCant",
      "click .dnui_reset":"reset" ,
      "click .dnui_ignore":"ignore"
    },updateCheck:function(evt){
        //alert(evt);
         this.dnuiMOption.set(jQuery(evt.target).data("dnui"),jQuery(evt.target).attr("checked")=== 'checked'?true:false);
    },updateSelect:function(evt){
         this.dnuiMOption.set(jQuery(evt.target).data("dnui"), parseInt(jQuery(evt.target).val()));
    },updateCant: function(evt){
        var cant=jQuery(evt.target).val();
        if(cant<this.dnuiCImageServer.length){
            this.dnuiMOption.set("cantInPage",jQuery(evt.target).val());
        }else if(this.dnuiCImageServer.length==this.dnuiMOption.get("cantInPage")){
             this.dnuiMOption.set("cantInPage",jQuery(evt.target).val());
        }
        else{
            this.dnuiMOption.set({cantInPage : cant},{silent: true});
        }
    },reset:function(evt){
        this.dnuiMOption.set(jQuery(evt.target).data("dnui"),0);
        this.dnuiVUlTabs.db();
    },ignore:function(evt){
        var ignore=[];
        jQuery(evt.currentTarget).find("option:selected").each(function(key,input){
            console.log(jQuery(input));
            console.log(jQuery(input).data("ignore"));
           ignore.push(jQuery(input).data("ignore"));
        });
         this.dnuiMOption.set("ignore",ignore);
    }
});



/********************************************ENDOPTION*********************************************************************/


/*--------------------------------------------SEARCH---------------------------------------------------------------------*/


/**
 * 
 * @type @exp;Backbone@pro;Collection@call;extend
 */
var DnuiCImageDeleted = Backbone.Collection.extend({
    url:ajaxurl
});



/**
 * 
 * @type @exp;Backbone@pro;Collection@call;extend
 */
var DnuiCImageServer = Backbone.Collection.extend({
    url:ajaxurl
});




var DnuiVTabsDb=Backbone.View.extend({
    el:"div#dnui_tabs_db"
});


/**
 * Show the table with all image search in the database 
 * @type @exp;Backbone@pro;View@call;extend
 */

var DnuiVTableImage = Backbone.View.extend({
    className:"wp-list-table widefat fixed",
    tagName:"table",
    template: _.template(jQuery("#dnui_table").html()),
    render: function() {
      
       this.$el.html(this.template());
       return this;
    },
    events: {
        "click #dnui-select-all" : "all"
    },
    all : function(){
        var evt={};
        var self=this;
        jQuery('input.dnui_original_check').each(function(key,input){

            if(!jQuery(input).prop("disabled")){
                jQuery(input).prop("checked", !jQuery(input).prop("checked"));
            }
            evt.target=input;
          
          original(evt);

        });
    }
});




/**
 * 
 * @type @exp;Backbone@pro;View@call;extend
 */
var DnuiVTbody=Backbone.View.extend({
    tagName:"tbody",
    template: _.template(jQuery("#dnui_tbody").html()),
    render:function(){
       this.$el.html(this.template({image:this.image,ignore:this.option.get("ignore"),show:this.option.get("show"),showIgnore:this.option.get("showIgnore")}));
       return this;
    },
    events: {
        "click .dnui_original_check" : "original"
    },original :function(evt){
        
        original(evt);

    }
    
    
});

function original(evt){
        
        var id=jQuery(evt.target).data("id");

        if(!jQuery(evt.target).prop("disabled")){
            
           jQuery('input.dnui_sizes[data-id="'+id+'"]').each(function(key,input){

           if(!jQuery(input).prop("disabled")){
             
               jQuery(input).prop("disabled",!jQuery(input).prop("disabled"));
               jQuery(input).prop("checked",false);
           }else{
              
              jQuery(input).removeProp("disabled");
                jQuery(input).prop("checked",false);
           }
        });
    }else {

         jQuery('input.dnui_sizes[data-id="'+id+'"]').each(function(key,input){
             if(!jQuery(input).attr("disabled")){
               
               jQuery(input).attr("checked", !jQuery(input).attr("checked"));
           }             
         });
    }
   
 }

/**
 * 
 * @type @exp;Backbone@pro;View@call;extend
 */
var DnuiVTableButton = Backbone.View.extend({
 
    tagName:"div",
    template: _.template(jQuery("#dnui_button").html()),
    render: function() {
       this.$el.html(this.template({option:this.dnuiMOption,lastValue:this.dnuiCImageServer.length}));
       return this;
    },
    events: {
        "click .dnui_next": "next",
        "click .dnui_before": "before",
        "click .dnui_delete": "deleteSelectd"
       
    },
    next: function() {
              
              this.dnuiMOption.set("page", this.dnuiMOption.get("page") + 1);
              this.dnuiVUlTabs.db();
    },
    before: function() {
            if(this.dnuiMOption.get("page")>=1){
               this.dnuiMOption.set("page", this.dnuiMOption.get("page") - 1);
                this.dnuiVUlTabs.db(); 
            }
    },
    deleteSelectd: function(evt) {
        var imageToDelete={};
        var id, type;
         jQuery("input:checked").each(function(index,element){
            id=jQuery(element).data("id");
            type=jQuery(element).data("type");
            if(_.isUndefined(imageToDelete[id])){
                imageToDelete[id]={};
                 imageToDelete[id]["id"]=id;
                
                 imageToDelete[id]["toDelete"]=[];
            }
             imageToDelete[id]["toDelete"].push(type);

         });
         if(!_.isEmpty(imageToDelete)){
            
         imageToDelete=_.compact(imageToDelete);
  
         this.dnuiCImageDeleted.fetch({type:"POST",data:{action: "dnui_delete",imageToDelete:imageToDelete,updateInServer:this.dnuiMOption.get("updateInServer")}});
         }
     }
});




/********************************************ENDSEARCH*********************************************************************/




jQuery(document).ready(function() {
    
   var dnuiCBackupImage= new DnuiCBackupImage(); 
   var dnuiVBackupButton=new DnuiVBackupButton();
   var dnuiVTabsBackup= new DnuiVTabsBackup();
   var dnuiVTableBackup = new DnuiVTableBackup();
   
   var dnuiMOption = new DnuiMOption(); 
    
   var dnuiVUlTabs= new DnuiVUlTabs();
   dnuiVUlTabs.dnuiCBackupImage=dnuiCBackupImage;
   dnuiVUlTabs.dnuiMOption=dnuiMOption;
   
   
   
   dnuiVTabsBackup.$el.append(dnuiVBackupButton.render().el);
   dnuiVTabsBackup.$el.append(dnuiVTableBackup.render().el);
  
   dnuiVTableBackup.listenTo(dnuiCBackupImage,"sync", function(){
        dnuiVTableBackup.render();
        dnuiCBackupImage.forEach(function(backup){
            var dnuiVTbodyBackup= new DnuiVTbodyBackup({model:backup.attributes});
           
            
            dnuiVTableBackup.$el.append(dnuiVTbodyBackup.render().el);
            
        });

      
    });
        
   var dnuiCImageServer= new DnuiCImageServer();
    dnuiVUlTabs.dnuiCImageServer=dnuiCImageServer;
   
    
    var dnuiCImageDeleted= new DnuiCImageDeleted();
    /*
     *  Configuration of view's all this is only for backbone view 
     */
   var dnuiVTabsDb= new DnuiVTabsDb();
   var dnuiVTableImage= new DnuiVTableImage();
   
   dnuiVUlTabs.dnuiVTableImage=dnuiVTableImage;
   var dnuiVTableButton1 = new DnuiVTableButton();
   dnuiVTableButton1.dnuiCImageServer=dnuiCImageServer;
   dnuiVTableButton1.dnuiMOption=dnuiMOption;
   dnuiVTableButton1.dnuiCImageDeleted=dnuiCImageDeleted;
   dnuiVTableButton1.dnuiVUlTabs=dnuiVUlTabs;
   
   var dnuiVTableButton2 = new DnuiVTableButton();
   dnuiVTableButton2.dnuiCImageServer=dnuiCImageServer;
   dnuiVTableButton2.dnuiMOption=dnuiMOption;
   dnuiVTableButton2.dnuiCImageDeleted=dnuiCImageDeleted;
   dnuiVTableButton2.dnuiVUlTabs=dnuiVUlTabs;
   
   dnuiVTableButton1.render();
   dnuiVTableButton2.render();
   
   var dnuiVTabsOption= new DnuiVTabsOption();
   var dnuiVTableOption= new DnuiVTableOption();
   dnuiVTableOption.dnuiMOption=dnuiMOption;
   dnuiVTableOption.dnuiCImageServer=dnuiCImageServer;
   dnuiVTableOption.dnuiVUlTabs=dnuiVUlTabs;
   dnuiVTabsOption.$el.append(dnuiVTableOption.render().el);
   
   var dnuiVGeneral= new DnuiVGeneral();
   dnuiVGeneral.$el.tabs();
   
  
   
   /*
    *Update the view with the new option,  
    */
   dnuiVTableOption.listenTo(dnuiMOption,"sync", function(){
        dnuiVTableOption.render();
    });
   /*
   dnuiCImageServer.listenTo(dnuiMOption,"change", function(){
          
          dnuiCImageServer.fetch({type:'POST',data:{action: "dnui_all",option:dnuiMOption.attributes}});
    });
    */
    dnuiVTableImage.listenTo(dnuiCImageServer,"sync", function(){
        dnuiVTableButton1.render();
        dnuiVTableButton2.render();
        dnuiVTabsDb.$el.append(dnuiVTableButton1.el);
        dnuiVTabsDb.$el.append(dnuiVTableImage.render().el);
        dnuiVTabsDb.$el.append(dnuiVTableButton2.el);
        dnuiVTableOption.render();
        dnuiCImageServer.forEach(function(image){
            var dnuiVTbody= new DnuiVTbody({id:image.id});
            dnuiVTbody.image=image.attributes;
            dnuiVTbody.option=dnuiMOption;
            dnuiVTbody.render();
            dnuiVTableImage.$el.append(dnuiVTbody.el);
            
        });
    });
    
    dnuiCImageDeleted.listenTo(dnuiCImageDeleted,"sync", function(){
       dnuiVTableImage.render().$el.html("");
       if( dnuiCImageDeleted.at(0).get("isOk")){
          dnuiVUlTabs.db();
       }
        
    });
    
    var dnuiCBackupSelected= new DnuiCBackupSelected();
    dnuiVBackupButton.dnuiCBackupSelected=dnuiCBackupSelected;
    dnuiVUlTabs.listenTo(dnuiCBackupSelected,"sync", function(){
        dnuiVUlTabs.bp();
    });
    
    
    
   /*
    * Get the options from the database
    */
   dnuiMOption.fetch({type:'GET',data:{action:'dnui_get_option',success:function(){
              dnuiVUlTabs.db();
   }}});
   
});

