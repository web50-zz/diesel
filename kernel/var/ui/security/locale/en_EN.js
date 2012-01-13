ui.security.locale = function(face){
	switch(face){
		case 'main':
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
		break;
		case 'interfaces':
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
		break;
	}
}
