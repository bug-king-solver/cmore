// Params
// Labels = label array
// Data = Data array for the chart
// id = Canvas id
// barColor = bar color array
// Unit = If we have any unit to display else it's blank
// Type = bar chart type/ 1. Single 2. Multibar
// View = bar chart type/ 1. x =  horizontal 2. y = vertical
let delayed;
window.bar = (labels, data, id, barColor, unit = null, type = "single", view = 'x') => {
    try {
        const chartIsInitialized = Chart.getChart(id); // <canvas> id

        if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
            chartIsInitialized.destroy();
        }
    } catch (error) {
    }

    if (type == 'single') {
        var data = {
            labels: labels,
            datasets: [{
                data: data,
                lineTension: 0.3,
                fill: true,
                backgroundColor: barColor,
            }],
        };
    } else {
        var data = {
            labels: labels,
            datasets: data,
        };
    }

    var chartOptions = {
        indexAxis: view,
        layout: {
            padding: {
                top: 50
            }
        },
        plugins: {
            legend: {
                display: false
            },
            datalabels: {
                anchor: 'end',
                align: (view == 'y' ? 'right' : 'top'),
                color: barColor,
                backgroundColor: hexToRgbA(barColor),
                formatter: function (value) {
                    return value + unit;
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    color: twConfig.theme.colors.esg8
                },
                grid: {
                    drawBorder: false,
                    display: true,
                    borderColor: twConfig.theme.colors.esg8,
                    borderDash: [10, 2],
                },
            },
            x: {
                display: true,
                offset: view == 'y' ? false : true,
                ticks: {
                    display: true,
                    color: twConfig.theme.colors.esg8
                },
                grid: {
                    display: false,
                    borderColor: twConfig.theme.colors.esg8
                },
            }
        },
        // animation: {
        //     duration: 0
        // }

    };

    new Chart(document.getElementById(id).getContext("2d"), {
        type: 'bar',
        data: data,
        options: chartOptions,

        plugins: [ChartDataLabels]
    });
}

window.barChartNew = (id) => {
    const element = document.getElementById(id);

    if (element != null) {

        let generalChartConfig = JSON.parse(element.getAttribute('data-json'));

        var labels = generalChartConfig['labels'],
            data = generalChartConfig['data'],
            barColor = generalChartConfig['bar_color'],
            labelColor = generalChartConfig['label_color'],
            unit = generalChartConfig['unit'],
            type = generalChartConfig['type'],
            view = generalChartConfig['view'],
            locale = generalChartConfig['locale'],
            currency = generalChartConfig['currency'];

        try {
            const chartIsInitialized = Chart.getChart(id); // <canvas> id

            if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
                chartIsInitialized.destroy();
            }
        } catch (error) {
        }

        if (type == 'single') {
            var data = {
                labels: labels,
                datasets: [{
                    data: data,
                    lineTension: 0.3,
                    fill: true,
                    backgroundColor: barColor,
                    borderColor: barColor,
                    borderWidth: 1,
                    borderRadius: 0,
                    borderSkipped: false,
                    barThickness: 'flex',
                }],
            };
        } else {
            var data = {
                labels: labels,
                datasets: data,
            };
        }

        var chartOptions = {
            indexAxis: view,
            locale: locale,
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    display: false,
                    anchor: 'start',
                    align: (view == 'y' ? 'right' : 'top'),
                    color: labelColor,
                    formatter: function (value) {
                        if (currency != null) {
                            return new Intl.NumberFormat(locale, {
                                style: 'currency',
                                currency: currency,
                                maximumFractionDigits: 2
                            }).format(value);
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: twConfig.theme.colors.esg8,
                        callback: (value, index, values) => {
                            if (view == 'y') {
                                return labels[index];
                            }
                            if (currency != null) {
                                return new Intl.NumberFormat(locale, {
                                    style: 'currency',
                                    currency: currency,
                                    maximumFractionDigits: 2
                                }).format(value);
                            }
                        }
                    },
                    grid: {
                        drawBorder: false,
                        display: true,
                        borderColor: twConfig.theme.colors.esg8,
                        borderDash: [10, 2],
                    },
                },
                x: {
                    display: true,
                    offset: view == 'y' ? false : true,
                    ticks: {
                        display: true,
                        color: twConfig.theme.colors.esg8,
                        callback: (value, index, values) => {
                            if (view == 'x') {
                                return labels[index];
                            }
                            if (currency != null) {
                                return new Intl.NumberFormat(locale, {
                                    style: 'currency',
                                    currency: currency,
                                    maximumFractionDigits: 2
                                }).format(value);
                            }
                        }
                    },
                    grid: {
                        display: false,
                        borderColor: twConfig.theme.colors.esg8
                    },
                }
            },
            animation: {
                duration: 400,
                type: 'easeOutQuart',
            }
        };

        new Chart(document.getElementById(id).getContext("2d"), {
            type: 'bar',
            data: data,
            options: chartOptions,
            plugins: [ChartDataLabels]
        });
    }
}
