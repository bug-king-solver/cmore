/* eslint-disable no-undef */
window.pie = (labels, data, id, barColor, centertext = '', textposition = null, legendtitle = null) => {
    try {
        const chartIsInitialized = Chart.getChart(id); // <canvas> id

        if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
            chartIsInitialized.destroy();
        }
    } catch (error) {
    }

    var extra = {
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
            ctx.font = "bolder 24px " + twConfig.theme.fontFamily.encodesans;
            ctx.fillStyle = twConfig.theme.colors.esg16;

            let total = data;
            let text = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));

            if (textposition != null) {
                //ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                let newtext = (centertext !== undefined ?  centertext : '-');
                ctx.fillText(text + ' ' + newtext, width / 2, height / 3 + top + 10 + textposition[0]);


                //ctx.fillText(newtext, width / 2, height / 3 + top + textposition[1]);
            } else {
                //ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                let newtext = (centertext !== undefined ?  centertext : '-');
                ctx.fillText(text + ' ' + newtext, width / 2, height / 3 + top + 20);


                //ctx.fillText(newtext, width / 2, height / 3 + top + 45);
            }
        },
        afterInit: function (chart, args, options) {
            if (labels != null) {
                const chartId = chart.canvas.id;
                const legendId = `${chartId}-legend`;
                let html = '';

                if (document.getElementById(legendId) !== null) {
                    chart.data.datasets[0].data.forEach((datavale, i) => {
                        let total = data;
                        let labelText = chart.data.labels[i];
                        let value = datavale;
                        let backgroundColor = chart.data.datasets[0].backgroundColor[i];

                        const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                        let percentag = Math.round(value * 100 / sum) + '%';

                        html += `
                            <div class="grid w-full grid-cols-3">
                                <div class="col-span-2 flex items-center">
                                    <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}"></div>
                                    <div class="inline-block text-sm text-esg8">${labelText}</div>
                                </div>
                                <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value})</div>
                            </div>
                        `;
                    })

                    document.getElementById(legendId).innerHTML = html;
                }
            }
        }
    };

    var options = {
        plugins: {
            legend: {
                display: false,
            },
            title: {
                display: false,
                font: {
                    family: twConfig.theme.fontFamily.encodesans,
                    size: 22,
                    weight: twConfig.theme.fontWeight.bold,
                },
                padding: {
                    bottom: 30
                },
                color: twConfig.theme.colors.esg27
            },
            datalabels: {
                color: twConfig.theme.colors.esg27,
                formatter: function (value) {
                    var total = data;
                    const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                    return Math.round(value * 100 / sum) + '%';
                },
                font: {
                    weight: 'bold',
                    size: 15,
                }
            }
        },
        cutout: '82%',
        animation: {
            duration: 0
        }
    };

    var config = {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: barColor,
                borderRadius: 0,
                borderWidth: 0,
                spacing: 0,
                hoverOffset: 1
            }]
        },
        options: options,
        plugins: [extra]
    };

    new Chart(document.getElementById(id), config);
}

window.pieChartNew = (id) => {

    const element = document.getElementById(id);

    if (element != null) {
        //throw new Error(`Element ${id} not found`);
        let generalChartConfig = JSON.parse(element.getAttribute('data-json'));

        var currency = generalChartConfig['currency'],
        single = generalChartConfig['single'] ?? false,
        locale = generalChartConfig['locale'];

        let labels = generalChartConfig['labels'],
            data = generalChartConfig['data'],
            barColor = generalChartConfig['bar_color'],
            centertext = generalChartConfig['unit'],
            textposition = generalChartConfig['position'],
            legendtitle = null;

        try {
            const chartIsInitialized = Chart.getChart(id); // <canvas> id

            if (typeof (chartIsInitialized) !== 'undefined' && chartIsInitialized != null) {
                chartIsInitialized.destroy();
            }
        } catch (error) {
            console.error("An error occurred while destroying the chart.");
        }

        var extra = {
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
                ctx.font = "bolder 24px " + twConfig.theme.fontFamily.encodesans;
                ctx.fillStyle = twConfig.theme.colors.esg16;

                let total = data;
                let text = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                if (single) {
                    text = total[0];
                }

                let newtext = (centertext !== undefined ? centertext : '-');
                if (locale !== undefined && currency !== undefined) {
                    text = new Intl.NumberFormat(locale, {
                        style: 'currency',
                        currency: currency,
                        maximumFractionDigits: 2
                    }).format(text);
                    newtext = (centertext !== undefined ?  '' : '-');
                }

                if (textposition != null) {
                    //ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                    ctx.fillText(text + ' ' + newtext, width / 2, height / 3 + top + 10 + textposition[0]);
                    //ctx.fillText(newtext, width / 2, height / 3 + top + textposition[1]);
                } else {
                    //ctx.font = "14px " + twConfig.theme.fontFamily.encodesans;
                    ctx.fillText(text + ' ' + newtext, width / 2, height / 3 + top + 20);
                    //ctx.fillText(newtext, width / 2, height / 3 + top + 45);
                }
            },
            afterInit: function (chart, args, options) {
                if (labels != null) {
                    const chartId = chart.canvas.id;
                    const legendId = `${chartId}-legend`;
                    let html = '';

                    if (document.getElementById(legendId) !== null) {
                        chart.data.datasets[0].data.forEach((datavale, i) => {
                            let total = data;
                            let labelText = chart.data.labels[i];
                            let value = datavale;
                            let backgroundColor = chart.data.datasets[0].backgroundColor[i];

                            const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                            let percentag = Math.round(value * 100 / sum) + '%';

                            html += `
                                <div class="grid w-full grid-cols-3">
                                    <div class="col-span-2 flex items-center">
                                        <div class="mr-4 inline-block rounded-full p-2 text-left" style="background-color:${backgroundColor}"></div>
                                        <div class="inline-block text-sm text-esg8">${labelText}</div>
                                    </div>
                                    <div class="text-right text-sm text-esg8 leading-10"> <span style="color:${backgroundColor}" class="text-sm font-bold">${percentag}</span>  (${value})</div>
                                </div>
                            `;
                        })

                        document.getElementById(legendId).innerHTML = html;
                    }
                }
            }
        };

        var options = {
            plugins: {
                legend: {
                    display: false,
                },
                title: {
                    display: false,
                    font: {
                        family: twConfig.theme.fontFamily.encodesans,
                        size: 22,
                        weight: twConfig.theme.fontWeight.bold,
                    },
                    padding: {
                        bottom: 30
                    },
                    color: twConfig.theme.colors.esg27
                },
                datalabels: {
                    color: twConfig.theme.colors.esg27,
                    formatter: function (value) {
                        var total = data;
                        const sum = total.reduce((accumulator, current) => parseFloat(accumulator) + parseFloat(current));
                        return Math.round(value * 100 / sum) + '%';
                    },
                    font: {
                        weight: 'bold',
                        size: 15,
                    }
                }
            },
            cutout: '82%',
            animation: {
                duration: 0
            }
        };

        var config = {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: barColor,
                    borderRadius: 0,
                    borderWidth: 0,
                    spacing: 0,
                    hoverOffset: 1
                }]
            },
            options: options,
            plugins: [extra]
        };

        new Chart(document.getElementById(id), config);
    }
}
