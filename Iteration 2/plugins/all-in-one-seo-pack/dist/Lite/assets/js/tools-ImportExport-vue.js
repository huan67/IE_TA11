(window["aioseopjsonp"]=window["aioseopjsonp"]||[]).push([["tools-ImportExport-vue","tools-partials-BackupSettings-vue","tools-partials-ExportSettings-vue","tools-partials-ImportAioseo-vue","tools-partials-ImportOthers-vue"],{"0687":function(t,s,e){"use strict";e.r(s);var i=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("div",{staticClass:"aioseo-tools-import-export"},[e("grid-row",[e("grid-column",{attrs:{md:"6"}},[e("import-aioseo"),e("import-others")],1),e("grid-column",{attrs:{md:"6"}},[e("export-settings")],1)],1),e("grid-row",[e("grid-column",[e("backup-settings")],1)],1)],1)},o=[],n=e("1407"),a=e("824c"),r=e("a551"),l=e("9b1d"),c={components:{BackupSettings:n["default"],ExportSettings:a["default"],ImportAioseo:r["default"],ImportOthers:l["default"]},data:function(){return{strings:{exportBackupSettings:this.$t.__("Export / Backup Settings",this.$td)}}},computed:{},methods:{}},u=c,p=(e("c343"),e("2877")),d=Object(p["a"])(u,i,o,!1,null,null,null);s["default"]=d.exports},1407:function(t,s,e){"use strict";e.r(s);var i=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("core-card",{staticClass:"aioseo-backup-settings",attrs:{slug:"backupSettings",toggles:!1,"no-slide":"","header-text":t.strings.backupSettings},scopedSlots:t._u([{key:"header-icon",fn:function(){return[e("svg-history")]},proxy:!0}])},[t.backupsDeleteSuccess?e("core-alert",{attrs:{type:"green"}},[t._v(" "+t._s(t.strings.backupSuccessfullyDeleted)+" ")]):t._e(),t.backupsRestoreSuccess?e("core-alert",{attrs:{type:"green"}},[t._v(" "+t._s(t.strings.backupSuccessfullyRestored)+" ")]):t._e(),t.backups.length?t._e():e("div",{staticClass:"aioseo-section-description"},[t._v(" "+t._s(t.strings.noBackups)+" ")]),t.backups.length?[e("div",{staticClass:"backups-table"},[e("div",{staticClass:"backups-rows"},t._l(t.backups,(function(s,i){return e("div",{key:i,staticClass:"backup-row",class:{even:0===i%2}},[e("div",{staticClass:"backup-name",domProps:{innerHTML:t._s(t.getBackupName(s))}}),e("div",{staticClass:"backup-actions"},[e("core-tooltip",{attrs:{type:"action"},scopedSlots:t._u([{key:"tooltip",fn:function(){return[t._v(" "+t._s(t.strings.restore)+" ")]},proxy:!0}],null,!0)},[e("svg-refresh",{nativeOn:{click:function(e){return t.maybeRestoreBackup(s)}}})],1),e("core-tooltip",{attrs:{type:"action"},scopedSlots:t._u([{key:"tooltip",fn:function(){return[t._v(" "+t._s(t.strings.delete)+" ")]},proxy:!0}],null,!0)},[e("svg-trash",{nativeOn:{click:function(e){return t.maybeDeleteBackup(s)}}})],1)],1)])})),0)])]:t._e(),e("base-button",{attrs:{type:"blue",size:"medium",loading:t.loading},on:{click:t.processCreateBackup}},[e("svg-circle-plus"),t._v(" "+t._s(t.strings.createBackup)+" ")],1),t.showModal?e("core-modal",{attrs:{"no-header":""},scopedSlots:t._u([{key:"body",fn:function(){return[e("div",{staticClass:"aioseo-modal-body"},[e("button",{staticClass:"close",on:{click:function(s){s.stopPropagation(),t.showModal=!1}}},[e("svg-close",{on:{click:function(s){t.showModal=!1}}})],1),e("h3",[t._v(t._s(t.areYouSure))]),e("div",{staticClass:"reset-description",domProps:{innerHTML:t._s(t.strings.actionCannotBeUndone)}}),e("base-button",{attrs:{type:"blue",size:"medium"},on:{click:t.processBackupAction}},[t._v(" "+t._s(t.iAmSure)+" ")]),e("base-button",{attrs:{type:"gray",size:"medium"},on:{click:function(s){t.showModal=!1}}},[t._v(" "+t._s(t.strings.noChangedMind)+" ")])],1)]},proxy:!0}],null,!1,1131809547)}):t._e()],2)},o=[],n=e("5530"),a=e("2f62"),r={data:function(){return{timeout:null,backupToDelete:null,backupToRestore:null,backupsDeleteSuccess:!1,showModal:!1,backupsRestoreSuccess:!1,loading:!1,strings:{backupSettings:this.$t.__("Backup Settings",this.$td),areYouSureDeleteBackup:this.$t.__("Are you sure you want to delete this backup?",this.$td),areYouSureRestoreBackup:this.$t.__("Are you sure you want to restore this backup?",this.$td),yesDeleteBackup:this.$t.__("Yes, I want to delete this backup",this.$td),yesRestoreBackup:this.$t.__("Yes, I want to restore this backup",this.$td),noChangedMind:this.$t.__("No, I changed my mind",this.$td),actionCannotBeUndone:this.$t.__("This action cannot be undone.",this.$td),noBackups:this.$t.__("You have no saved backups.",this.$td),createBackup:this.$t.__("Create Backup",this.$td),restore:this.$t.__("Restore",this.$td),delete:this.$t.__("Delete",this.$td),backupSuccessfullyDeleted:this.$t.__("Success! The backup was deleted.",this.$td),backupSuccessfullyRestored:this.$t.__("Success! The backup was restored.",this.$td)}}},computed:Object(n["a"])(Object(n["a"])({},Object(a["e"])(["backups"])),{},{areYouSure:function(){return this.backupToDelete?this.strings.areYouSureDeleteBackup:this.strings.areYouSureRestoreBackup},iAmSure:function(){return this.backupToDelete?this.strings.yesDeleteBackup:this.strings.yesRestoreBackup}}),methods:Object(n["a"])(Object(n["a"])({},Object(a["b"])(["createBackup","deleteBackup","restoreBackup"])),{},{processCreateBackup:function(){var t=this;this.loading=!0,this.createBackup().then((function(){t.loading=!1}))},maybeDeleteBackup:function(t){this.showModal=!0,this.backupToDelete=t},maybeRestoreBackup:function(t){this.showModal=!0,this.backupToRestore=t},processDeleteBackup:function(){var t=this;this.loading=!0,this.deleteBackup(this.backupToDelete).then((function(){clearTimeout(t.timeout),t.loading=!1,t.showModal=!1,t.backupToDelete=null,t.backupsDeleteSuccess=!0,t.timeout=setTimeout((function(){t.backupsDeleteSuccess=!1,t.backupsRestoreSuccess=!1}),3e3)}))},processRestoreBackup:function(){var t=this;this.loading=!0,this.restoreBackup(this.backupToRestore).then((function(){clearTimeout(t.timeout),t.loading=!1,t.showModal=!1,t.backupToRestore=null,t.backupsRestoreSuccess=!0,t.timeout=setTimeout((function(){t.backupsDeleteSuccess=!1,t.backupsRestoreSuccess=!1}),3e3)}))},getBackupName:function(t){return this.$t.sprintf(this.$t.__("%1$s at %2$s",this.$td),"<strong>"+this.$moment(1e3*t).tz(this.$moment.tz.guess()).format("MMMM D, YYYY")+"</strong>","<strong>"+this.$moment(1e3*t).tz(this.$moment.tz.guess()).format("h:mmA z")+"</strong>")},processBackupAction:function(){return this.backupToDelete?this.processDeleteBackup():this.processRestoreBackup()}})},l=r,c=(e("da9b"),e("2877")),u=Object(c["a"])(l,i,o,!1,null,null,null);s["default"]=u.exports},"1b5a2":function(t,s,e){},"6dac":function(t,s,e){},"6e58":function(t,s,e){},"824c":function(t,s,e){"use strict";e.r(s);var i=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("core-card",{staticClass:"aioseo-export-settings",attrs:{slug:"exportSettings",toggles:!1,"no-slide":"","header-text":t.strings.exportSettings},scopedSlots:t._u([{key:"header-icon",fn:function(){return[e("svg-upload")]},proxy:!0}])},[e("div",{staticClass:"export-settings",class:{"aioseo-settings-row":t.canExportPostOptions}},[e("grid-row",[e("grid-column",{staticClass:"export-all"},[e("base-checkbox",{attrs:{size:"medium"},model:{value:t.options.all,callback:function(s){t.$set(t.options,"all",s)},expression:"options.all"}},[t._v(" "+t._s(t.strings.allSettings)+" ")])],1),t._l(t.settings,(function(s,i){return e("grid-column",{key:i,attrs:{sm:"6"}},[t.options.all?t._e():e("base-checkbox",{attrs:{size:"medium"},model:{value:t.options[s.value],callback:function(e){t.$set(t.options,s.value,e)},expression:"options[setting.value]"}},[t._v(" "+t._s(s.label)+" ")]),"all"!==s.value&&t.options.all?e("base-checkbox",{attrs:{size:"medium",value:!0,disabled:""}},[t._v(" "+t._s(s.label)+" ")]):t._e()],1)}))],2)],1),t.canExportPostOptions?e("div",{staticClass:"export-post-types"},[e("grid-row",[e("grid-column",{staticClass:"export-all"},[e("base-checkbox",{attrs:{size:"medium"},model:{value:t.postOptions.all,callback:function(s){t.$set(t.postOptions,"all",s)},expression:"postOptions.all"}},[t._v(" "+t._s(t.strings.allPostTypes)+" ")])],1),t._l(t.$aioseo.postData.postTypes,(function(s,i){return e("grid-column",{key:i,attrs:{sm:"6"}},[t.postOptions.all?t._e():e("base-checkbox",{attrs:{size:"medium"},model:{value:t.postOptions[s.name],callback:function(e){t.$set(t.postOptions,s.name,e)},expression:"postOptions[postType.name]"}},[t._v(" "+t._s(s.label)+" ")]),"all"!==s.name&&t.postOptions.all?e("base-checkbox",{attrs:{size:"medium",value:!0,disabled:""}},[t._v(" "+t._s(s.label)+" ")]):t._e()],1)}))],2)],1):t._e(),e("base-button",{staticClass:"import",attrs:{type:"blue",size:"medium",disabled:!t.canExport,loading:t.loading},on:{click:t.processExportSettings}},[t._v(" "+t._s(t.strings.exportSettings)+" ")])],1)},o=[],n=e("5530"),a=(e("4de4"),e("159b"),e("b64b"),e("b0c0"),e("d3b7"),e("3ca3"),e("ddb0"),e("2b3d"),e("2f62")),r={data:function(){return{options:{},postOptions:{},loading:!1,strings:{exportSettings:this.$t.__("Export Settings",this.$td),allSettings:this.$t.__("Export All Settings",this.$td),allPostTypes:this.$t.__("Export All Post Types",this.$td)}}},computed:{settings:function(){var t=this,s=[{value:"webmasterTools",label:this.$t.__("Webmaster Tools",this.$td),access:"aioseo_general_settings"},{value:"rssContent",label:this.$t.__("RSS Content",this.$td),access:"aioseo_general_settings"},{value:"advanced",label:this.$t.__("Advanced",this.$td),access:"aioseo_general_settings"},{value:"searchAppearance",label:this.$t.__("Search Appearance",this.$td),access:"aioseo_search_appearance_settings"},{value:"social",label:this.$t.__("Social Networks",this.$td),access:"aioseo_social_networks_settings"},{value:"sitemap",label:this.$t.__("Sitemaps",this.$td),access:"aioseo_sitemap_settings"},{value:"redirects",label:this.$t.__("Redirects",this.$td),access:"aioseo_redirects_settings"},{value:"breadcrumbs",label:this.$t.__("Breadcrumbs",this.$td),access:"aioseo_general_settings"},{value:"tools",label:this.$t.__("Tools",this.$td),access:"aioseo_tools_settings"}];return this.$isPro&&s.push({value:"accessControl",label:this.$t.__("Access Control",this.$td),access:"aioseo_admin"}),!this.isUnlicensed&&this.showImageSeoReset&&s.push({value:"image",label:this.$t.__("Image SEO",this.$td),access:"aioseo_search_appearance_settings"}),!this.isUnlicensed&&this.showLocalBusinessReset&&s.push({value:"localBusiness",label:this.$t.__("Local Business SEO",this.$td),access:"aioseo_local_seo_settings"}),s.filter((function(s){return t.$allowed(s.access)}))},canExport:function(){var t=this,s=[];return Object.keys(this.options).forEach((function(e){s.push(t.options[e])})),Object.keys(this.postOptions).forEach((function(e){s.push(t.postOptions[e])})),s.some((function(t){return t}))},canExportPostOptions:function(){var t=this;return["aioseo_page_general_settings","aioseo_page_advanced_settings","aioseo_page_schema_settings","aioseo_page_social_settings","aioseo_page_local_seo_settings"].some((function(s){return t.$allowed(s)}))}},methods:Object(n["a"])(Object(n["a"])({},Object(a["b"])(["exportSettings"])),{},{processExportSettings:function(){var t=this,s=[];this.options.all?(this.$isPro&&s.push("general"),s.push("internal"),this.settings.filter((function(t){return"all"!==t.value})).forEach((function(t){s.push(t.value)}))):Object.keys(this.options).forEach((function(e){t.options[e]&&s.push(e)}));var e=[];this.postOptions.all?this.$aioseo.postData.postTypes.forEach((function(t){e.push(t.name)})):Object.keys(this.postOptions).forEach((function(s){t.postOptions[s]&&e.push(s)})),this.loading=!0,this.exportSettings({settings:s,postOptions:e}).then((function(s){t.loading=!1,t.options={},t.postOptions={};var e=new Blob([JSON.stringify(s.body.settings)],{type:"application/json"}),i=document.createElement("a");i.href=URL.createObjectURL(e),i.download="aioseo-export-settings-".concat(t.$moment().format("YYYY-MM-DD"),".json"),i.click(),URL.revokeObjectURL(i.href)}))}})},l=r,c=(e("b395d"),e("2877")),u=Object(c["a"])(l,i,o,!1,null,null,null);s["default"]=u.exports},"954e":function(t,s,e){"use strict";e("6e58")},"9b1d":function(t,s,e){"use strict";e.r(s);var i=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("core-card",{staticClass:"aioseo-import-others",attrs:{id:"aioseo-import-others",slug:"importOtherPlugins",toggles:!1,"no-slide":"","header-text":t.strings.importSettingsFromOtherPlugins},scopedSlots:t._u([{key:"header-icon",fn:function(){return[e("svg-download")]},proxy:!0}])},[e("div",{staticClass:"aioseo-section-description"},[t._v(" "+t._s(t.strings.importOthersDescription)+" ")]),t.importSuccess?e("core-alert",{staticClass:"import-success",attrs:{type:"green"}},[t._v(" "+t._s(t.importSuccessful)+" ")]):t._e(),t.importError?e("core-alert",{staticClass:"import-error",attrs:{type:"red"}},[t._v(" "+t._s(t.importErrorMessage)+" ")]):t._e(),e("base-select",{attrs:{size:"medium",options:t.plugins,placeholder:t.strings.selectPlugin},scopedSlots:t._u([{key:"option",fn:function(s){var i=s.option;return[e("div",{staticClass:"import-plugin-label"},[e("span",{staticClass:"plugin-label"},[t._v(t._s(i.label))]),i.$isDisabled?e("span",{staticClass:"plugin-status"},[t._v(t._s(t.strings.notInstalled))]):t._e()])]}}]),model:{value:t.plugin,callback:function(s){t.plugin=s},expression:"plugin"}}),t.plugin?e("div",{staticClass:"import-settings"},[t.plugin.canImport?e("grid-row",[e("grid-column",[e("base-checkbox",{attrs:{size:"medium"},model:{value:t.options.all,callback:function(s){t.$set(t.options,"all",s)},expression:"options.all"}},[t._v(" "+t._s(t.strings.allSettings)+" ")])],1),t._l(t.settings,(function(s,i){return e("grid-column",{key:i,attrs:{xl:"3",md:"4",sm:"6"}},[t.options.all?t._e():e("base-checkbox",{attrs:{size:"medium"},model:{value:t.options[s.value],callback:function(e){t.$set(t.options,s.value,e)},expression:"options[setting.value]"}},[t._v(" "+t._s(s.label)+" ")]),"all"!==s.value&&t.options.all?e("base-checkbox",{attrs:{size:"medium",value:!0,disabled:""}},[t._v(" "+t._s(s.label)+" ")]):t._e()],1)}))],2):t._e(),t.plugin.canImport?t._e():e("core-alert",{attrs:{type:"red"}},[t._v(" "+t._s(t.invalidVersion(t.plugin))+" ")])],1):t._e(),e("base-button",{staticClass:"import",attrs:{type:"blue",size:"medium",disabled:!t.plugin||!t.canImport,loading:t.loading},on:{click:t.processImportPlugin}},[t._v(" "+t._s(t.strings.import)+" ")])],1)},o=[],n=e("5530"),a=(e("159b"),e("b0c0"),e("b64b"),e("4de4"),e("2f62")),r={data:function(){return{importSuccess:!1,importError:!1,options:{},plugin:null,loading:!1,strings:{importSettingsFromOtherPlugins:this.$t.__("Import Settings From Other Plugins",this.$td),importOthersDescription:this.$t.sprintf(this.$t.__("Choose a plugin to import SEO data directly into %1$s.",this.$td),"AIOSEO"),selectPlugin:this.$t.__("Select a plugin...",this.$td),import:this.$t.__("Import",this.$td),allSettings:this.$t.__("All Settings",this.$td),notInstalled:this.$t.__("not installed",this.$td)}}},watch:{plugin:function(){this.importSuccess=!1,this.importError=!1,this.options={}}},computed:{settings:function(){var t=[{value:"settings",label:this.$t.__("SEO Settings",this.$td)},{value:"postMeta",label:this.$t.__("Post Meta",this.$td)}];return this.$isPro&&t.push({value:"termMeta",label:this.$t.__("Term Meta",this.$td)}),t},plugins:function(){var t=[];return this.$aioseo.importers.forEach((function(s){t.push({value:s.slug,label:s.name,canImport:s.canImport,version:s.version,$isDisabled:!s.installed})})),t},canImport:function(){var t=this,s=[];return Object.keys(this.options).forEach((function(e){s.push(t.options[e])})),s.some((function(t){return t}))},importSuccessful:function(){return this.$t.sprintf(this.$t.__("%1$s was successfully imported!",this.$td),this.plugin.label)},importErrorMessage:function(){return this.$t.sprintf(this.$t.__("An error occurred while importing %1$s. Please try again.",this.$td),this.plugin.label)}},methods:Object(n["a"])(Object(n["a"])({},Object(a["b"])(["importPlugins"])),{},{processImportPlugin:function(){var t=this;this.importSuccess=!1,this.importError=!1,this.loading=!0;var s=[];this.options.all?this.settings.filter((function(t){return"all"!==t.value})).forEach((function(t){s.push(t.value)})):Object.keys(this.options).forEach((function(e){t.options[e]&&s.push(e)})),this.importPlugins([{plugin:this.plugin.value,settings:s}]).then((function(){t.loading=!1,t.importSuccess=!0,t.options={}})).catch((function(){t.loading=!1,t.importError=!0,t.options={}}))},invalidVersion:function(t){return this.$t.sprintf(this.$t.__("We do not support importing from the currently installed version of %1$s (%2$s). Please upgrade to the latest version and try again.",this.$td),t.label,t.version)}})},l=r,c=(e("954e"),e("2877")),u=Object(c["a"])(l,i,o,!1,null,null,null);s["default"]=u.exports},a551:function(t,s,e){"use strict";e.r(s);var i=function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("core-card",{staticClass:"aioseo-import-aioseo",attrs:{slug:"importAioseoSettings",toggles:!1,"no-slide":"","header-text":t.strings.importRestoreAioseoSettings},scopedSlots:t._u([{key:"header-icon",fn:function(){return[e("svg-download")]},proxy:!0}])},[t.uploadError?e("core-alert",{staticClass:"import-error",attrs:{type:"red"}},[t._v(" "+t._s(t.uploadError)+" ")]):t._e(),t.uploadSuccess?e("core-alert",{staticClass:"import-error",attrs:{type:"green"}},[t._v(" "+t._s(t.strings.fileUploadedSuccessfully)+" ")]):t._e(),e("div",{staticClass:"file-upload"},[e("base-input",{class:{"aioseo-error":t.uploadError},attrs:{size:"medium",placeholder:t.strings.fileUploadPlaceholder},on:{focus:t.triggerFileUpload},model:{value:t.filename,callback:function(s){t.filename=s},expression:"filename"}}),e("base-button",{attrs:{type:"black",size:"medium"}},[t._v(" "+t._s(t.strings.chooseAFile)+" "),e("base-input",{ref:"file",attrs:{type:"file"},on:{click:t.reset,change:t.handleFileUpload},model:{value:t.inputFile,callback:function(s){t.inputFile=s},expression:"inputFile"}})],1)],1),e("div",{staticClass:"aioseo-description"},[t._v(" "+t._s(t.strings.fileUploadDescription)+" ")]),e("base-button",{staticClass:"import",attrs:{type:"blue",size:"medium",disabled:!t.file||!t.importValidated,loading:t.loading},on:{click:t.submitFile}},[t._v(" "+t._s(t.strings.import)+" ")])],1)},o=[],n=e("5530"),a=(e("8a79"),e("b0c0"),e("2f62")),r={data:function(){return{inputFile:null,filename:null,file:null,uploadError:!1,uploadSuccess:!1,loading:!1,strings:{importRestoreAioseoSettings:this.$t.sprintf(this.$t.__("Import / Restore %1$s Settings",this.$td),"AIOSEO"),fileUploadPlaceholder:this.$t.__("Import from a JSON or INI file...",this.$td),chooseAFile:this.$t.__("Choose a File",this.$td),fileUploadDescription:this.$t.__("Imported settings will overwrite existing settings and will not be merged.",this.$td),import:this.$t.__("Import",this.$td),jsonFileTypeRequired:this.$t.__("A JSON or INI file is required to import settings.",this.$td),fileUploadedSuccessfully:this.$t.__("Success! Your settings have been imported.",this.$td),fileUploadFailed:this.$t.__("There was an error importing your settings. Please make sure you are uploading the correct file or it is in the proper format.",this.$td)}}},computed:{importValidated:function(){return!("application/json"!==this.file.type&&!this.file.name.endsWith(".ini"))}},methods:Object(n["a"])(Object(n["a"])({},Object(a["b"])(["uploadFile"])),{},{reset:function(){this.uploadError=!1,this.filename=null,this.file=null,this.inputFile=null},triggerFileUpload:function(){this.reset(),this.$refs.file.$el.querySelector("input").focus(),this.$refs.file.$el.querySelector("input").click()},submitFile:function(){var t=this;this.loading=!0,this.uploadFile({file:this.file,filename:this.filename}).then((function(){t.reset(),t.uploadSuccess=!0,t.loading=!1})).catch((function(){t.reset(),t.loading=!1,t.uploadError=t.strings.fileUploadFailed}))},handleFileUpload:function(){this.reset(),this.file=this.$refs.file.$el.querySelector("input").files[0],this.file&&(this.filename=this.file.name,"application/json"===this.file.type||this.file.name.endsWith(".ini")||(this.uploadError=this.strings.jsonFileTypeRequired))}})},l=r,c=(e("e0b2"),e("2877")),u=Object(c["a"])(l,i,o,!1,null,null,null);s["default"]=u.exports},b395d:function(t,s,e){"use strict";e("d68e")},c343:function(t,s,e){"use strict";e("6dac")},d68e:function(t,s,e){},da9b:function(t,s,e){"use strict";e("e04a")},e04a:function(t,s,e){},e0b2:function(t,s,e){"use strict";e("1b5a2")}}]);