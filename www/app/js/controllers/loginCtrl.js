app.controller("loginCtrl",function($scope, loginService){

	$scope.login = function(data){
		loginService.login(data,$scope); // Service aufrufen
	};

	$scope.logout=function(){
		loginService.logout();
	};
});
