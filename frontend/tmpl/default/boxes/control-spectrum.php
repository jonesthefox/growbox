<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h4><i class="fa fa-chart-area w3-margin-right"></i><?=_LIGHT_SPECTRUM;?></h4>
    <canvas id="spectrum" width="100%" height="100" class="w3-small"></canvas>
</div>

<!--suppress HtmlUnknownTarget -->
<script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/chart/chart.js"></script>

<script>
    let labels = [<?=Cfg::read('light','spectrum_bands');?>];

    let red = [<?=Cfg::read('light','spectrum_r');?>];
    let green = [<?=Cfg::read('light','spectrum_g');?>];
    let blue = [<?=Cfg::read('light','spectrum_b');?>];
    let white = [<?=Cfg::read('light','spectrum_w');?>];

    red.forEach(function (part, index) { red[index] = (part * document.getElementById("red").defaultValue * 100 / 255 / 100).toFixed(2); });
    green.forEach(function (part, index) { green[index] = (part * document.getElementById("green").defaultValue * 100 / 255 / 100).toFixed(2); });
    blue.forEach(function (part, index) { blue[index] = (part * document.getElementById("blue").defaultValue * 100 / 255 / 100).toFixed(2); });
    white.forEach(function (part, index) { white[index] = (part * document.getElementById("white").defaultValue * 100 / 255 / 100).toFixed(2); });

    function updateChart(chart) {

        let r = [<?=Cfg::read('light','spectrum_r');?>];
        let g = [<?=Cfg::read('light','spectrum_g');?>];
        let b = [<?=Cfg::read('light','spectrum_b');?>];
        let w = [<?=Cfg::read('light','spectrum_w');?>];

        r.forEach(function (part, index) { r[index] = (part * document.getElementById("red").value * 100 / 255 / 100).toFixed(2); });
        g.forEach(function (part, index) { g[index] = (part * document.getElementById("green").value * 100 / 255 / 100).toFixed(2); });
        b.forEach(function (part, index) { b[index] = (part * document.getElementById("blue").value * 100 / 255 / 100).toFixed(2); });
        w.forEach(function (part, index) { w[index] = (part * document.getElementById("white").value * 100 / 255 / 100).toFixed(2); });

        let total = chart.data.labels.length;
        while (total >= 0) {
            chart.data.datasets[0].data.pop();
            chart.data.datasets[1].data.pop();
            chart.data.datasets[2].data.pop();
            chart.data.datasets[3].data.pop();
            total--;
            chart.update();
        }

        r.forEach(function (part) { chart.data.datasets[0].data.push(part); });
        g.forEach(function (part) { chart.data.datasets[1].data.push(part); });
        b.forEach(function (part) { chart.data.datasets[2].data.push(part); });
        w.forEach(function (part) { chart.data.datasets[3].data.push(part); });
        chart.update();

    }

    let spec = document.getElementById('spectrum');
    let spectral = new Chart(spec, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                label: '<?=_LIGHT_BRIGHTNESS_R;?>',
                data: red,
                backgroundColor: ['rgba(255,0,0,0.2)'],
                borderColor: ['rgba(255,0,0)'],
                borderWidth: 1,
                fill: true, cubicInterpolationMode: 'monotone'
                },
                {
                    label: '<?=_LIGHT_BRIGHTNESS_G;?>',
                    data: green,
                    backgroundColor: ['rgba(0,255,0,0.2)'],
                    borderColor: ['rgba(0,255,0)'],
                    borderWidth: 1,
                    fill: true, cubicInterpolationMode: 'monotone'
                },
                {
                    label: '<?=_LIGHT_BRIGHTNESS_B;?>',
                    data: blue,
                    backgroundColor: ['rgba(0,0,255,0.2)'],
                    borderColor: ['rgba(0,0,255)'],
                    borderWidth: 1,
                    fill: true, cubicInterpolationMode: 'monotone'
                },
                {
                    label: '<?=_LIGHT_BRIGHTNESS_W;?>',
                    data: white,
                    backgroundColor: ['rgba(0,0,0,0.2)'],
                    borderColor: ['rgb(0,0,0)'],
                    borderWidth: 1,
                    fill: true, cubicInterpolationMode: 'monotone'
                }]
        },
        options: {
            responsive: true, maintainAspectRatio: true, aspectRatio: 2,
            interaction: {mode: 'index', intersect: false},
            plugins: {
                legend: {display: false},
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y + ' %';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {beginAtZero: true, title: {display: true, text: '%'}},
                x: {ticks: {display: true, text: 'nm', font: {size: 8}}}
            }
        }
    });
</script>
