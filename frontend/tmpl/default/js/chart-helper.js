// noinspection JSUnusedGlobalSymbols,JSUnresolvedVariable

let _handle_ = document.getElementById('_sensor_');
let _sensor_ = new Chart(_handle_, {
    type: 'line',
    data: {
        labels: [_timestampdata_],
datasets: [{
    label: '_label_',
    data: [_sensordata_],
backgroundColor: ['rgba(_sensorcolor_,0.2)'],
    borderColor: ['rgba(_sensorcolor_)'],
    borderWidth: 1, cubicInterpolationMode: 'monotone',
    fill: true, tension: 0.3 }]
},
options: {
    responsive: true, maintainAspectRatio: true, aspectRatio: 2,
    interaction: { mode: 'index', intersect: false },
    plugins: { legend: { display:false },
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) { label += ': '; }
                    if (context.parsed.y !== null) { label += context.parsed.y + ' _appendlabel_'; }
                    return label;
                }
            }
        }},
    scales: { y: { beginAtZero: false, title: { display: true, text: '_appendlabel_' } }, x: {ticks: {display:true, font: {size: 8}}}}
}
});
