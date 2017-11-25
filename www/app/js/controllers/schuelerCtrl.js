	//scope = Modell schicht
	app.controller('SchuelerCtrl', function($scope,$http,$location,$timeout,loginService,dataExchangeService)
	{
		$scope.verSpaetung=["0","5","10","15","30","45","60","90"];
		$scope.stdId=dataExchangeService.getStdId();
		$scope.isSaved = false;

		$http({
			method: 'Post',
			url:	'/app/data/schueler.php',
			data: 	{stdId : $scope.stdId} //gesendete Daten
		})
			.then
			(
				function mySucess(response)
				{
					$scope.schueler = response.data; //erhaltene Daten von echo
					$scope.callToSetKasse= dataExchangeService.setKlasse($scope.schueler[0].klasse);
					$scope.callToSetFach= dataExchangeService.setFach($scope.schueler[0].fach);
				},
	 			function myError(response)
				{
					$scope.schueler = response.status;
				}
			);

		//Eintragungen an PHP senden -> nach PHP senden

		//Führt auf dem Server die angegebene .php aus.
		$scope.save= function()
		{

			$http({
				method:	'Post',
				url:		'/app/data/anwesenheit.php',
				data: 		{"Klasse": $scope.schueler}
			})

				.then(
					function successCallback(response)
					{
						$scope.status = response.status;
						$scope.data = response.data;
						$scope.result = response.data;
						$scope.isSaved = true;
					},

					function errorCallback(response)
					{
						$scope.data = response.data || "Request failed";
						$scope.status = response.status;
					}
			);

			$scope.setIsSaved = function()
			{
				$scope.isSaved = false;
			}
		};


		$scope.schuelerDetail = function(item)
		{
			$scope.callToSetSchuelerId = dataExchangeService.setSchuelerId(item);
			loginService.schueler();
		};

		$scope.entschuldigen = function(item)
		{
			$scope.callToSetSchuelerId = dataExchangeService.setSchuelerId(item);
			loginService.entschuldigen();
		};

		$scope.logout=function()
		{
			loginService.logout();
		};

		$scope.home=function()
		{
			loginService.home();
		};

		$scope.anwesend=function()
		{
			loginService.anwesend();
		};

		$scope.bemerkung=function()
		{
			loginService.home();
			loginService.bemerkung();
		};

		$scope.unterricht=function()
		{
			loginService.unterricht();
		};

	});
