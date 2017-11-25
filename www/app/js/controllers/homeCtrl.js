app.controller("homeCtrl",function($scope,loginService,dataExchangeService){
	dataExchangeService.setStdId("current_"+Date.now().toString());
	dataExchangeService.setSchuelerId("0");
	dataExchangeService.setFach("0");
	dataExchangeService.setKlasse("0");

	$scope.logout=function(){
		loginService.logout();
	};

	$scope.anwesend=function(){
		loginService.anwesend();
	};

	$scope.nachtragenSelect=function(){
		loginService.nachtragenSelect();
	};
	$scope.home=function()
	{
		loginService.home();
	};
	$scope.klasseAuswaehlen=function(){
		loginService.klasseAuswaehlen();
	};

});
