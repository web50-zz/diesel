var ApplyLocale = function(){
	if (ui.administrate){
		Ext.override(ui.administrate, {
			menuStructure: 'Структура',
			menuFileManager: 'Файл-менеджер',
			menuUsers: 'Пользователи',
			menuGroups: 'Группы пользователей',
			menuSecurity: "Безопасность",
			menuHelpPages: 'Страницы помощи',
			menuLogout: 'Выход',
			errUILoad: 'Не удалось загрузить UI'
		});
	}
	if (ui.sys_user && ui.sys_user.main){
		Ext.override(ui.sys_user.main, {
			labelName: 'Ф.И.О',
			labelLogin: 'Логин',
			labelEMail: 'e-mail',
			labelLang: 'Язык',
			labelPassw: 'Пароль',
			lebelRePassw: 'Re-Пароль',

			bttAdd: 'Добавить',
			bttSave: 'Сохранить',
			bttCancel: 'Отмена',

			mnuEdit: 'Изменить',
			mnuDelete: 'Удалить',

			pagerEmptyMsg: 'Нет пользователей',
			pagerDisplayMsg: 'Пользователи с {0} по {1} из {2}',

			titleConfirm: 'Подтверждение',
			msgDeleteConfirm: 'Вы действительно хотите удалить пользователя',
			winTitleAdd: 'Добавить пользователя',
			winTitleEdit: 'Изменить данные пользователя'
		});
	}
	if (ui.security && ui.security.main){
		Ext.override(ui.security.main, {
			menuTitleMain: 'Операции',
			menuTitleSync: 'Синхронизация',
			tabTitleMain: 'Общая сводка',
			errDoSync: 'Ошибка при синхронизации модулей системы'
		});
	}
}
ApplyLocale();
