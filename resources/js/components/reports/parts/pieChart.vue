<template>
    <Pie
        :chart-options="chartOptions"
        :chart-data="chartData"
        :chart-id="chartId"
        :plugins="plugins"
        :css-classes="cssClasses"
        :styles="styles"
        :width="width"
        :height="height"
        :currency="currency"
    />
</template>

<script>
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, Plugin } from 'chart.js'
import ChartJsPluginDataLabels from 'chartjs-plugin-datalabels';

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, ChartJsPluginDataLabels)

export default {
    name: 'Pie Chart',
    components: { Pie },
    props: {
        pieChartData: {
            type: Array,
            default: []
        },
        chartId: {
            type: String,
            default: 'pie-chart'
        },
        width: {
            type: Number,
            default: 350
        },
        height: {
            type: Number,
            default: 170
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Array,
            default: []
        },
        currency: {
            type: String,
            default: '$'
        }

    },

    data() {
        return {
            chartData: {
                labels: ["Foyda", "Tani", "Chegirma"],
                datasets: [ {
                    backgroundColor: ['#41B883', '#00D8FF', '#E46651'],
                    data: this.pieChartData
                    }
                ],
            },
            chartOptions: {
                responsive: false,
                offset: [12, 0, 0],
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'left',
                        title: {
                            display: true,
                            text: "Umumiy xisobot"
                        }
                    },
                    datalabels: {
                        color: 'black',
                        textAlign: 'center',
                        font: {
                            // weight: "bold",
                            // size: 16
                        },
                        anchor: 'end',
                        formatter: function(value, context) {

                            return value > 0 ? '$'+value : ''
                        }
                    }

                },


            }
        }
    },
    mounted(){

        // this.chartData.label = this.soldData
        // console.log("soldData 2:", this.pieChartData);

    }


}
</script>


