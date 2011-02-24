Ext.apply(Ext.form.VTypes, {
	password: function(val, field) {
		if (field.initialPasswordField) {
			var pwd = Ext.getCmp(field.initialPasswordField);
			return (val == pwd.getValue());
		}
		return true;
	},
	passwordText: 'Пароль повторно введён не верно',
	emailText: 'Введите в формате email@domain.com'
});
var formatDate = function(value){
	return value ? value.dateFormat('d M Y') : ''
};
var formatDateMySQL = function(value){
	return value ? value.dateFormat('Y-m-d') : ''
};
var formatDateTime = function(value){
	return value ? value.dateFormat('d M Y H:i:s') : ''
};
var formatTime = function(value){
	return value ? value.dateFormat('H:i:s') : ''
};
var formatSize = function(value){
	return value ? Ext.util.Format.fileSize(value) : '0'
};
