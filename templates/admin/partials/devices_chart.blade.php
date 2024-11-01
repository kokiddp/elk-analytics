<canvas id="deviceChart"></canvas>

<canvas id="osChart"></canvas>

<canvas id="browserChart"></canvas>

<script>
  jQuery(function($) {
    const data = @json($data);

    const getCounts = (data, field) => {
      const labels = [...new Set(data.map(item => item[field] ?? "{!! __('Unknown', 'elk-analytics') !!}"))];
      const counts = labels.map(label => 
        data.filter(item => item[field] === label).length
      );
      return { labels, counts };
    };

    const createChart = (ctxId, labels, counts, title) => {
      const ctx = document.getElementById(ctxId).getContext('2d');
      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            label: title,
            data: counts,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: title
            },
            datalabels: {
              formatter: (value, context) => {
                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                const percentage = ((value / total) * 100).toFixed(1);
                return percentage + '%';
              },
              color: '#fff',
              font: {
                weight: 'bold'
              }
            }
          }
        },
        plugins: [ChartDataLabels]
      });
    };

    const deviceData = getCounts(data, 'device');
    const osData = getCounts(data, 'os_type');
    const browserData = getCounts(data, 'browser_type');

    createChart("deviceChart", deviceData.labels, deviceData.counts, "{!! __('Device Usage Distribution', 'elk-analytics') !!}");
    createChart("osChart", osData.labels, osData.counts, "{!! __('OS Usage Distribution', 'elk-analytics') !!}");
    createChart("browserChart", browserData.labels, browserData.counts, "{!! __('Browser Usage Distribution', 'elk-analytics') !!}");
  });
</script>