<x-layout>
    <!-- Content Header (Page header) -->
    <x-breadcrump title="Dashboard" parent="Dashboard" child="List" />


       <div class="card">
        <!-- FIXED: Added explicit width and height directly to the container -->
        <div id="hbar"  data-chart="directorate"  style="width: 100%; height: 900px;"></div>
    </div>


     <hr>

    <div class="card">
        <!-- FIXED: Added explicit width and height directly to the container -->
        <div id="vbar" id="bytheme" style="width: 100%; height: 500px;"></div>
    </div>




 <hr>

    <div class="card">
        <!-- FIXED: Added explicit width and height directly to the container -->
        <div id="piechart"  data-chart="partner" style="width: 100%; height: 500px;"></div>
    </div>


 <hr>

    <div class="card">
        <!-- FIXED: Added explicit width and height directly to the container -->
        <div id="bytheme" data-chart="theme" style="width: 100%; height: 500px;"></div>
    </div>



{{-- <div id="hbar" data-chart="directorate" style="width: 100%; height: 900px;"></div>
<div id="vbar" data-chart="partner" style="width: 100%; height: 500px;"></div>
<div id="piechart" data-chart="status" style="width: 100%; height: 500px;"></div>
<div id="bytheme" data-chart="theme" style="width: 100%; height: 500px;"></div> --}}






    <!-- Your chart container boxes placed anywhere on your blade page layout -->
{{-- <div id="hbar" data-chart="directorate" style="width: 100%; height: 400px; margin-bottom: 30px;"></div>
<div id="bytheme" data-chart="theme" style="width: 100%; height: 400px; margin-bottom: 30px;"></div>
<div id="vbar" data-chart="partner" style="width: 100%; height: 400px; margin-bottom: 30px;"></div> --}}


    <script type="text/javascript" src="https://fastly.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>


<!-- One Unified ECharts Intersection & Animation Engine -->
<script type="text/javascript">
    // ================================================================
    // 1. DYNAMIC COLOR WHEEL FACTORY FUNCTION
    // ================================================================
    function generateDynamicColors(count, saturation, lightness, hue) {
         var colors = [];
         //colors.push('hsl(' + hue + ', ' + saturation + '%, ' + lightness + '%)');


       for (var i = 0; i < count; i++) {
             hue = Math.round((360 / count) * i);
            colors.push('hsl(' + hue + ', ' + saturation + '%, ' + lightness + '%)');
        }
        return colors;
    }

    // ================================================================
    // 2. MODULAR CHART INITIALIZATION REGISTRY (YOUR SLICES)
    // ================================================================

    // Slice 1: Initiatives per Directorate (hbar)
    function initDirectorateChart(domElement) {
        var myChart = echarts.init(domElement, null, { renderer: 'canvas', useDirtyRect: false });
        var dataValues = {!! json_encode($counts) !!};
        var option = {
            // Upgraded to use your non-repeating color spectrum instead of flat blue
            color: generateDynamicColors(dataValues.length, 65, 45, 225),
            title: {
                text: 'Initiatives Per Dirctorate',
                subtext: 'Total Count Breakdown per Managed Directorate'
            },
            tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
            grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
            xAxis: { type: 'value', boundaryGap: [0, 0.01], minInterval: 1 },
            yAxis: { type: 'category', data: {!! json_encode($labels) !!} },
            series: [{
                name: 'Total Initiatives',
                type: 'bar',
                colorBy: 'data', // Unlocks distinct column colors
                data: dataValues,
                label: { show: true, position: 'right' }
            }]
        };
        myChart.setOption(option);
        window.addEventListener('resize', myChart.resize);
    }

    // Slice 2: Total Registered Activities per Partner (vbar)
    function initPartnerChart(domElement) {
        var myChart = echarts.init(domElement, null, { renderer: 'canvas', useDirtyRect: false });
        var dataValues = {!! json_encode($activityCounts) !!};
        var option = {
            color: generateDynamicColors(dataValues.length, 60, 45, 191),
            title: { text: 'Total Registered Activities per Partner', left: 'center', top: '1%' },
            tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
            grid: { left: '3%', right: '4%', bottom: '8%', containLabel: true },
            xAxis: [{
                type: 'category',
                data: {!! json_encode($partnerLabels) !!},
                axisTick: { alignWithLabel: true },
                axisLabel: { interval: 0, rotate: 30 }
            }],
            yAxis: [{ type: 'value', minInterval: 1 }],
            series: [{
                name: 'Total Activities',
                type: 'bar',
                barWidth: '55%',
                colorBy: 'data',
                data: dataValues,
                label: { show: true, position: 'top', fontWeight: 'bold' }
            }]
        };
        myChart.setOption(option);
        window.addEventListener('resize', myChart.resize);
    }

    // Slice 3: Activities Breakdown by Status (piechart)
    function initStatusPieChart(domElement) {
        var myChart = echarts.init(domElement, null, { renderer: 'canvas', useDirtyRect: false });
        var rawChartData = {!! json_encode($pieData) !!};
        var option = {
            color: generateDynamicColors(rawChartData.length, 65, 50, 215),
            title: { text: 'Activities Breakdown by Status', left: 'center', top: '1%' },
            tooltip: { trigger: 'item', formatter: '{a} <br/>{b} : <strong>{c}</strong> ({d}%)' },
            legend: { bottom: '2%', left: 'center' },
            series: [{
                name: 'Activity Status',
                type: 'pie',
                radius: ['40%', '70%'],
                center: ['50%', '45%'],
                avoidLabelOverlap: false,
                itemStyle: { borderRadius: 6, borderColor: '#fff', borderWidth: 2 },
                label: { show: false, position: 'center' },
                emphasis: { label: { show: true, fontSize: 32, fontWeight: 'bold' } },
                labelLine: { show: false },
                data: rawChartData
            }]
        };
        myChart.setOption(option);
        window.addEventListener('resize', myChart.resize);
    }

    // Slice 4: Initiatives Distribution per Strategic Theme (bytheme)
    function initThemeChart(domElement) {
        var myChart = echarts.init(domElement, null, { renderer: 'canvas', useDirtyRect: false });
        var dataValues = {!! json_encode($initiativeCounts) !!};
        var option = {
            color: generateDynamicColors(dataValues.length, 65, 45, 240),
            title: { text: 'Initiatives Distribution per Strategic Theme' },
            tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
            grid: { left: '3%', right: '5%', bottom: '3%', containLabel: true },
            legend: { show: false },
            xAxis: { type: 'value', boundaryGap: [0, 0.01], minInterval: 1 },
            yAxis: { type: 'category', data: {!! json_encode($themeLabels) !!} },
            series: [{
                name: 'Total Initiatives',
                type: 'bar',
                colorBy: 'data',
                data: dataValues,
                label: { show: true, position: 'right', fontWeight: 'bold' }
            }]
        };
        myChart.setOption(option);
        window.addEventListener('resize', myChart.resize);
    }

    // ================================================================
    // 3. CENTRALIZED SCROLL-TRIGGERED VIEWPORT INTERSECTION OBSERVER
    // ================================================================
    var observerOptions = {
        root: null,         // Tracks boundaries relative to the screen viewport window
        rootMargin: '0px',
        threshold: 0.5     // Initializes and animates exactly when 15% of the chart card is visible
    };

    var lazyChartObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            // Trigger layout rendering engine only when scrolled into view
            if (entry.isIntersecting) {
                var element = entry.target;
                var targetId = element.id;

                // Route the targeted DOM directly to its rendering wrapper
                if (targetId === 'hbar') {
                    initDirectorateChart(element);
                } else if (targetId === 'vbar') {
                    initPartnerChart(element);
                } else if (targetId === 'piechart') {
                    initStatusPieChart(element);
                } else if (targetId === 'bytheme') {
                    initThemeChart(element);
                }

                // Stop tracking this chart box container to fix its animation states
                observer.unobserve(element);
            }
        });
    }, observerOptions);

    // Dynamic Discovery: Boot up observer bindings on your HTML targets
    var chartIds = ['hbar', 'vbar', 'piechart', 'bytheme'];
    chartIds.forEach(function(id) {
        var targetDom = document.getElementById(id);
        if (targetDom) {
            lazyChartObserver.observe(targetDom);
        }
    });
</script>


</x-layout>
