function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
var toolberBg = '#f4f7f9',
    interval = ['1', '3', '5', '15', '30'],
    customCssUrl = base_url + 'assets/css/tradingview.css?v=coinsky';
        
var disabledFeatures = [
    'save_chart_properties_to_local_storage',
    'volume_force_overlay',
    'study_templates',
    'header_undo_redo',
    'header_symbol_search',
    'symbol_search_hot_key',
    'adaptive_logo',
    'go_to_date',
    'header_compare',
    'left_toolbar'
];
var overrides = {
    'mainSeriesProperties.style': 1,
    'mainSeriesProperties.candleStyle.upColor': '#cc0000',
    'mainSeriesProperties.candleStyle.downColor': '#1155cc',
    'mainSeriesProperties.candleStyle.wickUpColor': '#cc0000',
    'mainSeriesProperties.candleStyle.wickDownColor': '#1155cc',
    'mainSeriesProperties.candleStyle.borderUpColor': '#cc0000',
    'mainSeriesProperties.candleStyle.borderDownColor': '#1155cc',
    'mainSeriesProperties.hollowCandleStyle.upColor': '#cc0000',
    'mainSeriesProperties.hollowCandleStyle.downColor': '#1155cc',
    'mainSeriesProperties.hollowCandleStyle.wickUpColor': '#cc0000',
    'mainSeriesProperties.hollowCandleStyle.wickDownColor': '#1155cc',
    'mainSeriesProperties.hollowCandleStyle.borderUpColor': '#cc0000',
    'mainSeriesProperties.hollowCandleStyle.borderDownColor': '#1155cc',
    'mainSeriesProperties.haStyle.upColor': '#cc0000',
    'mainSeriesProperties.haStyle.downColor': '#1155cc',
    'mainSeriesProperties.haStyle.wickUpColor': '#cc0000',
    'mainSeriesProperties.haStyle.wickDownColor': '#1155cc',
    'mainSeriesProperties.haStyle.borderUpColor': '#cc0000',
    'mainSeriesProperties.haStyle.borderDownColor': '#1155cc',
    'study_Overlay@tv-basicstudies.style': 1,
    'study_Overlay@tv-basicstudies.lineStyle.color': '#351c75',
    'volumePaneSize': 'medium'
};
var studiesOverrides = {
    'volume.volume.plottype': 'columns',
    'volume.volume.color.0': '#3c78d8',
    'volume.volume.color.1': '#e16d6d',
    'volume.volume.transparency': 10,
    'volume.volume ma.plottype': 'line',
    'volume.volume ma.color': '#9bba8e',
    'volume.volume ma.transparency': 50,
    'volume.volume ma.linewidth': 2,
    'volume.MA length': 15,
    'volume.show ma': true
};

var historyURL;
var oldHistoryURL = '';
var iOldChartInterval = 0;
var iChartInterval = 1;
var bUpdate = false;
var lastChartDate = 0;

TradingView.onready(function(){
    var supportedResolutions = ["1", "3", "5", "15", "30", "60", "1440"];
    var config = {
        supported_resolutions: supportedResolutions
    };

    var lastBar;

    JSdatafeed = {
        onReady: cb => {
            setTimeout(() => cb(config), 0);                                
        },
        searchSymbols: (userInput, exchange, symbolType, onResultReadyCallback) => {
        },
        resolveSymbol: (symbolName, onSymbolResolvedCallback, onResolveErrorCallback) => {
            var split_data = symbolName.split(/[:/]/);
            var symbol_stub = {
                name: symbolName,
                description: '',
                type: 'crypto',
                session: '24x7',
                timezone: 'Asia/Seoul',
                ticker: symbolName,
                exchange: split_data[0],
                minmov: 1,
                pricescale: 1,
                has_intraday: true,
                intraday_multipliers: ['1'],
                supported_resolution:  supportedResolutions,
                volume_precision: 8,
				data_status: 'streaming'
            }
            setTimeout(function() {
                onSymbolResolvedCallback(symbol_stub)
            }, 0)
        },
        getBars: function(symbolInfo, resolution, from, to, onHistoryCallback, onErrorCallback, firstDataRequest) {
            var nodata = false;            
            if (iChartInterval == iOldChartInterval) {
                nodata = true;
            }            
            var xhr = new XMLHttpRequest();
            xhr.open('GET', base_url + "api/getChartData/"+target+"/"+base, true);
            xhr.send();
            xhr.onreadystatechange = processRequest;
            function processRequest(e) {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    data = JSON.parse(xhr.responseText);
                    var bars = [];
                    iOldChartInterval = iChartInterval;
                    if (nodata) {
                    } else {
                        if (data.length > 1) {
                            bars = data.map(function(d){
                                return {
                                    time: d.date,
                                    low: d.low,
                                    high: d.high,
                                    open: d.open,
                                    close: d.close,
                                    volume: d.volume 
                                }
                            })
                            var tempLastBar = data[data.length-1];
                            lastBar = {
                                time: tempLastBar.date,
                                low: tempLastBar.low,
                                high: tempLastBar.high,
                                open: tempLastBar.open,
                                close: tempLastBar.close,
                                volume: tempLastBar.volume
                            }                           
                        }else{
                            var date = new Date();
                            var time = date.getTime();
                            lastBar = {
                                time: time,
                                low: null,
                                high: null,
                                open: null,
                                close: null,
                                volume: null
                            }
                        }
                    }
                    onHistoryCallback(bars, {noData: nodata, nextTime: undefined});                    
                }
            }
        },
        subscribeBars: (symbolInfo, resolution, onRealtimeCallback, subscribeUID, onResetCacheNeededCallback) => {
            socket.on('updateChart', (addData) => {
                var tempInterval = resolution * 60 * 1000;
                var addTime = addData.time;
                addTime = addTime - addTime % tempInterval;
                var addClose = addData.close;
                var addVolume = addData.volume;
                var addVolume = parseFloat(addVolume);
                var lastTime, lastOpen, lastClose, lastLow, lastHigh, lastVolume;
                var newTime, newOpen, newClose, newLow, newHigh, newVolume;
                lastTime = lastBar.time;
                lastOpen = lastBar.open;
                lastClose = lastBar.close;
                lastLow = lastBar.low;
                lastHigh = lastBar.high;
                lastVolume = lastBar.volume;
                var tempDiff = addTime - lastTime;
                if(tempDiff > 0){
                    newTime = addTime;
                    newOpen = lastClose;
                    newClose = addClose;
                    if(newOpen >= newClose){
                        newLow = newClose;
                        newHigh = newOpen;
                    }else{
                        newLow = newOpen;
                        newHigh = newClose;
                    }
                    newVolume = addVolume.toFixed(8);
                    var newLastBar = {
                        time : newTime,
                        open : newOpen,
                        close : newClose,
                        low : newLow,
                        high : newHigh,
                        volume : newVolume
                    }
                    onRealtimeCallback(newLastBar, {noData: false});
                    lastBar = newLastBar;
                }else{
                    newTime = lastTime;
                    newOpen = lastOpen;
                    newClose = addClose;
                    if(lastLow > addClose){
                        newLow = addClose;
                    }else{
                        newLow = lastLow;
                    }
                    if(lastHigh < addClose){
                        newHigh = addClose;
                    }else{
                        newHigh = lastHigh;
                    }
                    newVolume = parseFloat(lastVolume) + parseFloat(addVolume);
                    newVolume = newVolume.toFixed(8);
                    var changedLastBar = {
                        time : newTime,
                        open : newOpen,
                        close : newClose,
                        low : newLow,
                        high : newHigh,
                        volume : newVolume
                    }
                    onRealtimeCallback(changedLastBar, {noData: false});
                    lastBar = changedLastBar;
                }
            })
            socket.on('updateChartMinBar', (addData) => {
                var tempInterval = resolution * 60 * 1000;
                var addTime = addData.time;
                addTime = addTime - addTime % tempInterval;
                var addClose = addData.close;
                var addVolume = addData.volume;
                var addVolume = parseFloat(addVolume);
                var lastTime, lastOpen, lastClose, lastLow, lastHigh, lastVolume;
                var newTime, newOpen, newClose, newLow, newHigh, newVolume;
                lastTime = lastBar.time;
                lastOpen = lastBar.open;
                lastClose = lastBar.close;
                lastLow = lastBar.low;
                lastHigh = lastBar.high;
                lastVolume = lastBar.volume;
                var tempDiff = addTime - lastTime;
                if(tempDiff > 0){
                    newTime = addTime;
                    newOpen = lastClose;
                    newClose = addClose;
                    if(newOpen >= newClose){
                        newLow = newClose;
                        newHigh = newOpen;
                    }else{
                        newLow = newOpen;
                        newHigh = newClose;
                    }
                    newVolume = addVolume.toFixed(8);
                    var newLastBar = {
                        time : newTime,
                        open : newOpen,
                        close : newClose,
                        low : newLow,
                        high : newHigh,
                        volume : newVolume
                    }
                    onRealtimeCallback(newLastBar, {noData: false});
                    lastBar = newLastBar;
                }else{
                    newTime = lastTime;
                    newOpen = lastOpen;
                    newClose = addClose;
                    if(lastLow > addClose){
                        newLow = addClose;
                    }else{
                        newLow = lastLow;
                    }
                    if(lastHigh < addClose){
                        newHigh = addClose;
                    }else{
                        newHigh = lastHigh;
                    }
                    newVolume = parseFloat(lastVolume) + parseFloat(addVolume);
                    newVolume = newVolume.toFixed(8);
                    var changedLastBar = {
                        time : newTime,
                        open : newOpen,
                        close : newClose,
                        low : newLow,
                        high : newHigh,
                        volume : newVolume
                    }
                    onRealtimeCallback(changedLastBar, {noData: false});
                    lastBar = changedLastBar;
                }
            })
        },
        unsubscribeBars: subscriberUID => {

        },
        calculateHistoryDepth: (resolution, resolutionBack, intervalBack) => {
            return resolution < 60 ? {resolutionBack: 'D', intervalBack: '1'} : undefined
        },
        getMarks: (symbolInfo, startDate, endDate, onDataCallback, resolution) => {
            socket.on('updateChart', (chartData) => {
                var data = {
                    time : lastBar.time,
                    open : lastBar.open,
                    close : lastBar.close,
                    low : lastBar.low,
                    high : lastBar.high,
                    volume : lastBar.volume
                }
                onDataCallback(data, {noData: false});
            })
        },
        getTimeScaleMarks: (symbolInfo, startDate, endDate, onDataCallback, resolution) => {
        },
        getServerTime: cb => {
        }
    }

    widget = new TradingView.widget({
        fullscreen: false,
        symbol: target+'/'+base,
        interval: '1',
        toolbar_bg: toolberBg,
        container_id: "chartdiv",
        datafeed: JSdatafeed,
        library_path: base_url + "assets/chart/charting_library/",
        autosize: true,
        locale: getParameterByName('lang') || "ko",
        disabled_features: disabledFeatures,
        enabled_features: [
            'move_logo_to_main_pane'
        ],
        overrides: overrides,
        studies_overrides: studiesOverrides,
        debug: false,
        timezone : 'Asia/Seoul',
        time_frames: [
            { text: "1d", resolution: "1440" },
            { text: "1h", resolution: "60" },
            { text: "30m", resolution: "30" },
            { text: "15m", resolution: "15" },
            { text: "10m", resolution: "10" },
            { text: "5m", resolution: "5" },
            { text: "3m", resolution: "3" },
            { text: "1m", resolution: "1" }
        ],
        favorites: {
            intervals: interval
        },
        custom_css_url: customCssUrl
    });

    widget.onChartReady(function(){
        var chartContainer = $('#chartdiv iframe');

        chartContainer.contents().find('.js-rootresizer__contents').css('opacity', 1);

        widget.chart().createStudy('Moving Average', false, false, [15], null, {'Plot.color': '#bf9000'});
        widget.chart().createStudy('Moving Average', false, false, [60], null, {'Plot.color': '#45818e'});  
        widget.activeChart().onIntervalChanged().subscribe({}, function(interval){
            iChartInterval = interval;
        });      
    });
});
