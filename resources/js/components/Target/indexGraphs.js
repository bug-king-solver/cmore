/* eslint-disable no-undef */
window.targetPieCharts = (labels, data, id, labelColors = []) => {
    if (!labels || !data || !id) {
        return null;
    }

    let barColor = ['#99CA3C', '#f57c00', '#f00028'];
    if (labelColors.length > 0) {
        barColor = labelColors;
    }

    const options = {
        plugins: {
            legend: {
                display: false,
            },
            title: {
                display: true,
                align: 'center',
                position: 'top',
                padding: {
                    top: 40,
                },
                font: {
                    family: twConfig.theme.fontFamily.encodesans,
                    size: 22,
                    weight: twConfig.theme.fontWeight.bold,
                },
                color: twConfig.theme.colors.esg27,
                responsive: true,
            },
            datalabels: {
                color: twConfig.theme.colors.esg27,
                formatter(value) {
                    const total = data;
                    const sum = total.reduce(
                        (accumulator, current) => accumulator + current,
                    );
                    return `${Math.round((value * 100) / sum)}%`;
                },
                font: {
                    weight: 'bold',
                    size: 15,
                },
            },
        },
        cutout: '85%',
        animation: {
            duration: 0,
        },
        tooltipCaretSize: 0,
    };

    const config = {
        type: 'doughnut',
        data: {
            labels,
            datasets: [
                {
                    data,
                    backgroundColor: barColor,
                    spacing: 0,
                    hoverOffset: 3,
                },
            ],
        },
        options,
    };

    return new Chart(document.getElementById(id), config);
};

window.multilineCharts = (id, labels, dataStatus, dataCreated) => {
    if (!id || !dataStatus) {
        return null;
    }

    const divChart = document.getElementById(id);

    if (
        typeof divChart === 'undefined'
        || divChart === null
        || divChart.length === 0
    ) {
        return null;
    }

    const chart = Chart.getChart(id);

    if (chart) {
        chart.destroy();
    }

    const data = [
        {
            data: JSON.parse(dataStatus),
            label: id,
            backgroundColor: 'rgba(21, 58, 91, 0.2)',
            borderColor: ['#153A5B'],
            fill: true,
            tension: 0.4,
            borderWidth: 2,
        },
    ];

    /**
     * to render the chart with dateCreated fo targets , add
     * {
        data: JSON.parse(dataCreated),
        label: 'Created',
        backgroundColor: 'rgb(63 131 248 / 0.5)',
        borderColor: 'rgb(63 131 248 / 0.5)',
        borderDash: [5, 5],
        fill: false,
        tension: 0.4,
        borderWidth: 2,
      },
     */

    const options = {
        elements: {
            center: false,
        },
        plugins: {
            legend: {
                display: false,
            },
        },
        interaction: {
            mode: 'index',
        },
        scales: {
            xAxes: {
                display: false,
                ticks: {
                    display: false,
                },
            },
            yAxes: {
                display: false,
                ticks: {
                    display: false,
                },
            },
        },
    };

    const config = {
        type: 'line',
        data: {
            labels: JSON.parse(labels),
            datasets: data,
        },
        options,
    };

    return new Chart(divChart, config);
};
