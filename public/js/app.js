function _getDate(t){var e=new Array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"),i=t.getDate(),n=t.getMonth(),a=t.getFullYear();return i+" "+e[n]+" "+a}function _getTime(t){var e=t.getHours(),i=t.getMinutes();return 10>i&&(i="0"+i),e+":"+i}function showCommentForm(t,e){$("#commentParentId").val(e),$("#commentForm").insertAfter(t)}function addTagToThematic(t,e){Api.post("/api.tags.thematic",{tag:t},function(t){"function"==typeof e&&e(t)})}function addToFavorite(t){var e=$(this);App.User.runIfAuth(function(){Api.post("/api.article.favorite",{id:e.data("id")},function(t){e.closest(".articleItem--favorites").replaceWith($(t.content).find(".icon-bookmark").addClass("animated bounceIn").end())})},"Вы должны авторизоваться")}var App={Controllers:{_controllers:[],add:function(t,e){if("function"!=typeof e)return this;if("object"==typeof t)for(var i=0;i<t.length;i++)this._controllers.push([t[i],e]);else"string"==typeof t&&this._controllers.push([t,e]);return this},call:function(){for(var t=$("body:first").attr("id"),e=0;e<this._controllers.length;e++)t=="body."+this._controllers[e][0]&&this._controllers[e][1](this._controllers[e][0])}},Components:{_elements:[],_modules:[],add:function(t,e,i){return"function"!=typeof e?this:(this._elements.push([t,e,i||0]),this)},addModule:function(t,e,i){return"function"!=typeof e?this:(this._modules.push([t,e,i||0]),this)},call:function(t){for(var e=0;e<this._elements.length;e++){var i=this._elements[e];_.isArray(t)&&-1!=_.indexOf(t,i[0])?i[1]():t==i[0]&&i[1]()}},init:function(t){this._elements=_.sortBy(this._elements,2),this._modules=_.sortBy(this._modules,2);for(i in this._elements){var e=this._elements[i];try{t?_.isArray(t)&&-1!=_.indexOf(t,e[0])?e[1]():t==e[0]&&e[1]():e[1]()}catch(n){console.log(e[0],n)}}var a=[];$("[data-module]").each(function(){a.push($(this).data("module"))}),a=_.uniq(a);for(i in this._modules){var t=this._modules[i],o=t[0];-1!=_.indexOf(a,o)&&t[1]()}}},Messages:{init:function(){"undefined"!=typeof MESSAGE_ERRORS&&(this.parse(MESSAGE_ERRORS,"error"),this.parse(MESSAGE_SUCCESS),$("body").on("show_message",$.proxy(function(){var t=_.toArray(arguments).slice(1);this.parse(t)},this)))},parse:function(t,e){for(text in t)"_external"!=text?this.show(t[text],e):this.parse(t[text],e)},show:function(t,e,i){e||(e="success"),window.top.noty({layout:"topRight",type:e,icon:i||"fa fa-ok",text:decodeURIComponent(t)})},error:function(t){this.show(t,"error")}},Dialog:{confirm:function(t,e,i,n){bootbox.confirm({title:i||"Подтверждение действия",message:t,className:"modal-alert modal-warning",closeButton:!1,callback:function(t){t&&e()},buttons:{confirm:{label:"Да",className:"btn-success btn-lg"},cancel:{label:"Нет",className:"btn-default btn-lg"}}})}},Loader:{counter:0,getLastId:function(){return this.counter},init:function(t,e){void 0===t||t instanceof jQuery?void 0===t&&(t=$("body")):t=$(t),++this.counter;var i=$('<div class="_loader_container"><span class="_loader_preloader" /></div>');return void 0!==e&&(e instanceof jQuery?i.append(e):i.append('<span class="_loader_message">'+e+"</span>")),i.appendTo(t).css({width:t.outerWidth(!0),height:t.outerHeight(!0),top:t.offset().top-$(window).scrollTop(),left:t.offset().left-$(window).scrollLeft()}).prop("id","loader"+this.getLastId())},show:function(t,e,i){var i=i||500;return this.init(t,e).fadeTo(i,.7),this.counter},hide:function(t){t?cont=$("#loader"+t):cont=$("._loader_container"),cont.stop().fadeOut(400,function(){$(this).remove()})}}};App.User={checkAuth:function(){return USER_ID},runIfGuest:function(t,e){this.checkAuth()||"function"!=typeof t||t(),e&&App.Messages.error(e)},runIfAuth:function(t,e){return this.checkAuth()&&"function"==typeof t?t():void(e&&App.Messages.error(e))}},App.Form={extend:function(t,e){if(!e)var e={};return e._prefix=t,App.Form[t]=$.extend({},this._decorator,e),App.Form[t]},_decorator:{_id:null,_api_url:null,_api_method:null,_prefix:null,_key:null,_timestamp:null,_isChanged:!1,_form:null,_autoSaveTimer:null,_fieldsData:{},_submitButton:null,messages:{saved:null},fieldsMeta:{},_fields:{},autoSaveDelay:5e3,init:function(t){if(this._form=t,this._key="form"+this._prefix+this._id,$(this._form).on("click",":button",$.proxy(function(t){this._submitButton=$(t.target)},this)),this._fieldsData.timestamp=(new Date).getTime(),(null===this._api_url||!this._api_url.length)&&this._form.attr("action").indexOf("api.")>=0&&(this._api_url=this._form.attr("action")),null===this._api_method||!this._api_method.length){var e=$('input[name="_method"]',this._form);this._api_method=e.size()?e.val().toLowerCase():this._form.prop("method").toLowerCase()}this._autoSaveTimer=setInterval($.proxy(this.onBackup,this),this.autoSaveDelay),this._id=this._fieldsData.id;for(i in this.fieldsMeta){if(this.fieldsMeta[i]in App.Form.Field)var n=Object.create(App.Form.Field[this.fieldsMeta[i]]);else var n=Object.create(App.Form.Field["default"]);n.construct(this,i),n.getElement().length&&(n._init(),this._fields[i]=n)}this.getFieldsData(),this.onLoad(),$(window).unload($.proxy(this.onUnload,this))},getFieldsData:function(){for(i in this._fields)this._fieldsData[i]=this._getFieldData(i);return this._fieldsData},setFieldsData:function(t){for(i in this._fields)"id"!=i&&this._setFieldData(i,t[i])},getField:function(t){return this._fields[t]||null},hasField:function(t){return t in this.fieldsMeta},_getFieldData:function(t){return this.hasField(t)?this.getField(t).getValue():!1},_setFieldData:function(t,e){return this.hasField(t)?this.getField(t).setValue(e):!1},saveToLocalStorage:function(t){$.jStorage.set(this._key,t),this._isChanged=!1},getFromLocalStorage:function(){return $.jStorage.get(this._key)},clearLocalStorage:function(){$.jStorage.deleteKey(this._key)},clearErrors:function(){$(".validation-error").remove(),$(".form-group").removeClass("has-error")},onFailValidation:function(t){for(field in t)if(this.hasField(field)){var e=this.getField(field).getElement();e.closest(".form-group").addClass("has-error").end();for(i in t[field])e.after($('<p class="help-block validation-error" />').text(t[field][i]))}},onLoad:function(){this._form.on("submit",$.proxy(this.onSubmit,this)).on("change keyup","input, select, textarea",$.proxy(this.onChange,this)),this.showAutoSaveNotify()},onChange:function(t){t.preventDefault(),this._isChanged=!0,$("#notification_autosave").remove()},onSubmit:function(t){return this.clearErrors(),$(":button",this._form).prop("disabled",!0),this._api_url?(t.preventDefault(),Api[this._api_method](this._api_url,this.getFieldsData(),$.proxy(this.onResponse,this)),!1):void 0},onResponse:function(t){$(":button",this._form).prop("disabled",!1),this.clearLocalStorage()},showAutoSaveNotify:function(){var t=this.getFromLocalStorage();if(_.isObject(t)&&!_.isEmpty(t)){var e=new Date(t.timestamp);this._form.prepend(_.template('<div class="alert alert-info m-b-none autoSaveNotification">У вас есть автосохранение от <b><%= date %> <%= time %></b>, <a href="#restore" class="autoSaveNotification--restore">восстановить форму</a>?<span class="close autoSaveNotification--close" onclick="">&times;</span></div>')({date:_getDate(e),time:_getTime(e)})),$(".autoSaveNotification").on("click",".autoSaveNotification--restore",$.proxy(function(e){e.preventDefault(),this.onRestore(t),$(e.target).closest(".autoSaveNotification").remove()},this)),$(".autoSaveNotification").on("click",".autoSaveNotification--close",$.proxy(function(t){t.preventDefault(),this.clearLocalStorage(),$(t.target).closest(".autoSaveNotification").remove()},this))}},onBackup:function(t){if(this._isChanged){var e=this.getFieldsData();e.timestamp=(new Date).getTime(),this.saveToLocalStorage(e)}},onRestore:function(t){this.setFieldsData(t)},onUnload:function(t){this.onBackup(t)}}},App.Form.Field={extend:function(t,e){if(!e)var e={};return e._prefix=t,App.Form.Field[t]=$.extend({},this._decorator,e),App.Form.Field[t]},_decorator:{_form:null,_name:null,_element:null,construct:function(t,e){return this._form=t,this._name=e,this._element=this.getFieldInput(),this},getFieldInput:function(){return $(':input[name="'+this.getName()+'"]',this._form._form)},getElement:function(){return this._element},getValue:function(){return this.getElement().val()},setValue:function(t){this.getElement().val(t)},getName:function(){return this._name},_init:function(){}}},App.Form.Field.extend("default"),App.Form.Field.extend("checkbox",{getFieldInput:function(){return $(':input[name="'+this.getName()+'"]:not(:hidden)',this._form._form)},getValue:function(){return this.getElement().prop("checked")},setValue:function(t){return this.getElement().prop("checked",t).trigger("change")}}),App.Form.Field.extend("markdown",{_init:function(){var t=this.getElement();this.editor=new SimpleMDE({element:t[0],spellChecker:!1})},getValue:function(){return this.editor.value()},setValue:function(t){return this.editor.value(t)}}),App.Form.Field.extend("tags",{_init:function(){this.getElement().tagsinput({minLength:2,confirmKeys:[13,44],trimValue:!0,freeInput:!0,typeahead:{afterSelect:function(t){this.$element.val("")},source:function(t){return $.get("/api.tags.search",{query:t})}}})},setValue:function(t){return this.getElement().tagsinput("add",t)}}),App.Form.Field.extend("rangeslider",{_init:function(){this.slider=$("<div />").insertBefore(this.getElement())[0];var t=this;noUiSlider.create(this.slider,{start:this.getElement().val()||0,step:this.getElement().data("step")||1,range:this.getElement().data("range")||{min:[0],max:100}}),this.slider.noUiSlider.on("update",function(e,i){$("#slider-"+t.getName()+" .slider-value").text(e[i]),t.getElement().val(e[i])})},getValue:function(){return this.slider.noUiSlider.get()},setValue:function(t){return this.slider.noUiSlider.set(t)}});var Api={_response:null,get:function(t,e,i,n){return this.request("GET",t,e,i,n)},post:function(t,e,i,n){return this.request("POST",t,e,i,n)},put:function(t,e,i,n){return this.request("PUT",t,e,i,n)},"delete":function(t,e,i,n){return this.request("DELETE",t,e,i,n)},request:function(t,e,i,n,a){var o=this.parseUrl(e);return $.ajaxSetup({contentType:"application/json"}),i instanceof jQuery&&(i=Api.serializeObject(i)),"object"==typeof i&&"get"!=t.toLowerCase()&&(i=JSON.stringify(i)),$.ajax({type:t,url:o,data:i,dataType:"json",async:a!==!1}).done(function(e){return this._response=e,200!=e.code?Api.exception(e,n):(window.top.$("body").trigger(Api.getEventKey(t,o),[this._response]),e.message&&200==e.code&&App.Messages.show(e.message,"information"),e.popup&&200==e.code&&Popup.openHTML(e.popup),void("function"==typeof n&&n(this._response)))}).fail(function(t){return Api.exception(t.responseJSON,n)})},parseUrl:function(t){return t},getEventKey:function(t,e){var i=t+e.replace(SITE_URL,":").replace(/\//g,":");return i.toLowerCase()},serializeObject:function(t){var e={},i={},n={validate:/^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,key:/[a-zA-Z0-9_]+|(?=\[\])/g,push:/^$/,fixed:/^\d+$/,named:/^[a-zA-Z0-9_]+$/},a=function(t,e,i){return t[e]=i,t},o=function(t){return void 0===i[t]&&(i[t]=0),i[t]++};return $.each(t.serializeArray(),function(){if(n.validate.test(this.name)){for(var t,i=this.name.match(n.key),s=this.value,r=this.name;void 0!==(t=i.pop());)r=r.replace(new RegExp("\\["+t+"\\]$"),""),t.match(n.push)?s=a([],o(r),s):t.match(n.fixed)?s=a([],t,s):t.match(n.named)&&(s=a({},t,s));e=$.extend(!0,e,s)}}),e},exception:function(t,e){switch("function"==typeof e&&e(t),t.code){case 220:break;case 110:App.Messages.show(t.message,"error","fa fa-exclamation-triangle");break;case 120:for(i in t.errors)App.Messages.show(t.errors[i],"error","fa fa-exclamation-triangle");break;case 130:case 140:case 150:break;case 301:case 302:window.location.href=t.targetUrl;break;case 403:case 404:break;default:App.Messages.show(t.message,"error","fa fa-exclamation-triangle")}},response:function(){return this._response}};App.Components.add("ajaxSetup",function(){$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}})}).add("SocialShareKit",function(){SocialShareKit.init()}).add("notySetup",function(){$.noty.defaults=$.extend($.noty.defaults,{layout:"topRight",theme:"relax",timeout:3e3})}).add("validator.default",function(){"function"==typeof jQuery.fn.validator&&$.validator.setDefaults({highlight:function(t){var e=$(t);e.hasClass("select2-offscreen")?$("#s2id_"+e.attr("id")+" ul").closest(".form-group").addClass("has-error"):e.closest(".form-group").addClass("has-error")},unhighlight:function(t){var e=$(t);e.hasClass("select2-offscreen")?$("#s2id_"+e.attr("id")+" ul").closest(".form-group").removeClass("has-error").find("help-block-hidden").removeClass("help-block-hidden").addClass("help-block").show():e.closest(".form-group").removeClass("has-error").find("help-block-hidden").removeClass("help-block-hidden").addClass("help-block").show()},errorElement:"p",errorClass:"jquery-validate-error help-block",errorPlacement:function(t,e){var i,n,a;return a=e.is('input[type="checkbox"]')||e.is('input[type="radio"]'),n=e.closest(".form-group").find(".jquery-validate-error").length,a&&n?void 0:(n||e.closest(".form-group").find(".help-block").removeClass("help-block").addClass("help-block-hidden").hide(),t.addClass("help-block"),a?e.closest('[class*="col-"]').append(t):(i=e.parent(),i.is(".input-group")?i.parent().append(t):i.append(t)))}})}).add("datepicker",function(){if("function"==typeof jQuery.fn.datetimepicker){var t={format:"Y-m-d H:i:00",lang:LOCALE,dayOfWeekStart:1};$(".input-datetime").each(function(){var e=$.extend({},t),i=$(this);i.data("range-max-input")&&(e.onShow=function(t){var e=$(i.data("range-max-input"));this.setOptions({maxDate:e.val()?e.val():!1})}),i.data("range-min-input")&&(e.onShow=function(t){var e=$(i.data("range-min-input"));this.setOptions({minDate:e.val()?e.val():!1})}),i.datetimepicker(e)}),$(".input-date").each(function(){var e=$.extend(t,{timepicker:!1,format:"Y-m-d"}),i=$(this);i.data("range-max-input")?e.onShow=function(t){var e=$(i.data("range-max-input"));this.setOptions({maxDate:e.val()?e.val():!1})}:i.data("range-min-input")&&(e.onShow=function(t){var e=$(i.data("range-min-input"));this.setOptions({minDate:e.val()?e.val():!1})}),i.datetimepicker(e)})}}).add("icon",function(){$("*[data-icon]").add("*[data-icon-prepend]").each(function(){var t=$(this).data("icon");$(this).hasClass("btn-labeled")&&(t+=" btn-label icon"),$(this).html('<i class="icon-'+t+'"></i> '+$(this).html()),$(this).removeAttr("data-icon-prepend").removeAttr("data-icon")}),$("*[data-icon-append]").each(function(){$(this).html($(this).html()+'&nbsp&nbsp<i class="icon-'+$(this).data("icon-append")+'"></i>'),$(this).removeAttr("data-icon-append")})}),App.Form.extend("articles",{fieldsMeta:{title:"string",text_source:"markdown",disable_comments:"checkbox",disable_stat_views:"checkbox",disable_stat_pays:"checkbox",tags_list:"tags",cost:"rangeslider"},onSubmit:function(t){t.preventDefault(),this.clearErrors();var e=this._submitButton.val(),i=this._api_url,n=this._api_method;switch(e){case"publish":i="/api.article.publish/"+this._id,n="post";break;case"draft":i="/api.article.draft/"+this._id,n="post"}$(":button",this._form).prop("disabled",!0),Api[n](i,this.getFieldsData(),$.proxy(this.onResponse,this))}}),App.Controllers.add(["article.create","article.edit"],function(t){App.Form.articles.init($("#articleForm")),App.Form.articles._id=ARTICLE_ID}),App.Controllers.add("article.list.thematic",function(t){$("#addTagInput").typeahead({afterSelect:function(t){var e=this.$element;addTagToThematic(t,function(t){e.val(""),200==t.code&&($("#thematicTags").html(t.content),Api.get("/api.articles.thematic",{},function(t){$("#thematicArticles").html(t.content)}))})},source:function(t,e){return $.get("/api.tags.search",{query:t},function(t){e(t)})}}),$("#thematicTags").on("click",".close",function(){var t=$(this).closest(".tagsCloud--tag").data("id");Api["delete"]("/api.tags.thematic",{tag:t},function(t){200==t.code&&($("#thematicTags").html(t.content),Api.get("/api.articles.thematic",{},function(t){$("#thematicArticles").html(t.content)}))})})}),$(function(){$("body").on("click",".addToFavorite",addToFavorite)}),App.Controllers.add("article.show",function(){$(".commentItem--reply").on("click",function(t){t.preventDefault(),showCommentForm($(this),$(this).data("id"))}),$(".commentForm--title a").on("click",function(t){t.preventDefault(),showCommentForm($(this).parent(),null)}),$("#commentForm").validate({rules:{text:{required:!0,minlength:10},title:{maxlength:255}}})}),App.Controllers.add(["user.bookmarks"],function(){$("#searchBookmarked").on("keyup",function(){Api.get("/api.filter.bookmarks",{query:$(this).val()},function(t){t.content&&$(".articleList").html(t.content)})})}),$(function(){function t(){App.Components.init(),App.Controllers.call(),App.Messages.init()}$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}}),t()});