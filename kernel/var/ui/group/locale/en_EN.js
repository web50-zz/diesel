ui.group.locale = function(face){
	switch(face){
		case 'main':
			Ext.override(ui.group.main, {
				ttlAvailable: 'Available',
				ttlEnabled: 'Enabled',
				permTitle: "The access rights",
				bttFind: 'Find',
				bttReset: 'Reset',
				vName: 'Title',
				bttAdd: 'Add',
				bttEdit: 'Edit',
				bttFaces: "The access rights",
				bttDelete: 'Delete'
			});
		break;
		case 'grid':
			Ext.override(ui.group.grid, {
				colNameTitle: "Title",
				addTitle: "Add group",
				editTitle: "Edit group",
				permTitle: "The access rights",
				cnfrmTitle: "Confirm",
				cnfrmMsg: "Are you sure you want to delete this group?",
				pagerEmptyMsg: 'Empty',
				pagerDisplayMsg: 'Groups {0} - {1} of {2}',
			});
		break;
		case 'item_form':
			Ext.override(ui.group.item_form, {
				labelName: 'Title',
				loadText: 'Loading form data',
				saveText: 'Saving...',
				bttSave: 'Save',
				bttCancel: 'Cancel',
				errSaveText: 'Error while saving',
				errInputText: 'Correctly fill out all required fields',
				errConnectionText: "Error communicating with server"
			});
		break;
	}
}
