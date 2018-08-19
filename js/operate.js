var myChart,myChart1,myChart2,myChart3;//为了绑定窗口resize时重绘图表，这里使用图表的全局变量

$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='operate.php']").parent().addClass("active");	
	
	loadManWomanCount();
	loadKindsOfJobUserCount();
	loadKindsOfQuestionCount();
	loadKindsOfTopicCount();
	window.addEventListener("resize", () => { 
	    myChart.resize();  
	    myChart1.resize();  
	    myChart2.resize();  
	    myChart3.resize();
	});
});

/**
 * 加载网站男女人数信息统计
 */
function loadManWomanCount(){
	$.get(
		"../Controller/OperatorController.php",
		{action:"loadManWomanCount"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.manWomanCount.forEach(function(value,index){
				var manCount=value.maleCount;
				var womanCount=value.femaleCount;
				showManWomanCount(manCount,womanCount);
			});
		}
	);
}


/**
 * 加载网站男女人数信息统计
 */
function showManWomanCount(manCount,womanCount){
	var dom = document.getElementById("manWomanCountContainer");
	myChart = echarts.init(dom,'light');
	myChart.setOption({
		tooltip:{},//这行代码使得鼠标放在饼图上时，显示信息，如果没有，就不现实
	    series : [
	        {
	            name: '男女人数比例',
	            type: 'pie',
	            radius: '55%',
	            data:[
	                {value:manCount, name:'男'},
	                {value:womanCount, name:'女'}
	            ]
	        }
	    ]
	});
}

/**
 * 加载不同种类工作人员信息统计
 */
function loadKindsOfJobUserCount(){
	$.get(
		"../Controller/OperatorController.php",
		{action:"loadKindsOfJobUserCount"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.jobUserCount.forEach(function(value,index){
				var economyCount=value.economyCount;
				var businessCount=value.businessCount;
				var lawCount=value.lawCount;
				var societyCount=value.societyCount;
				var healthCount=value.healthCount;
				
				var sportsCount=value.sportsCount;
				var artCount=value.artCount;
				var electronCount=value.electronCount;
				var amusementCount=value.amusementCount;
				var areaCount=value.areaCount;
				
				var psychicCount=value.psychicCount;
				var medicalCount=value.medicalCount;
				var scienceCount=value.scienceCount;
				var computerCount=value.computerCount;
				var otherCount=value.otherCount;
				var counts=['人员数量',economyCount,businessCount,lawCount,societyCount,healthCount,
							sportsCount,artCount,electronCount,amusementCount,areaCount,
							psychicCount,medicalCount,scienceCount,computerCount,otherCount];
				var columns=['type','经济金融','企业管理','法律法规','社会民生','健康生活',
							'体育运动','文化艺术','电子数码','娱乐休闲','地理地区','心理分析',
							'医疗卫生','科学教育','电脑网络','其他'];
				showKindsOfJobUserCount(counts,columns);
			});
		}
	);
}


/**
 * 加载不同种类工作人员信息统计
 */
function showKindsOfJobUserCount(counts,columns){
	var dom = document.getElementById("kindsOfJobUserCountContainer");
	myChart1 = echarts.init(dom,'light');
	var app = {};//暂未发现其途
	option = null;
	option = {
	    legend: {},
	    tooltip: {},
	    // 声明一个 X 轴，类目轴（category）。默认情况下，类目轴对应到 dataset 第一列。
	    dataset: {
	        // 提供一份数据。
	        source: [
	            columns,
	            counts
	        ]
	    },
	    xAxis:{type: 'category'},
	    // 声明一个 Y 轴，数值轴。
	    yAxis: {type: 'value'},
	    grid:{
	    	top:'35%'
	    },
	    // 声明多个 bar 系列，默认情况下，每个系列会自动对应到 dataset 的每一列。
	    series: [
	        {type: 'bar',name:'经济金融'},
	        {type: 'bar',name:'企业管理'},
	        {type: 'bar',name:'法律法规'},
	        {type: 'bar',name:'社会民生'},
	        {type: 'bar',name:'健康生活'},
	        {type: 'bar',name:'体育运动'},
	        {type: 'bar',name:'文化艺术'},
	        {type: 'bar',name:'电子数码'},
	        {type: 'bar',name:'娱乐休闲'},
	        {type: 'bar',name:'地理地区'},
	        {type: 'bar',name:'心理分析'},
	        {type: 'bar',name:'医疗卫生'},
	        {type: 'bar',name:'科学教育'},
	        {type: 'bar',name:'电脑网络'},
	        {type: 'bar',name:'其他'}
	    ]
	};
	if (option && typeof option === "object") {
	    myChart1.setOption(option, true);
	}
}

/**
 * 加载不同种类问题信息统计
 */
function loadKindsOfQuestionCount(){
	$.get(
		"../Controller/OperatorController.php",
		{action:"loadKindsOfQuestionCount"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.questionTypeCount.forEach(function(value,index){
				var economyCount=value.economyCount;
				var businessCount=value.businessCount;
				var lawCount=value.lawCount;
				var societyCount=value.societyCount;
				var healthCount=value.healthCount;
				
				var sportsCount=value.sportsCount;
				var artCount=value.artCount;
				var electronCount=value.electronCount;
				var amusementCount=value.amusementCount;
				var areaCount=value.areaCount;
				
				var psychicCount=value.psychicCount;
				var medicalCount=value.medicalCount;
				var scienceCount=value.scienceCount;
				var computerCount=value.computerCount;
				var otherCount=value.otherCount;
				var counts=['问题数量',economyCount,businessCount,lawCount,societyCount,healthCount,
							sportsCount,artCount,electronCount,amusementCount,areaCount,
							psychicCount,medicalCount,scienceCount,computerCount,otherCount];
				var columns=['type','经济金融','企业管理','法律法规','社会民生','健康生活',
							'体育运动','文化艺术','电子数码','娱乐休闲','地理地区','心理分析',
							'医疗卫生','科学教育','电脑网络','其他'];
				showKindsOfQuestionCount(counts,columns);
			});
		}
	);
}

/**
 * 加载不同种类问题信息统计
 */
function showKindsOfQuestionCount(counts,columns){
	var dom = document.getElementById("kindsOfQuestionCountContainer");
	myChart2 = echarts.init(dom,'light');
	var app = {};//暂未发现其途
	option = null;
	option = {
	    legend: {},
	    tooltip: {},
	    // 声明一个 X 轴，类目轴（category）。默认情况下，类目轴对应到 dataset 第一列。
	    dataset: {
	        // 提供一份数据。
	        source: [
	            columns,
	            counts
	        ]
	    },
	    xAxis:{type: 'category'},
	    // 声明一个 Y 轴，数值轴。
	    yAxis: {type: 'value'},
	    grid:{
	    	top:'35%'
	    },
	    // 声明多个 bar 系列，默认情况下，每个系列会自动对应到 dataset 的每一列。
	    series: [
	        {type: 'bar',name:'经济金融'},
	        {type: 'bar',name:'企业管理'},
	        {type: 'bar',name:'法律法规'},
	        {type: 'bar',name:'社会民生'},
	        {type: 'bar',name:'健康生活'},
	        {type: 'bar',name:'体育运动'},
	        {type: 'bar',name:'文化艺术'},
	        {type: 'bar',name:'电子数码'},
	        {type: 'bar',name:'娱乐休闲'},
	        {type: 'bar',name:'地理地区'},
	        {type: 'bar',name:'心理分析'},
	        {type: 'bar',name:'医疗卫生'},
	        {type: 'bar',name:'科学教育'},
	        {type: 'bar',name:'电脑网络'},
	        {type: 'bar',name:'其他'}
	    ]
	};
	if (option && typeof option === "object") {
	    myChart2.setOption(option, true);
	}
}

/**
 * 加载不同种类话题信息统计
 */
function loadKindsOfTopicCount(){
	$.get(
		"../Controller/OperatorController.php",
		{action:"loadKindsOfTopicCount"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.topicTypeCount.forEach(function(value,index){
				var economyCount=value.economyCount;
				var businessCount=value.businessCount;
				var lawCount=value.lawCount;
				var societyCount=value.societyCount;
				var healthCount=value.healthCount;
				
				var sportsCount=value.sportsCount;
				var artCount=value.artCount;
				var electronCount=value.electronCount;
				var amusementCount=value.amusementCount;
				var areaCount=value.areaCount;
				
				var psychicCount=value.psychicCount;
				var medicalCount=value.medicalCount;
				var scienceCount=value.scienceCount;
				var computerCount=value.computerCount;
				var otherCount=value.otherCount;
				var counts=['话题数量',economyCount,businessCount,lawCount,societyCount,healthCount,
							sportsCount,artCount,electronCount,amusementCount,areaCount,
							psychicCount,medicalCount,scienceCount,computerCount,otherCount];
				var columns=['type','经济金融','企业管理','法律法规','社会民生','健康生活',
							'体育运动','文化艺术','电子数码','娱乐休闲','地理地区','心理分析',
							'医疗卫生','科学教育','电脑网络','其他'];
				showKindsOfTopicCount(counts,columns);
			});
		}
	);
}

/**
 * 加载不同种类话题信息统计
 */
function showKindsOfTopicCount(counts,columns){
	var dom = document.getElementById("kindsOfTopicCountContainer");
	myChart3 = echarts.init(dom,'light');
	var app = {};//暂未发现其途
	option = null;
	option = {
	    legend: {},
	    tooltip: {},
	    // 声明一个 X 轴，类目轴（category）。默认情况下，类目轴对应到 dataset 第一列。
	    dataset: {
	        // 提供一份数据。
	        source: [
	            columns,
	            counts
	        ]
	    },
	    xAxis:{type: 'category'},
	    // 声明一个 Y 轴，数值轴。
	    yAxis: {type: 'value'},
	    grid:{
	    	top:'35%'
	    },
	    // 声明多个 bar 系列，默认情况下，每个系列会自动对应到 dataset 的每一列。
	    series: [
	        {type: 'bar',name:'经济金融'},
	        {type: 'bar',name:'企业管理'},
	        {type: 'bar',name:'法律法规'},
	        {type: 'bar',name:'社会民生'},
	        {type: 'bar',name:'健康生活'},
	        {type: 'bar',name:'体育运动'},
	        {type: 'bar',name:'文化艺术'},
	        {type: 'bar',name:'电子数码'},
	        {type: 'bar',name:'娱乐休闲'},
	        {type: 'bar',name:'地理地区'},
	        {type: 'bar',name:'心理分析'},
	        {type: 'bar',name:'医疗卫生'},
	        {type: 'bar',name:'科学教育'},
	        {type: 'bar',name:'电脑网络'},
	        {type: 'bar',name:'其他'}
	    ]
	};
	if (option && typeof option === "object") {
	    myChart3.setOption(option, true);
	}
}