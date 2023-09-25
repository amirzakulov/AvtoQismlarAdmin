<template>
    <Bar
        :chart-options="chartOptions"
        :chart-data="chartData"
        :chart-id="chartId"
        :plugins="plugins"
        :css-classes="cssClasses"
        :styles="styles"
        :width="width"
        :height="height"
    />
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
    name: 'Bar Chart',
    components: { Bar },
    props: {
        chartId: {
            type: String,
            default: 'bar-chart'
        },
        width: {
            type: Number,
            default: 400
        },
        height: {
            type: Number,
            default: 200
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {

            }
        },
        plugins: {
            type: Object,
            default: () => {}
        }
    },

    data() {
        return {
            chartData: {
                labels: [ 'January', 'February', 'March' ],
                datasets: [ {
                    label: 'Data One',
                    backgroundColor: '#f87979',
                    data: [40, 20, 12]
                } ]
            },
            chartOptions: {
                responsive: true
            }
        }
    },


}
</script>


