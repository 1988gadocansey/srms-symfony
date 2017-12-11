$(document).ready(function(){
    $.ajax({
        url: "/srms-laravel/report/service/gender",
        method: "GET",
        success: function(data) {
            console.log(data);
            var player = [];
            var score = [];

            for(var i in data) {
                player.push(  data[i].GENDER);
                score.push(data[i].TOTAL);
            }

            var chartdata = {
                labels: player,
                datasets : [
                    {
                        label: 'Applicants by Gender',
                        backgroundColor: ["#FF9A5A", "#889d9e"],
                        borderColor: 'darkorange',

                        data: score
                    }
                ]
            };

            var ctx = $("#mycanvas");

            var myDoughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: chartdata
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
});