import Vue from 'vue';
import Chart from 'chart.js';

export default Vue.extend({
    template: `
        <div>
            <canvas ref="canvas"></canvas>
        </div>
    `,

    props: {
        url: '',
    },

    methods: {
        render(data) {
            this.chart = new Chart(
                this.$refs.canvas.getContext("2d"), {

                    type: 'line',
                    data: data,
                    options: {
                        hover: {
                            mode: 'index'
                        },
                        legend: {
                            position: 'bottom'
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        },
                        responsive: true,
                        maintainAspectRatio: true
                    },

                });
        },

    }
});
