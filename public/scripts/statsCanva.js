
document.addEventListener("DOMContentLoaded", function () {

    function countByKey(key) {
        let counts = {};
        medias.forEach(media => {
            if (media[key]) {
                counts[media[key]] = (counts[media[key]] || 0) + 1;
            }
        });
        return counts;
    }

    let dataSets = {
        auteur: countByKey('author'), 
        type: countByKey('type')     
    };

    let currentFilter = "auteur";

    let ctx = document.getElementById('Medias').getContext('2d');
    let Medias = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(dataSets[currentFilter]), 
            datasets: [{
                data: Object.values(dataSets[currentFilter]), 
                backgroundColor: ['#14213D', '#FCA311'] 
            }]
        }
    });

    document.querySelectorAll(".button").forEach(button => {
        button.addEventListener("click", function () {
            let filter = this.getAttribute("data-filter");
            currentFilter = filter || "auteur"; 

            // Update the chart data
            Medias.data.labels = Object.keys(dataSets[currentFilter]);
            Medias.data.datasets[0].data = Object.values(dataSets[currentFilter]);
            Medias.update();
        });
    });
});
