// Factory übergibt an den Service Werte
app.factory("loginService",function($http,$location,sessionService){
	return{
		login:function(data,scope){

			var $promise=$http.post("data/user.php",data); //Daten nach php
			$promise.then(function(msg){
				var uid=msg.data;

				if(uid)
				{
					sessionService.set("uid",uid);
					$location.path("/home");
				}
				else
				{
					$location.path("/");
					sessionService.destroy("uid");
				}
			});
		},

		logout:function(){
			sessionService.destroy("uid");
			$location.path("/");
		},

		home:function(){
			$location.path("/home");
		},

		nachtragenSelect:function(){

			$location.path("/nachtragenSelect");
		},

		nachtragen:function(str){
			$location.path("/nachtragen");
		},

		anwesend:function(){
			$location.path("/anwesend");
		},

		bemerkung:function(){
			$location.path("/bemerkung");
		},

		unterricht:function(){
			$location.path("/unterricht");
		},

		schueler:function(){
			$location.path("/schueler");
		},

		klasseAuswaehlen:function(){
			$location.path("/klassen");
		},

		klasseBearbeiten:function(){
			$location.path("/klasseBearbeiten");
		},

		schuelerBearbeiten:function(){
			$location.path("/schuelerBearbeiten");
		},

		entschuldigen:function(){
			$location.path("/entschuldigen");
		},

		islogged:function(){
			var $checkSessionServer=$http.post('data/check_session.php');
			return $checkSessionServer;
		}
	}


});
