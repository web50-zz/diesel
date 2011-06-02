/****************************************************
* CKEditor Extension
*****************************************************/
Ext.form.CKEditor = function(config){
	this.config = config;
	config.listeners = config.listeners || {};
	this.onBeforeDestroy = function() {
		if(Ext.isIE){
	//9* some troubles in Firefox with destroy below but IE8 troubles if  we not do such action so only for IE
	// 9* in IE8 now this method rises error so commented 30052011			this.ckEditor.destroy();
		}
	}
	Ext.applyIf(config.listeners, {
			beforedestroy : this.onBeforeDestroy.createDelegate(this),
			scope : this
	});
																																		
	Ext.form.CKEditor.superclass.constructor.call(this, config);


	this.onRender = function(ct, position){
		if(!this.el){
			this.defaultAutoCreate = {
				tag: "textarea",
				autocomplete: "off"
			};
		}
		Ext.form.TextArea.superclass.onRender.call(this, ct, position);
		this.ckEditor = CKEDITOR.replace(this.id, this.config.CKConfig);
	}
	this.setValue = function(value){
		Ext.form.TextArea.superclass.setValue.apply(this,[value]);
		CKEDITOR.instances[this.id].setData( value );
	}
	this.getValue = function(){
		CKEDITOR.instances[this.id].updateElement();
		this.value = CKEDITOR.instances[this.id].getData();
		return Ext.form.TextArea.superclass.getValue.apply(this);
	}
	this.getRawValue = function(){
		CKEDITOR.instances[this.id].updateElement();
		this.value = CKEDITOR.instances[this.id].getData();
		return Ext.form.TextArea.superclass.getRawValue.apply(this);
	}
	this.onDestroy = function(){
		if (CKEDITOR.instances[this.id]) {
			delete CKEDITOR.instances[this.id];
		}
	}
};
Ext.extend(Ext.form.CKEditor, Ext.form.TextArea, {});
Ext.reg('ckeditor', Ext.form.CKEditor);
