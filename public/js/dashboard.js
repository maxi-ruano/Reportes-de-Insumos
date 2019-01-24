var myTimeout;

$(document).ready(function() {
   
    iniciarTimeout();

    //Control botoneraDasboard para Pausar / Start
    $("#botoneraDashboard #starPause").change(function(){
        if($(this).prop('checked') == true){
            iniciarTimeout();
        }else{
            clearTimeout(myTimeout);
        }
    });

    $("#botoneraDashboard #pagNext").click(function(){
        /*$(this).button('loading').delay(10).queue(function(){
            $(this).button('reset').dequeue();*/
            reload();
        //});
    });
    
    //Configuracion del datepicker
    $('#fecha').daterangepicker({
        singleDatePicker: true,
        drops: 'up',
        maxDate: moment().add(5, 'day'),
        locale: { format: 'DD-MM-YYYY' }
    }).on('change', function(e) {
        $('#btnConsultar').click();
    });

    init_charts();

});

function iniciarTimeout (){
    var date = new Date();
    var hora = date.getHours(); 
    var d = date.getDate();
    var m = date.getMonth() + 1;
    var y = date.getFullYear();
    var actual = (d <= 9 ? '0' + d : d) + '-' + (m <= 9 ? '0' + m : m) + '-' + y;
    var fecha = $("#fecha").val();
    
    if (hora < 7 && fecha != actual){
        $("#fecha").val(actual);
        $('#btnConsultar').click();
    }else{
        if(fecha == actual)
            myTimeout = setTimeout(reload, 20000);
    }
    
}

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
    /*if ($('#echart_sedeRoca').length ){
        
        var fecha = $("#fecha").val();
        var elementId = '';

        //Generar Primer Grafico de forma circula de la sucursal Roca
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '/consultaTurnosEnEspera',
            data: {fecha: fecha, sucursal: 1 },
            type: "GET", dataType: "json",
            success: function(datos){
                generarGrafico('echart_sedeRoca',datos, 'Por Estación');
            },
            error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
            }
        });
    
    }*/

    if ($('#echart_sedes').length ){
        var fecha = $("#fecha").val();      
        var sucursales = [];
        //Obtener sucursales
        $.ajax({
            url: '/api/funciones/obtenerSucursales',
            type: "POST", dataType: "json",
            success: function(ret){
               sucursales = ret;
            }
        });

        //Generar Grafico detallado por sucursal
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: '/consultaTurnosEnEsperaPorSucursal',
            data: {fecha: fecha },
            type: "GET", 
            success: function(datos){
                //Solo para manejar los datos de forma manual ---Pierre
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
            orient: 'vertical',
            x: 'left',
            y: 'bottom',
            //itemWidth: 12,
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
            radius: ['40%', '60%'],
            center : ['50%', '45%'],
            selectedMode:'multiple',
            animationType: 'scale',
            animationEasing: 'elasticOut',
            animationDelay: function (idx) {
                return Math.random() * 400;
            },
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
                            fontSize: '14',
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
            //Configurar titulos de sucursales
            option.title.push({
                textBaseline: 'middle',
                top: (idx + 0.8) * 97 / days.length + '%',
                text: day,
                textStyle:{
                    fontWeight:'bold',
                    fontSize:'18'
                            
                }
            });
            option.singleAxis.push({
                left: 130,
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
                //type: 'scatter',
                type: 'effectScatter',
                rippleEffect:{
                    scale:2
                },
                /*symbol:'pin',
                symbolOffset:[0,'10%'],
                showEffectOn: 'emphasis',*/
                data: [],
                itemStyle: {
                    normal: {
                        label:{
                            textStyle:{
                                fontWeight:'bold',
                                fontSize:30
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
                    return dataItem[1] * 4;
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