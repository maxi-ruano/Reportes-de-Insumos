var myTimeout;
var automatico = true;

$(document).ready(function() {

    //Control botoneraDasboard para Pausar / Start Reload
    $("#pausarReload, #startReload").change(function () {  
        
        if(this.value == 'start'){
            automatico = true;
            myTimeout = setTimeout(reload, 15000);
        }else{
            automatico = false;
            clearTimeout(myTimeout);
        }

        //alert('reload '+this.value+'  automatico: '+automatico);        
    });
    
    if(automatico == true)
        $('#startReload').click();
    else
        $('#pausarReload').click();
    
    //****end botoneraDasboard */

    
    //Configuracion del datepicker
    $('#fecha').daterangepicker({
        singleDatePicker: true,
        maxDate: moment().add(0, 'day'),
        locale: { format: 'DD-MM-YYYY' }
    }).on('change', function(e) {
        $('#btnConsultar').click();
    });

    init_charts();

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
        var sucursales = [];
        var elementId = '';
        $("#echart_sedes").empty();

        //Obtener sucursales
        $.ajax({
            url: '/api/funciones/obtenerSucursales',
            type: "POST", dataType: "json",
            success: function(ret){
               sucursales = ret;
            }
        });

        //Generar Primer Grafico de forma circula de la sucursal Roca
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '/consultaTurnosEnEspera',
            data: {fecha: fecha, sucursal: 1 },
            type: "GET", dataType: "json",
            async:false,
            success: function(datos){
                generarGrafico('echart_sedeRoca',datos, 'Por Estación');
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
        
        //Generar Grafico detallado por sucursal
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '/consultaTurnosEnEsperaPorSucursal',
            data: {fecha: fecha },
            type: "GET", 
            success: function(datos){
                generarGraficoSucursales('echart_sedes',datos,sucursales);
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
        },
        calculable: true,
        legend: {
            orient: 'horizontal',
            x: 'center',
            y: 'bottom',
            itemWidth: 20,
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
            radius: ['45%', '70%'],
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
            }]
        });

    }


    function generarGraficoSucursales(elementId, datos, titulos){
        
        var myChart = echarts.init(document.getElementById(elementId));
        var option = null;
        
        //Obtener solo los id tanto de sucursales como estaciones, ordenados de forma ascedente
        var sucursales = [...new Set(datos.map(item => item.sucursal_id).sort(function(a,b){return a - b;}))];
        var estaciones = [...new Set(datos.map(item => item.estacion_id).sort(function(a,b){return a - b;}))];

        //Mostrar solo los name de las sucursales que se encuentre en el array sucursales 
        var days = titulos.filter(item => sucursales.includes(item.id)).map(function(value,index) { return value.name;  });
        
        //Obtener la descripcion de las estaciones que se generaron en datos
        var hours = [...(new Set(datos.map(({ estacion }) => estacion)))];

        option = {
            title: [],
            singleAxis: [],
            series: []
        };

        echarts.util.each(days, function (day, idx) {
            option.title.push({
                textBaseline: 'middle',
                top: (idx + 0.8) * 97 / days.length + '%',
                text: day
            });
            option.singleAxis.push({
                left: 140,
                type: 'category',
                boundaryGap: true,
                data: hours,
                top: (idx * 97 / days.length + 7.6) + '%',
                height: (100 / days.length - 10) + '%',
                axisLabel: {
                    interval: 0
                }
            });
            option.series.push({
                singleAxisIndex: idx,
                coordinateSystem: 'singleAxis',
                type: 'scatter',
                data: [],
                itemStyle: {
                    normal: {
                        label:{
                            textStyle:{
                                fontWeight:'bold',
                                fontSize:16
                            },
                            show:true,
                            position: 'inside',
                            formatter: function(value) {
                                return value.data[1];
                            }
                        }
                    }
                },
                symbolSize: function (dataItem) {
                    if(idx==0)
                        return dataItem[1] * 3.5;
                    else
                        return dataItem[1] * 5;
                }
            });
        });

        echarts.util.each(datos, function (item) {
            //Obtener la posicion del index segun el value
            var sucursal = sucursales.indexOf(item.sucursal_id); 
            var estacion = estaciones.indexOf(item.estacion_id);
            option.series[sucursal].data.push([estacion, item.cant]);
            
            //console.log(item.sucursal_id+' | '+sucursal+' | estacion: '+item.estacion_id+' | '+estacion);
        }); 

        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        }

    }

}