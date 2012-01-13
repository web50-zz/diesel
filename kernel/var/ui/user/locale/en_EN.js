ui.user.locale = function(face){
	switch(face){
		case 'main':
			Ext.override(ui.user.main, {
				fldName: 'Name',
				fldLogin: 'Login',
				fldEmail: 'email',
				fldServer: 'Server',
				bttFind: 'Find',
				bttReset: 'Reset',
				bttAdd: 'Add',
				bttEdit: 'Edit',
				bttDelete: 'Delete'
			});
		break;
		case 'grid':
			Ext.override(ui.user.grid, {
				lblName: 'Name',
				lblType: 'Type',
				lblServer: 'Server',
				lblLogin: 'Login',
				lblEMail: 'e-mail',
				lblLang: 'Language',
				addTitle: "Add new user",
				editTitle: "Edit user",
				cnfrmTitle: "Confirm",
				cnfrmMsg: "Are you sure you want to delete this user?",
				pagerEmptyMsg: 'Empty',
				pagerDisplayMsg: 'Users {0} - {1} of {2}'
			});
		break;
		case 'item_form':
			Ext.override(ui.user.item_form, {
				lblName: 'Name',
				lblMulti: "multi-login",
				lblLogin: 'Login',
				lblType: 'Auth type',
				lblServer: 'Server',
				lblEMail: 'e-mail',
				lblLang: 'Language',
				lblPassw: 'Password',
				lblRePassw: 'Confirm password',
				vYes: 'Yes',
				vNo: 'No',
				loadText: 'Loading form data',
				saveText: 'Saving...',
				bttSave: 'Save',
				bttCancel: 'Cancel',
				errSaveText: 'Error while saving',
				errInputText: 'Correctly fill out all required fields',
				errConnectionText: "Error communicating with server"
			});
		break;
		case 'list':
			Ext.override(ui.user.list, {
				labelName: 'Name',
				labelLogin: 'Login',

				pagerEmptyMsg: 'Empty',
				pagerDisplayMsg: 'Users {0} - {1} of {2}'
			});
		break;
	}
}
