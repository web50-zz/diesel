var ApplyLocale = function(){
	if (ui.user){
		if (ui.user.main){
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
		}
		if (ui.user.grid){
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
		}
		if (ui.user.item_form){
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
		}
		if (ui.user.list){
			Ext.override(ui.user.list, {
				labelName: 'Name',
				labelLogin: 'Login',

				pagerEmptyMsg: 'Empty',
				pagerDisplayMsg: 'Users {0} - {1} of {2}'
			});
		}
	}
	if (ui.group){
		if (ui.group.main){
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
		}
		if (ui.group.grid){
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
		}
		if (ui.group.item_form){
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
		}
	}
	if (ui.security){
		if (ui.security.main){
			Ext.override(ui.security.main, {
				bttFind: 'Find',
				bttReset: 'Reset',
				vName: 'Name',
				ttlUsers: 'Users',
				ttlGroups: 'Groups',
				menuTitleMain: 'Operations',
				menuTitleSync: 'Syncronization',
				bttAddUsers: 'Add users', 
				bttRemoveUsers: 'Remove users', 
				errDoSync: 'Error while modules syncronization',
				errGroupNotSelected: 'The group is not selected',
				errUserNotSelected: 'The user(s) is not selected'
			});
		}
		if (ui.security.interfaces){
			Ext.override(ui.security.interfaces, {
				labelType: 'Type',
				labelName: 'Interface',
				labelFace: 'Entry point',

				vAll: 'All',
				bttFind: 'Find',
				bttReset: 'Reset',

				pagerEmptyMsg: 'Empty',
				pagerDisplayMsg: 'Records {0} - {1} of {2}'
			});
		}
	}
}
ApplyLocale();
