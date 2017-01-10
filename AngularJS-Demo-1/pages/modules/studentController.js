/**
 * Created by Ramona on 10.1.2017 г..
 */


mainApp.controller("studentController", function ($scope)
{
    $scope.student = {

        friends: [
            {
                name: "Aleks",
                phone: "0889-256-625"
            },
            {
                name: "Rosen",
                phone: "0883-055-555"
            },
            {
                name: "Kalina",
                phone: "0877-522-405"
            }
        ],

    }
});