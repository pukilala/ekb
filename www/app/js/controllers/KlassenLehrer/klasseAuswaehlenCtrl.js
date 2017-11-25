app.controller('KlasseAuswaehlenCtrl', function($scope,$http,$location,loginService,dataExchangeService)
{
  $http({
    method: 'POST',
    url:	'/app/data/KlassenLehrer/getKlassen.php',
    //data:	{datum : $scope.tag} //gesendete Daten
  })
    .then(
      function successCallback(response)
      {
        $scope.klassen=response.data;	  //erhalten Daten
        $scope.status =response.status;
      },

      function errorCallback(response)
      {
        $scope.data = response.data || "Request failed";
        $scope.status = response.status;
      });

      $scope.bearbeiten = function(item)
  		{
  			$scope.callToSetKlasse = dataExchangeService.setKlasse($scope.klassen[item].myKlasse);
  			loginService.klasseBearbeiten();
  		};

      $scope.home=function()
  		{
  			loginService.home();
  		};
});
