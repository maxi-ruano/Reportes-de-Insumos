$(document).ready(function() {

    init_charts();

    //Configuracion del datepicker
    $('#fecha').daterangepicker({
        singleDatePicker: true,
        maxDate: moment().add(0, 'day'),
        locale: { format: 'DD-MM-YYYY' }
    }).on('change', function(e) {
        $('#btnConsultar').click();
    });

    //Actualizar pagina cada 10 segundos
    /*setTimeout(function(){
        window.location.reload(1);
    }, 10000);*/

});

function init_charts() {

    if( typeof (echarts) === 'undefined'){ return; }
    console.log('init_echarts');

    var theme = {
        color: [
            '#26B99A', '#34495E', '#BDC3C7', '#3498DB',
            '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
        ],

        title: {
            itemGap: 8,
            textStyle: {
                fontWeight: 'normal',
                color: '#408829'
            }
        },

        dataRange: {
            color: ['#1f610a', '#97b58d']
        },

        toolbox: {
            color: ['#408829', '#408829', '#408829', '#408829']
        },

        tooltip: {
            backgroundColor: 'rgba(0,0,0,0.5)',
            axisPointer: {
                type: 'line',
                lineStyle: {
                    color: '#408829',
                    type: 'dashed'
                },
                crossStyle: {
                    color: '#408829'
                },
                shadowStyle: {
                    color: 'rgba(200,200,200,0.3)'
                }
            }
        },

        dataZoom: {
            dataBackgroundColor: '#eee',
            fillerColor: 'rgba(64,136,41,0.2)',
            handleColor: '#408829'
        },
        grid: {
            borderWidth: 0
        },

        categoryAxis: {
            axisLine: {
                lineStyle: {
                    color: '#408829'
                }
            },
            splitLine: {
                lineStyle: {
                    color: ['#eee']
                }
            }
        },

        valueAxis: {
            axisLine: {
                lineStyle: {
                    color: '#408829'
                }
            },
            splitArea: {
                show: true,
                areaStyle: {
                    color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                }
            },
            splitLine: {
                lineStyle: {
                    color: ['#eee']
                }
            }
        },
        timeline: {
            lineStyle: {
                color: '#408829'
            },
            controlStyle: {
                normal: {color: '#408829'},
                emphasis: {color: '#408829'}
            }
        },

        k: {
            itemStyle: {
                normal: {
                    color: '#68a54a',
                    color0: '#a9cba2',
                    lineStyle: {
                        width: 1,
                        color: '#408829',
                        color0: '#86b379'
                    }
                }
            }
        },
        map: {
            itemStyle: {
                normal: {
                    areaStyle: {
                        color: '#ddd'
                    },
                    label: {
                        textStyle: {
                            color: '#c12e34'
                        }
                    }
                },
                emphasis: {
                    areaStyle: {
                        color: '#99d2dd'
                    },
                    label: {
                        textStyle: {
                            color: '#c12e34'
                        }
                    }
                }
            }
        },
        force: {
            itemStyle: {
                normal: {
                    linkStyle: {
                        strokeColor: '#408829'
                    }
                }
            }
        },
        chord: {
            padding: 4,
            itemStyle: {
                normal: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    },
                    chordStyle: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        }
                    }
                },
                emphasis: {
                    lineStyle: {
                        width: 1,
                        color: 'rgba(128, 128, 128, 0.5)'
                    },
                    chordStyle: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        }
                    }
                }
            }
        },
        gauge: {
            startAngle: 225,
            endAngle: -45,
            axisLine: {
                show: true,
                lineStyle: {
                    color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                    width: 8
                }
            },
            axisTick: {
                splitNumber: 10,
                length: 12,
                lineStyle: {
                    color: 'auto'
                }
            },
            axisLabel: {
                textStyle: {
                    color: 'auto'
                }
            },
            splitLine: {
                length: 18,
                lineStyle: {
                    color: 'auto'
                }
            },
            pointer: {
                length: '90%',
                color: 'auto'
            },
            title: {
                textStyle: {
                    color: '#333'
                }
            },
            detail: {
                textStyle: {
                    color: 'auto'
                }
            }
        },
        textStyle: {
            fontFamily: 'Arial, Verdana, sans-serif'
        }
    };

    //echart Donut
    if ($('#echart_sedeRoca').length ){
        var fecha = $("#fecha").val();
        console.log(fecha);
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '/consultaTurnosPorEstacion',
            data: {fecha: fecha },
            type: "GET", dataType: "json",
            success: function(ret){
                generarGrafico('echart_sedeRoca',ret,'Sede ROCA','Por estación');
                /*generarGraficoMin('echart_sede01',ret,'Sede 01');
                generarGraficoMin('echart_sede02',ret,'Sede 02');
                generarGraficoMin('echart_sede03',ret,'Sede 03');
                generarGraficoMin('echart_sede04',ret,'Sede 04');*/
            },
            error: function(xhr, status, error) {
              var err = eval("(" + xhr.responseText + ")");
            }
        });
    }

    function generarGrafico(elementId, datos, titulo = '', subtitulo = ''){
        
        var titulos = datos.map(function(value,index) { return value.name; });

        var echartDonut = echarts.init(document.getElementById(elementId), theme);
        echartDonut.setOption({
        title: {
            text: titulo,
            subtext:subtitulo
        },
        tooltip: {
            trigger: 'item',
            formatter: "{b} {c} ({d}%)"
            //formatter: "{a}: <br/>{b} {c} ({d}%)"
        },
        calculable: true,
        legend: {
            orient: 'horizontal',
            x: 'center',
            y: 'bottom',
            /*orient: 'vertical',
            x: 'left',
            y: 'center',
            itemGap: 16,*/
            //data: ['Fotografia', 'Vision', 'Psicologia', 'Medico', 'Teorico']
            data: titulos
        },
        toolbox: {
            show: true,
            feature: {          
                restore: {
                    show: true,
                    title: "Actualizar"
                },
                saveAsImage: {
                    show: true,
                    title: "Descargar"
                }
            }
        },        
        series: [{
            name: 'Personas en espera',
            type: 'pie',
            radius: ['45%', '60%'],
            //center: ['60%', 200],
            itemStyle: {
                normal: {
                    label: {
                        show: true,
                        formatter: "{b}:\n  {c}      \n {d}%"
                    }
                },
                emphasis: {
                    label: {
                        show: true,
                        textStyle: {
                            fontSize: '12',
                            fontWeight: 'bold'
                        }
                    }
                }
            },
            data: datos
            /*data: [{
                value: 335,
                name: 'Fotografia'                
                }, {
                value: 310,
                name: 'Vision'
                }, {
                value: 234,
                name: 'Psicologia'
                }, {
                value: 135,
                name: 'Medico'
                }, {
                value: 1548,
                name: 'Teorico'
            }]*/
        }]
        });

    }

    function generarGraficoMin(elementId, datos, titulo = '', subtitulo = ''){
        var echartDonut = echarts.init(document.getElementById(elementId), theme);
        echartDonut.setOption({
        title: {
            text: titulo,
            subtext:subtitulo
        },
        tooltip: {
            trigger: 'item',
            formatter: "{b} {d}%"
        },
        toolbox: {
            show: true,
            feature: {
                saveAsImage: {
                    show: true,
                    title: "Descargar"
                }
            }
        },        
        series: [{
            type: 'pie',
            radius: ['0%', '70%'],
            center: ['35%', 80],
            itemStyle: {
                normal: {
                    label: {
                        show: true,
                        position:'inside',
                        formatter: "{c}",
                        textStyle: {
                            fontSize: '10',
                            fontWeight: 'normal'
                        }
                    }
                },
                emphasis: {
                    label: {
                        show: true,
                        formatter: "{c}",
                        textStyle: {
                            fontSize: '16',
                            fontWeight: 'normal'
                        }
                    },
                    //color: '#FF0000'
                }
            },
            data: datos
        }]
        });

    }
}