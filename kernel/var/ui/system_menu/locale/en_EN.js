ui.system_menu.locale = function(face){
	switch(face){
		case 'main':
			Ext.override(ui.system_menu.main, {
				bttAdd: 'Add',
				bttEdit: 'Edit',
				bttDelete: 'Delete'
			});
		break;
		case 'item_form':
			Ext.override(ui.system_menu.item_form, {
				lblType: 'Type',
				lblText: 'title',
				lblIcon: 'Icon',
				lblUI: 'User Interface',
				lblEP: 'Entry Point',
				lblHref: 'Href',
				vMenu: 'Menu item',
				vSpec: 'Special item',
				loadText: 'Loading form data',
				saveText: 'Saving...',
				bttSave: 'Save',
				bttCancel: 'Cancel',
				errSaveText: 'Error while saving',
				errInputText: 'Correctly fill out all required fields',
				errConnectionText: "Error communicating with server"
			});
		break;
		case 'tree':
			Ext.override(ui.system_menu.tree, {
				msgLoading: "Data Loading...",
			});
		break;
	}
}
