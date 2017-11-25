app.service('dataExchangeService', function() {
	var klasse ="0";
	var fach ="0";
	var stdId ="current_"+Date.now().toString();
	var schuelerId ="0";
	

	return {
			getSchuelerId: function(){
				return schuelerId;
			},
			setSchuelerId: function(value){
				schuelerId=value;
			},

			getStdId: function(){
			return stdId;
			},
			setStdId: function(value){
				stdId=value;
			},

			getFach: function(){
				return fach;
			},
			setFach: function(value){
				fach=value;
			},

			getKlasse: function(){
				return klasse;
			},
			setKlasse: function(value){
				klasse=value;
			}

	 };
});
