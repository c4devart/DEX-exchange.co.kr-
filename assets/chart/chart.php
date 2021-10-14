<!DOCTYPE html>
<html>
	<head>

		<title>TradingView Charting Library demo -- testing mess</title>

		<!-- Fix for iOS Safari zooming bug -->
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	</head>
	<body style="margin:0;">
		<style>
			#chartdiv, #chartdiv iframe, #chartdiv iframe html{
				width : 968px;
				height : 590px !important;
			}
		</style>
		<?php
			$date = new DateTime();
			$tz = date_timezone_get($date);
			$timezone = timezone_name_get($tz);
		?>
		<div id="chartdiv" style="width:900px;max-height:500px;"></div>
	</body>	

	<script type="text/javascript" src="charting_library/charting_library.min.js"></script>
	<script src="../../socket/node_modules/socket.io-client/dist/socket.io.js"></script>
	<script type="text/javascript">

		var timezone = '<?php echo $timezone;?>';
		var socket = io.connect('http://localhost:4200');
		var market = 'BTC_KRW';

		socket.on('connect', function(data) {
			var socketData = {
				market : market,
				token : 'token'
			}
			socket.emit('joinMarket', socketData);
		});

		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}

		var supportedResolutions = ["1", "3", "5", "15", "30", "60", "1D", "1W"];
		var config = {
			supported_resolutions: supportedResolutions
		};

		var JSdatafeed = {
			onReady: cb => {
				setTimeout(() => cb(config), 0)					
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
					timezone: timezone,
					ticker: symbolName,
					exchange: split_data[0],
					minmov: 10,
					pricescale: 10,
					has_intraday: true,
					intraday_multipliers: ['1', '60'],
					supported_resolution:  supportedResolutions,
					volume_precision: 20,
					data_status: 'streaming',
				}
				setTimeout(function() {
					onSymbolResolvedCallback(symbol_stub)
				}, 0)
			},
			getBars: function(symbolInfo, resolution, from, to, onHistoryCallback, onErrorCallback, firstDataRequest) {
				var xhr = new XMLHttpRequest();
				xhr.open('GET', "http://localhost.trade.coinsky.co.kr/api/getChartData/BTC/KRW", true);
				xhr.send();
				xhr.onreadystatechange = processRequest;
				function processRequest(e) {
					if (xhr.readyState == 4 && xhr.status == 200) {
						data = JSON.parse(xhr.responseText);
						var bars = [];
						if (data.length) {
							bars = data.map(function(d){
								return {
									time: d.date, //TradingView requires bar time in ms
									low: d.low,
									high: d.high,
									open: d.open,
									close: d.close,
									volume: d.volume
								}
							})
							onHistoryCallback(bars, {noData: false});
						}else{
							onHistoryCallback(bars, {noData: true});
						}
					}
				}
			},
			subscribeBars: (symbolInfo, resolution, onRealtimeCallback, subscribeUID, onResetCacheNeededCallback) => {
				socket.on('updateChart', (chartData) => {
					var data = {
						time : chartData.time,
						open : chartData.open,
						close : chartData.close,
						low : chartData.low,
						high : chartData.high,
						volume : chartData.volume
					}
					onRealtimeCallback(data, {noData: false});
				})
			},
			unsubscribeBars: subscriberUID => {
				
			},
			calculateHistoryDepth: (resolution, resolutionBack, intervalBack) => {
				// return resolution < 60 ? {resolutionBack: 'D', intervalBack: '1'} : undefined
				return 1;
			},
			getMarks: (symbolInfo, startDate, endDate, onDataCallback, resolution) => {
			},
			getTimeScaleMarks: (symbolInfo, startDate, endDate, onDataCallback, resolution) => {
			},
			getServerTime: cb => {
			}
		}

		var widgetOptions = {
			fullscreen: true,
			symbol: 'BTC/KRW',
			interval: '1',
			toolbar_bg: '#f4f7f9',
			container_id: "chartdiv",
			datafeed: JSdatafeed,
			library_path: "charting_library/",
			autosize: true,
			timezone : timezone,
			locale: getParameterByName('lang') || "ko",
			studies_access: { type: 'black', tools: [ { name: "macd", grayed: true } ] },
			disabled_features: ["save_chart_properties_to_local_storage", "volume_force_overlay"],
			enabled_features: ["move_logo_to_main_pane", "study_templates"],
			overrides: {
				"mainSeriesProperties.style": 1,
				"symbolWatermarkProperties.color" : "#944",
				"mainSeriesProperties.barStyle.upColor" : "#ff0000",
				"mainSeriesProperties.barStyle.downColor" : "#1155cc",
				"mainSeriesProperties.candleStyle.upColor" : "#ff0000",
				"mainSeriesProperties.candleStyle.downColor" : "#1155cc",
				"mainSeriesProperties.candleStyle.borderUpColor" : "#ff0000",
				"mainSeriesProperties.candleStyle.borderDownColor" : "#1155cc",
				"mainSeriesProperties.hollowCandleStyle.upColor" : "#ff0000",
				"mainSeriesProperties.hollowCandleStyle.downColor" : "#1155cc",
				"mainSeriesProperties.hollowCandleStyle.borderUpColor" : "#ff0000",
				"mainSeriesProperties.hollowCandleStyle.borderDownColor" : "#1155cc",
				"mainSeriesProperties.haStyle.upColor" : "#ff0000",
				"mainSeriesProperties.haStyle.downColor" : "#1155cc",
				"mainSeriesProperties.haStyle.borderUpColor" : "#ff0000",
				"mainSeriesProperties.haStyle.borderDownColor" : "#1155cc",
				"mainSeriesProperties.barStyle.upColor" : "#ff0000",
				"mainSeriesProperties.barStyle.downColor" : "#1155cc",
				"volumePaneSize": "large"
			},
			studies_overrides: {
				"volume.volume.color.0": "#1155cc",
				"volume.volume.color.1": "#ff0000",
				"volume.volume.transparency": 70,
				"volume.show ma": false,
				"bollinger bands.median.color": "#000000",
				"bollinger bands.upper.linewidth": 1
			},
			time_frames: [
				{ text: "1w", resolution: "1W" },
				{ text: "1d", resolution: "1D" },
				{ text: "1h", resolution: "60" },
				{ text: "30m", resolution: "30" },
				{ text: "15m", resolution: "15" },
				{ text: "10m", resolution: "10" },
				{ text: "5m", resolution: "5" },
				{ text: "3m", resolution: "3" },
				{ text: "1m", resolution: "1" }
			],
			charts_storage_url: 'http://saveload.tradingview.com',
			charts_storage_api_version: "1.1",
			client_id: 'trade.coinsky.co.kr',
			user_id: 'steveik',
			favorites: {
				intervals: ["1", "3", "5"],
				chartTypes: ["Area", "Line"]
			}
		};

		window.TradingView.onready(() => {
			var widget = window.tvWidget = new window.TradingView.widget(widgetOptions);

			widget.onChartReady(() => {
				console.log('Chart has loaded!');
			});
		});
	</script>

</html>
