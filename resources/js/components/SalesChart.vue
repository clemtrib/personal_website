<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

const props = defineProps<{
  data: Array<{ month: string; total_subtotal_month: number }>;
}>();

const chartRef = ref<HTMLCanvasElement | null>(null);

onMounted(() => {
  if (chartRef.value && props.data.length) {
    new Chart(chartRef.value, {
      type: 'bar',
      data: {
        labels: props.data.map(item => item.month),
        datasets: [{
          label: 'Ventes hors taxes',
          data: props.data.map(item => item.total_subtotal_month),
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }
});
</script>

<template>
  <canvas ref="chartRef"></canvas>
</template>
