// app module Login
var app=angular.module("loginApp",["ngRoute"]);

app.config(function($routeProvider) {
    $routeProvider
  .when("/", {
        templateUrl : "/app/partials/login.html",
				controller	: "loginCtrl"
    	})

	.when("/login", {
        templateUrl : "/app/partials/login.html",
				controller:  "loginCtrl"
    	})

	.when("/home", {
        templateUrl : "/app/partials/home.html",
				controller:  "homeCtrl" // auf der Seite aktiver controller
    	})

	.when("/anwesend", {
        templateUrl : "/app/partials/anwesend.html",
				controller: "SchuelerCtrl"
    	})

	.when("/nachtragenSelect", {
       	templateUrl : "/app/partials/nachtragenSelect.html",
				controller: "nachTragenSelectTagCtrl"
    	})

	.when("/nachtragen", {
        	templateUrl : "/app/partials/nachtragen.html",
					controller: "nachTragenCtrl"
    	})

	.when("/schueler", {
        	templateUrl : "/app/partials/schuelerDetail.html",
					controller	: "SchuelerDetailCtrl"
    	})

	.when("/bemerkung", {
        	templateUrl : "/app/partials/bemerkung.html",
					controller : "BemerkungCtrl"
    	})

	.when("/unterricht", {
        	templateUrl : "/app/partials/unterricht.html",
					controller : "UnterrichtCtrl"
    	})

	.when("/logout",{
		templateUrl : "/app/partials/logout.html",
		controller : "logoutCtrl"
	})
	.when("/klassen",
	{
		templateUrl : "/app/partials/KlassenLehrer/klasseAuswaehlen.html",
		controller : "KlasseAuswaehlenCtrl"
	})
  .when("/klasseBearbeiten",
  {
    templateUrl : "/app/partials/KlassenLehrer/klasseBearbeiten.html",
    controller : "KlasseBearbeitenCtrl"
  })
  .when("/schuelerBearbeiten",
  {
    templateUrl : "/app/partials/KlassenLehrer/schuelerBearbeiten.html",
    controller : "SchuelerBearbeitenCtrl"
  })

  .when("/entschuldigen",
  {
    templateUrl : "/app/partials/KlassenLehrer/entschuldigen.html",
    controller : "SchuelerDetailCtrl"
  })

	.otherwise({
			redirectTo : "/logout"
	})
});
//remove access to /home without authentification

app.run(function($rootScope, $location, loginService){
	var routespermission=["/","/home","/klassen","/anwesend","/nachtragen",
        "/nachtragenSelect","/bemerkung","/unterricht","/schueler",
        "/klasseBearbeiten","/schuelerBearbeiten","/entschuldigen"];

	$rootScope.$on('$routeChangeStart',function(){
		if(routespermission.indexOf($location.path())!=-1)
		{
			var connected=loginService.islogged();
			connected.then(function(msg){
				if(!msg.data){
					$location.path("/logout");
					$location.path("/login");
				}
			});
		}
	});
});
