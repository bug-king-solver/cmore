/* eslint-disable no-bitwise */
/* eslint-disable no-param-reassign */
/* eslint-disable no-plusplus */
/* eslint-disable no-new */
/* eslint-disable no-undef */
/* eslint-disable no-unused-vars */
let delayed;
const availableCharts = ['bar', 'line', 'pie', 'doughnut', 'radar', 'polarArea', 'bubble', 'scatter', 'horizontalBar'];
const randomColor = (text) => {
    let hash = 0;
    for (let i = 0; i < text.length; i++) {
        hash = text.charCodeAt(i) + ((hash << 5) - hash);
    }
    const c = (hash & 0x00FFFFFF)
        .toString(16)
        .toUpperCase();

    return `#${'00000'.substring(0, 6 - c.length)}${c}`;
};

const defaultConfig = () => ({
    data: {
        labels: [],
        datasets: [],
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
            },
        },
        animation: {
            onComplete: () => {
                delayed = true;
            },
            delay: (context) => {
                let delay = 0;
                if (context.type === 'data' && context.mode === 'default' && !delayed) {
                    delay = context.dataIndex * 300 + context.datasetIndex * 100;
                }
                return delay;
            },
        },
        layout: {
            padding: 5,
        },
        plugins: {
            legend: {
                labels: {
                    usePointStyle: true,
                },
            },
        },
        emptyDoughnut: {
            color: 'rgba(255, 128, 0, 0.5)',
            width: 2,
            radiusDecrease: 20,
        },
        cutout: '80%',
        borderRadius: 8,
    },
    destroy: true,
});

const parseColorFromTailwind = (color) => {
    const twColor = window.twConfig.theme.colors[color];
    if (typeof twColor === 'object') {
        if (typeof twColor['500'] !== 'undefined') {
            return twColor['500'];
        }
    }
    return twColor;
};

const makeSingleDatasetColor = (dataset, labels) => {
    const formatedBgColors = [];
    const colors = dataset.backgroundColor || [];// dataset.backgroundColor is array

    colors.forEach((value, key) => {
        if (value.match(/^#[0-9A-F]{6}$/i)) {
            colors[key] = value;
        } else {
            colors[key] = parseColorFromTailwind(value);
        }
    });
    labels.forEach((label, key) => {
        formatedBgColors.push(colors[key] ?? randomColor(label));
    });
    return formatedBgColors;
};

const makeMultipleDatasetColor = (dataset, index) => {
    let backgroundColor = dataset.backgroundColor || [];// dataset.backgroundColor is array
    let colors = [];
    var i = 0;
    for (const value in backgroundColor) {
        colors[i] = backgroundColor[value];
        i++;
    };
    const color = colors[index] ?? null;
    if (color != null && color.match(/#/i)) {
        return color;
    } if (color !== null && color !== undefined && color.match(/esg/i)) {
        return parseColorFromTailwind(color);
    }
    return randomColor(dataset.label);
};

const defaultConfigBarChart = (dataset) => {
    if (typeof (dataset.label) === 'undefined' || dataset.label == null) {
        dataset.label = 'QTD';
    }
    if (typeof dataset.borderWidth === 'undefined' || dataset.borderWidth == null) {
        dataset.borderWidth = 1;
    }
    if (typeof dataset.borderRadius === 'undefined' || dataset.borderRadius == null) {
        dataset.borderRadius = 3;
    }
    if (typeof dataset.spacing === 'undefined' || dataset.spacing == null) {
        dataset.spacing = 1;
    }
    if (typeof dataset.borderSkipped === 'undefined' || dataset.borderSkipped == null) {
        dataset.borderSkipped = false;
    }
    if (typeof dataset.hoverOffset === 'undefined' || dataset.hoverOffset == null) {
        dataset.hoverOffset = 1;
    }

    if (typeof dataset.lineTension === 'undefined' || dataset.lineTension == null) {
        dataset.lineTension = 0.3;
    }

    if (typeof dataset.barThickness === 'undefined' || dataset.barThickness == null) {
        dataset.barThickness = 'flex';
    }

    if (typeof dataset.fill === 'undefined' || dataset.fill == null) {
        dataset.fill = true;
    }

    return dataset;
};

const defaultConfigDonutChart = (dataset) => {
    if (typeof dataset.borderWidth === 'undefined') {
        dataset.borderWidth = 1;
    }
    if (typeof dataset.borderRadius === 'undefined') {
        dataset.borderRadius = 8;
    }
    if (typeof dataset.spacing === 'undefined') {
        dataset.spacing = 0;
    }
    if (typeof dataset.hoverOffset === 'undefined') {
        dataset.hoverOffset = 1;
    }
    return dataset;
};

window.dynamicCharts = (chartName) => {
    const element = document.getElementById(chartName);
    if (!element) {
        throw new Error(`Element ${chartName} not found`);
    }
    let generalChartConfig = JSON.parse(element.getAttribute('data-json'));
    const { type, options, plugins } = generalChartConfig;
    const { labels, datasets } = generalChartConfig.data;
    if (typeof labels === 'undefined' || typeof datasets === 'undefined') {
        throw new Error('Labels and datasets are required');
    }
    if (!availableCharts.includes(type)) {
        throw new Error(`Chart type ${type} is not available`);
    }
    try {
        const chartIsInitialized = Chart.getChart(chartName); // <canvas> id
        if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
            chartIsInitialized.destroy();
        }
        datasets.forEach((dataset, key) => {
            if (type === 'doughnut' || type === 'pie') {
                datasets[key] = defaultConfigDonutChart(dataset);
                datasets[key].backgroundColor = makeSingleDatasetColor(
                    dataset,
                    labels,
                );
            } else {
                datasets[key] = defaultConfigBarChart(dataset);
                datasets[key].backgroundColor = makeMultipleDatasetColor(
                    dataset,
                    key,
                );
            }
        });

        generalChartConfig.data.datasets = datasets;
        generalChartConfig.options = { ...defaultConfig().options, ...options };
        generalChartConfig.plugins = { ...defaultConfig().plugins, ...plugins };

        Chart.register({
            id: 'space-between-graph-legend',
            beforeInit: function (chart, args, options) {
                // Get reference to the original fit function
                const originalFit = chart.legend.fit;
                // Override the fit function
                chart.legend.fit = function fit() {
                    // Call original function and bind scope in order to use `this` correctly inside it
                    originalFit.bind(chart.legend)();
                    // Change the height as suggested in another answers
                    this.height += 15;
                }
            }
        });
        new Chart(element, generalChartConfig);
    } catch (error) {
    }
    return true;
};

window.tenantBarChart = (labels, data, id, barColor, unit = null, configs = {}, type = 'singlebar', view = 'x') => {
    const element = document.getElementById(id);
    var totalLabels = labels.length || 0;

    if (!element) {
        throw new Error(`Element ${id} not found`);
    }
    const chartIsInitialized = Chart.getChart(id); // <canvas> id

    if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
        chartIsInitialized.destroy();
    }

    shortLabels = [];

    if (typeof data[0].label !== 'undefined') {
        labels = data.map((item) => item.label);
        if (typeof data[0].label_short !== 'undefined') {
            labels = data.map((item) => item.label_short);
            shortLabels = data.map((item) => item.label);
        }

        data = data.map((item) => item.value);
    }

    showTitle = true;
    if (typeof configs.showTitle !== 'undefined') {
        showTitle = configs.showTitle;
    }

    if (type == 'singlebar') {
        var chartConfig = {
            labels: labels,
            datasets: [{
                data: data,
                lineTension: 0.3,
                barThickness: totalLabels <= 4 ? 45 : 'flex',
                fill: true,
                backgroundColor: barColor,
                borderColor: barColor,
                borderWidth: 1,
                borderRadius: 0,
                borderSkipped: false,
            }],
        };
    } else {
        var chartConfig = {
            labels: labels,
            datasets: data,
        };
    }

    var simplifiedGrid = false
    if (typeof configs.simplifiedGrid !== 'undefined') {
        simplifiedGrid = true;
    }

    var chartOptions = {
        indexAxis: view,
        layout: {
            padding: {
                top: (view == 'y' ? 0 : 30),
                left: (view == 'y' ? 0 : 30),
            }
        },
        plugins: {
            legend: {
                display: false
            },
            datalabels: {
                align: (view == 'y' ? 'right' : 'top'),
                anchor: 'end',
                formatter: function (value) {
                    return value + (unit != null ? unit : '');
                }
            },
            tooltip: {
                enabled: true,
                callbacks: {
                    label: function (tooltipItems, data) {

                        var label = shortLabels[tooltipItems.dataIndex] || labels[tooltipItems.dataIndex];
                        return label + ': ' + tooltipItems.formattedValue + (unit != null ? unit : '');
                    }
                }
            }
        },
        scales: {
            y: {
                display: true, // Hide left side of bar numbers
                ticks: {
                    color: simplifiedGrid ? '{{ color(8) }}' : null
                },
                grid: {
                    drawBorder: simplifiedGrid ? false : true,
                    display: true,
                    borderColor: simplifiedGrid ? '{{ color(8) }}' : null,
                    borderDash: simplifiedGrid ? [10, 2] : null
                },
            },
            x: {
                display: true,
                offset: true,
                ticks: {
                    display: true,
                    color: simplifiedGrid ? '{{ color(8) }}' : null
                },
                grid: {
                    display: simplifiedGrid ? false : true,
                }
            }
        },
        animation: {
            duration: 1,
        },
    };

    return new Chart(document.getElementById(id).getContext("2d"), {
        type: 'bar',
        data: chartConfig,
        options: chartOptions,
        plugins: [ChartDataLabels]
    });
}

window.tenantDoughnutChart = (labels, data, id, color, configs = {}) => {
    const element = document.getElementById(id);

    if (!element) {
        throw new Error(`Element ${id} not found`);
    }
    const chartIsInitialized = Chart.getChart(id); // <canvas> id

    if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
        chartIsInitialized.destroy();
    }
    let { currency, locale } = configs;
    const legendLabels = {
        usePointStyle: true,
        generateLabels(chart) {
            const data = chart.data;
            if (data.labels.length && data.datasets.length) {
                const { labels: { pointStyle } } = chart.legend.options;

                return data.labels.map((label, i) => {
                    const meta = chart.getDatasetMeta(0);
                    const style = meta.controller.getStyle(i);

                    let textLbl = `${label} (${chart.data.datasets[0].data[i].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")})`;

                    if (currency != null) {
                        $formatted = new Intl.NumberFormat(locale, {
                            style: 'currency',
                            currency: currency,
                            maximumFractionDigits: 2
                        }).format(chart.data.datasets[0].data[i]);
                        textLbl = `${label} (${$formatted})`
                    }

                    return {
                        text: textLbl,
                        fontSize: 20,
                        fillStyle: style.backgroundColor,
                        strokeStyle: style.borderColor,
                        lineWidth: style.borderWidth,
                        pointStyle: pointStyle,
                        hidden: !chart.getDataVisibility(i),
                        index: i
                    };
                });
            }
            return [];
        }
    };

    legendPosition = 'right';
    if (typeof configs.legend !== 'undefined' && typeof configs.legend.position !== 'undefined') {
        legendPosition = configs.legend.position;
    }

    const legendOnCenter = {
        id: 'centerText',
        afterDatasetsDraw(chart, args, options) {
            const {
                ctx,
                chartArea: {
                    left,
                    right,
                    top,
                    bottom,
                    width,
                    height
                }
            } = chart;

            ctx.save;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.font = "bolder 20px " + twConfig.theme.fontFamily.encodesans;
            ctx.fillStyle = twConfig.theme.colors.esg5;
            var text = parseFloat(chart.data.datasets[0].data[0]) + "%";
            ctx.fillText(text, width / 2, height / 2 + top);
        }
    };

    legendDisplay = true;
    if (typeof configs.legend !== 'undefined' && typeof configs.legend.display !== 'undefined') {
        legendDisplay = configs.legend.display;
    }

    const legend = {
        display: legendDisplay,
        position: legendPosition,
        align: "center",
        labels: legendLabels
    };


    const showTooltips = configs.showTooltips ?? true;
    const tooltip = {
        callbacks: {
            displayColors: false,
            title: function (context, data) {
                return context[0]['label'];
            },
            label: function (context) {
                if (currency != null) {
                    return new Intl.NumberFormat(locale, {
                        style: 'currency',
                        currency: currency,
                        maximumFractionDigits: 2
                    }).format(context['raw']);
                }
                return context['formattedValue'];
            },
        }
    };


    if (configs.percentagem) {
        var total = data.reduce((a, b) => a + b, 0);
        var percentages = data.map(value => Math.round(((value / total) * 100).toFixed(2)) + '%');
        labels = labels.map((label, index) => `${label} ${percentages[index]}`);
    }


    const plugins = [];

    if (configs.plugins) {
        if (configs.plugins.legendOnCenter) {
            plugins.push(legendOnCenter);
        }
    }

    const ctx1 = document.getElementById(id).getContext('2d');
    const chart1 = new Chart(ctx1, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: data,
                backgroundColor: color,
            }],
            labels: labels,
        },
        options: {
            cutout: '80%',
            weight: 1,
            borderRadius: 8,
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: legend,
                tooltip: showTooltips ? tooltip : false,
            },
            animation: {
                duration: 500,
                easing: 'linear',
            },
            onClick: genericFunction
        },
        plugins: plugins,
    });

    function genericFunction(e, activeEls) {
        let datasetIndex = activeEls[0].datasetIndex;

        let dataIndex = activeEls[0].index;
        let datasetLabel = e.chart.data.datasets[datasetIndex].label;
        let value = e.chart.data.datasets[datasetIndex].data[dataIndex];
        let positionClicked = e.chart.data.labels[dataIndex];

        if (configs.action.typeAction == 'redirect') {
            let url = `${configs.action.onclick}?s[${configs.action.filter}][0]=${positionClicked}`;
            window.location.href = url;
        }
        return;

        if (configs.action.typeAction == 'emit') {
            Livewire.emit(`${configs.action}`, { 'positionClicked': positionClicked });
        }
    }
}

window.tenantBarChartWithDataLabels = (labels, data, id, barColor, view = 'x', type = "single") => {
    if (type == 'single') {
        var data = {
            labels: labels,
            datasets: [{
                data: data,
                lineTension: 0.3,
                fill: true,
                backgroundColor: barColor,
                borderColor: '{{ color(6) }}'
            }],
        };
    } else {
        var data = {
            labels: labels,
            datasets: data,
        };
    }
    var chartOptions = {
        maxBarThickness: '30',
        indexAxis: view,
        layout: {
            padding: {
                top: 50,
                right: (view == 'y' ? 60 : 0),
            }
        },
        plugins: {
            legend: {
                display: false
            },
            datalabels: {
                anchor: 'end',
                align: (view == 'y' ? 'right' : 'top'),
                backgroundColor: hexToRgbA(barColor),
                color: barColor,
                padding: {
                    top: 6,
                    right: 6,
                    left: 6
                },
                borderRadius: 3,
                textStrokeColor: '#F0F0F0',
                font: {
                    size: '12px',
                    weight: 'Bold',
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    color: '{{ color(8) }}',
                },
                grid: {
                    drawBorder: false,
                    display: true,
                    borderColor: '{{ color(8) }}',
                    borderDash: [10, 2],
                },
            },
            x: {
                display: true,
                offset: view == 'x' ? true : false,
                ticks: {
                    display: true,
                    color: '{{ color(8) }}',
                },
                grid: {
                    display: false,
                    borderColor: '{{ color(8) }}'
                },
                grouped: false
            }
        }
    };
    new Chart(document.getElementById(id), {
        type: 'bar',
        data: data,
        options: chartOptions,
        plugins: [ChartDataLabels]
    });
}


window.tenantRadarChart = (labels, data, id, color) => {
    const element = document.getElementById(id);
    if (!element) {
        throw new Error(`Element ${id} not found`);
    }
    const chartIsInitialized = Chart.getChart(id); // <canvas> id

    if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
        chartIsInitialized.destroy();
    }


    const tooltipsConfig = {
        displayColors: true,
        titleColor: '#FFF',
        callbacks: {
            labelTextColor: function (context) {
                return '#FFF';
            },
            label: function (context) {
                return context.parsed.r + '%';
            }
        },
    };

    const titleConfig = {
        display: false,
        color: twConfig.theme.colors.esg27
    };

    var options = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                pointLabels: {
                    display: true,
                    font: {
                        family: twConfig.theme.fontFamily.encodesans,
                        size: 12,
                        weight: twConfig.theme.fontWeight.bold,
                    }
                },
                ticks: {
                    display: false,
                    beginAtZero: true,
                },
                format: {
                    callback: function (value, index, ticks) {
                        return value + '%';
                    }
                },
                min: 0,
                max: 100
            }
        },
        plugins: {
            legend: {
                display: true,
                position: data.length >= 4 ? 'bottom' : 'right',
            },
            tooltip: tooltipsConfig,
            title: titleConfig,
            datalabels: {
                formatter: function (value, context) {
                    return context.chart.data.labels[context.dataIndex] + ': ' + value + '%';
                }
            }
        },
        animation: {
            duration: 500
        },
    };

    var config = {
        type: 'radar',
        data: {
            labels: labels,
            datasets: data
        },
        options: options,
    };

    console.log(config);
    return new Chart(document.getElementById(id), config);
}
