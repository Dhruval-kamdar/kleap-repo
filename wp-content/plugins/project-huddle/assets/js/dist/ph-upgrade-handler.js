"use strict";jQuery(document).ready(function(s){if("undefined"!=typeof phUpgradeHandler){console.log("DEBUG: Upgrade handler first upgrades is "+phUpgradeHandler.upgrade);var e=new function(r){this.upgrade=r,this.process=function(r,i,n){r=void 0!==r?r:0,i=void 0!==i?i:0,n=void 0!==n?n:[],this.upgrade&&(console.log("Upgrade: "+this.upgrade),console.log(n),s.post(ajaxurl,{upgrade:this.upgrade,step:parseInt(r),total_steps:parseInt(i),args:n,action:"ph_upgrade_handler"},function(r){var i=s("#ph_upgrade_"+e.upgrade);try{r=s.parseJSON(r)}catch(s){return i.find(".spinner").css("display","none").css("visibility","hidden"),i.find(".dashicons-no").css("display","block"),i.find(".ph-upgrade-handler__errors__text").html("Bad Response :'(<br/>"+s+"<br />"+r),void i.find(".ph-upgrade-handler__errors").slideDown()}if(console.log("DEBUG: Upgrade handler step response: "),console.log(r),null==r)return i.find(".spinner").css("display","none").css("visibility","hidden"),i.find(".dashicons-no").css("display","block"),i.find(".ph-upgrade-handler__errors__text").html("Empty Response :'("),void i.find(".ph-upgrade-handler__errors").slideDown();if(r.errors){i.find(".spinner").css("display","none").css("visibility","hidden"),i.find(".dashicons-no").css("display","block");var n="";return s.each(r.errors,function(s,e){n=n+"["+s+"] "+e+"<br />"}),i.find(".ph-upgrade-handler__errors__text").html("Processing Error :'(<br />"+n),i.find(".ph-upgrade-handler__errors").slideDown(),void s("#progressbar_"+r.upgrade).slideUp()}if(s("#progressbar_"+r.upgrade).progressbar({value:r.step/r.total_steps*100}),i.find(".spinner").css("display","block").css("visibility","visible"),i.find(".dashicons-no").css("display","none"),i.find(".inside").slideDown(),null!=r.complete)return i.find(".inside").slideUp(),i.find(".spinner").css("display","none").css("visibility","hidden"),i.find(".dashicons-yes").css("display","block"),null!=r.nextUpgrade?(e.upgrade=r.nextUpgrade,s("#ph_upgrade_"+e.upgrade).find(".spinner").css("display","block").css("visibility","visible"),s("#ph_upgrade_"+e.upgrade).find(".inside").slideDown(),void e.process()):(console.log('DEBUG: Upgrade handler says "It is finished!"'),void s(".ph-upgrade-complete").show());e.process(r.step,r.total_steps,r.args)}).fail(function(){alert("There was an error with the upgrade. Please reload the page and try again. If you see this message repeatedly, please reach out to support!")}))}}(phUpgradeHandler.upgrade);s(".progressbar").progressbar({value:0});var r=s("#ph_upgrade_"+e.upgrade);r.find(".spinner").css("display","block").css("visibility","visible"),r.find(".dashicons-no").css("display","none"),r.find(".inside").slideDown(),e.process()}else document.location.href="admin.php"});
