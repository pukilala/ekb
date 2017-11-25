app.controller('KlasseBearbeitenCtrl', function($scope,$http,$location,loginService,dataExchangeService)
{
    $scope.klasse=dataExchangeService.getKlasse();
    $http({
			method: 'Post',
			url:	'/app/data/KlassenLehrer/getSchueler.php',
			data: 	{name : $scope.klasse} //gesendete Daten
		})
			.then
			(
				function mySucess(response)
				{
					$scope.schueler = response.data; //erhaltene Daten von echo
				},
	 			function myError(response)
				{
					$scope.schueler = response.status;
				}
			);

      $scope.home=function()
    	{
    		loginService.home();
    	};

      $scope.schuelerBearbeiten=function(item)
      {
          $scope.callToSetSchuelerId = dataExchangeService.setSchuelerId(item);
          loginService.schuelerBearbeiten();
      };
});
