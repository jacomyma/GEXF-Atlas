buildPieCharts = function(){
	$$(".piecharttable").each(function(table, index){
		var variables = {};
		variables.id = 'pie'+index;
		variables.div = new Element('div', {id:variables.id});
		variables.div.inject(table, 'before');
		variables.categories = [];
		table.getFirst().getChildren('tr').each(function(tr, trIndex){
			if(tr.getChildren('td').length>=3){
				var cat = {};
				var tds = tr.getChildren('td');
				cat.color = tds[0].textContent || tds[0].innerText;
				cat.value = tds[1].textContent|| tds[1].innerText;
				cat.link = tds[1].firstChild.href;
				cat.nodesCount = tds[2].textContent || tds[2].innerText;
				cat.isFirst = (trIndex==1);
				variables.categories.push(cat);
			}
		});
		
		tracePieChart(variables);
	});
}
tracePieChart = function(variables){
	// Geometric variables
	var	width = 920,
		height = 700,
		centerX = width/2,
		centerY = height/2,
		pieRadius = 150,
		startingAngle = 90,
		textXCenterDist = 280,
		textYCenterDist = 200,
		textYStepDecal = 30,
		lineCurveStartingRadius = pieRadius + 40,
		lineTextControlPointXDecal = 80,	
		fontSize = "9px";
		lineStepRadius = 8;
		curveElbowAngle = 5;

	var id = variables.id;
	var pieDiv = variables.div;
	
	var paper = Raphael(id, width, height);
	var c = paper.rect(0, 0, width, height, 5);
	c.attr({fill: "#FCFFF9", stroke:'none'});
	var rad = Math.PI / 180;
	function sector(cx, cy, r, startAngle, endAngle, params) {
		if(endAngle == startAngle+360){
			return paper.circle(cx, cy, r).attr(params);
		} else {
			var x1 = cx + r * Math.cos(-startAngle * rad),
				x2 = cx + r * Math.cos(-endAngle * rad),
				y1 = cy + r * Math.sin(-startAngle * rad),
				y2 = cy + r * Math.sin(-endAngle * rad);
			return paper.path(["M", cx, cy, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "z"]).attr(params);
		}
	}
	function referingLine(bascule, meanAngle, currentTextX, currentTextY, currentLineRadius, params){
		while(meanAngle<0){meanAngle += 360;}
		while(meanAngle>180){meanAngle -= 360;}
		var textAngle = Math.atan2(currentTextY-centerY, currentTextX - centerX)/rad;
		while(textAngle<0){textAngle += 360;}
		while(textAngle>180){textAngle -= 360;}
		var differenceAngle = -meanAngle - textAngle;
		while(differenceAngle<0){differenceAngle += 360;}
		while(differenceAngle>180){differenceAngle -= 360;}
		var clockwise = ((-textAngle>0 && meanAngle>0 && -textAngle>meanAngle) || (-textAngle>0 && meanAngle<0 && -textAngle+meanAngle>360+meanAngle+textAngle) || (-textAngle<0 && meanAngle>0 && meanAngle+textAngle>360-textAngle-meanAngle) || (-textAngle<0 && meanAngle<0 && -textAngle>meanAngle))?(-1):(1);
		if(Math.abs(differenceAngle)<4*curveElbowAngle){
			return paper.path([
				'M',
					centerX + (pieRadius-4) * Math.cos(-(meanAngle)*rad),
					centerY + (pieRadius-4) * Math.sin(-(meanAngle)*rad),
				'C',
					centerX + (pieRadius + currentLineRadius) * Math.cos(-meanAngle*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin(-meanAngle*rad),
					centerX + (pieRadius + currentLineRadius) * Math.cos(textAngle*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin(textAngle*rad),
					centerX + (pieRadius + currentLineRadius + 30) * Math.cos(textAngle*rad),
					centerY + (pieRadius + currentLineRadius + 30) * Math.sin(textAngle*rad),
				'L',
					(currentTextX<centerX)?(currentTextX + 2):(currentTextX - 2),
					currentTextY
			]).attr(params);
		} else {
			return paper.path([
				'M',
					centerX + (pieRadius-4) * Math.cos(-(meanAngle)*rad),
					centerY + (pieRadius-4) * Math.sin(-(meanAngle)*rad),
				'C',
					centerX + (pieRadius + currentLineRadius) * Math.cos(-meanAngle*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin(-meanAngle*rad),
					centerX + (pieRadius + currentLineRadius) * Math.cos((-meanAngle + clockwise * 1 * curveElbowAngle)*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin((-meanAngle + clockwise * 1 * curveElbowAngle)*rad),
					centerX + (pieRadius + currentLineRadius) * Math.cos((-meanAngle + clockwise * 2 * curveElbowAngle)*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin((-meanAngle + clockwise * 2 * curveElbowAngle)*rad),
				'A',
					(pieRadius + currentLineRadius),
					(pieRadius + currentLineRadius),
					0,
					0,
					((bascule && differenceAngle<0) || (!bascule && differenceAngle<0))?(1):(0),
					centerX + (pieRadius + currentLineRadius) * Math.cos((textAngle - clockwise * 2 * curveElbowAngle)*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin((textAngle - clockwise * 2 * curveElbowAngle)*rad),
				'C',
					centerX + (pieRadius + currentLineRadius) * Math.cos((textAngle - clockwise * curveElbowAngle)*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin((textAngle - clockwise * curveElbowAngle)*rad),
					centerX + (pieRadius + currentLineRadius) * Math.cos(textAngle*rad),
					centerY + (pieRadius + currentLineRadius) * Math.sin(textAngle*rad),
					centerX + (pieRadius + currentLineRadius + 30) * Math.cos(textAngle*rad),
					centerY + (pieRadius + currentLineRadius + 30) * Math.sin(textAngle*rad),
				'L',
					(currentTextX<centerX)?(currentTextX + 2):(currentTextX - 2),
					currentTextY
			]).attr(params);
		}
	}
	// get the total
	var nodesTotal = 0;
	variables.categories.each(function(cat){
		nodesTotal += cat.nodesCount.toInt();
	});
	
	// Simulation for radius line spacing
	var currentAngle = startingAngle;
	var currentTextY = centerY - textYCenterDist;
	var currentTextX = centerX - textXCenterDist;
	var bascule = false;
	var lineRadiuses = [1, 1];
	variables.categories.each(function(cat){
		var value = cat.value;
		var nodesCount = cat.nodesCount;
		endAngle = currentAngle + 360 * nodesCount.toInt() / nodesTotal;
		meanAngle = (currentAngle + endAngle) * 0.5;
		while(meanAngle>360+90){meanAngle -= 360;}
		while(meanAngle<=90){meanAngle += 360;}
		var textAngle = -Math.atan2(currentTextY-centerY, currentTextX - centerX)/rad;
		while(textAngle>360+90){textAngle -= 360;}
		while(textAngle<=90){textAngle += 360;}
		if(meanAngle>=270 && !bascule){
			// Here we just pass the first half
			currentTextY = centerY + textYCenterDist;
			currentTextX = centerX + textXCenterDist;
			bascule = true;
		} else {
			if(!bascule){
				// We're still in the first half
				if(meanAngle<textAngle){
					lineRadiuses[0]++;
				}
			} else {
				// We're in the second half
				if(meanAngle<textAngle){
					lineRadiuses[1]++;
				}
			}
		}
		
		if(!bascule){
			currentTextY += textYStepDecal;
		} else {
			currentTextY -= textYStepDecal;
		}
		currentAngle = endAngle;
	});
	
	// Compute and draw sectors
	currentAngle = startingAngle;
	currentTextY = centerY - textYCenterDist;
	currentTextX = centerX - textXCenterDist;
	bascule = false;
	var currentLineRadius = lineRadiuses[0]*lineStepRadius;
	variables.categories.each(function(cat){
		var color = cat.color;
		var value = cat.value;
		var nodesCount = cat.nodesCount;
		endAngle = currentAngle + 360 * nodesCount.toInt() / nodesTotal;
		meanAngle = (currentAngle + endAngle) * 0.5;
		while(meanAngle>360+90){meanAngle -= 360;}
		while(meanAngle<=90){meanAngle += 360;}
		var textAngle = -Math.atan2(currentTextY-centerY, currentTextX - centerX)/rad;
		while(textAngle>360+90){textAngle -= 360;}
		while(textAngle<=90){textAngle += 360;}
		if(meanAngle>=270 && !bascule){
			currentTextY = centerY + textYCenterDist;
			currentTextX = centerX + textXCenterDist;
			bascule = true;
			currentLineRadius = lineRadiuses[1]*lineStepRadius;
		} else {
			if(meanAngle<textAngle){
				currentLineRadius -= lineStepRadius;
			} else {
				currentLineRadius += lineStepRadius;
			}
		}
		textOrientation = (bascule)?("start"):("end");
		
		paper.path([
			'M',
				(currentTextX<centerX)?(currentTextX + 3):(currentTextX - 3),
				currentTextY + 10,
			'L',
				(currentTextX<centerX)?(currentTextX + 3):(currentTextX - 3),
				currentTextY - 10
		]).attr({stroke: "#666", "stroke-width":"2px"});
		var underline = referingLine(bascule, meanAngle, currentTextX, currentTextY, currentLineRadius, {stroke: "#666", "stroke-width":"4px"});
		var secteur = sector(centerX, centerY, pieRadius, currentAngle, endAngle, {fill: '#'+color, stroke: "#666"});
		var upperline = referingLine(bascule, meanAngle, currentTextX, currentTextY, currentLineRadius, {stroke: "#"+color, "stroke-width":"2px"});
		secteur.toFront();
		upperline.toFront();
		paper.path([
			'M',
				(currentTextX<centerX)?(currentTextX + 2):(currentTextX - 2),
				currentTextY + 10,
			'L',
				(currentTextX<centerX)?(currentTextX + 2):(currentTextX - 2),
				currentTextY - 10
		]).attr({stroke: "#"+color, "stroke-width":"2px"});
		
		var link = paper.text(
			currentTextX,
			currentTextY,
			value+"\n"+nodesCount+" nodes or "+(Math.round(100*nodesCount/nodesTotal))+"%"
		).attr({
			"text-anchor":textOrientation,
			"fill":"#333333",
			"font-size":fontSize,
			"font-weight":"bold",
			"font-family":"Tahoma, Verdana"
		});
		link[0].onmouseover = function () {
			this.setStyle('cursor','pointer');
		}
		link[0].onclick = function () {
			window.location.href=cat.link;
		}
		//alert("meanAngle : "+Math.round(meanAngle)+"\ntextAngle : "+Math.round(textAngle));
		if(!bascule){
			currentTextY += textYStepDecal;
		} else {
			currentTextY -= textYStepDecal;
		}
		currentAngle = endAngle;
	});
	
	// Trace SVG Viewer
	var form = new Element('form', {action: 'viewsvg.php', method: 'post', style:'text-align:right;'});
	form.inject(id, 'after');
	var hidden = new Element('input', {type: 'hidden', name:'svg', value: $(id).innerHTML});
	hidden.inject(form);
	var input = new Element('input', {type: 'submit', value: 'SVG', style: 'height:20px; font-size:8px;'});
	input.inject(form);

}
traceHalfPieChart = function(variables){
	// Geometric variables
	var	width = 920,
		height = 120,
		centerX = width/2,
		centerY = 110,
		pieRadius = 100,
		startingAngle = 0,
		fontSize = "12px",
		needleWidth = 5;
		
	var id = variables.id;
	var pieDiv = variables.div;
	
	var paper = Raphael(id, width, height);
	var c = paper.rect(0, 0, width, height, 5);
	c.attr({fill: "#FCFFF9", stroke:'none'});
	var rad = Math.PI / 180;
	function sector(cx, cy, r, startAngle, endAngle, params) {
		var x1 = cx + r * Math.cos(-startAngle * rad),
			x2 = cx + r * Math.cos(-endAngle * rad),
			y1 = cy + r * Math.sin(-startAngle * rad),
			y2 = cy + r * Math.sin(-endAngle * rad);
		return paper.path(["M", cx, cy, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "z"]).attr(params);
	}
	function needle(centerX, centerY, pieRadius, currentAngle){
		//paper.circle(centerX, centerY, needleWidth);
		paper.path([
			'M',
				centerX,
				centerY + needleWidth,
			'L',
				centerX + pieRadius,
				centerY,
			'L',
				centerX,
				centerY - needleWidth,
			'A',
				needleWidth,
				needleWidth,
				0,
				0,
				0,
				centerX,
				centerY + needleWidth,
		]).attr({fill:"#FFF", "stroke-width":"2px", stroke:"#666"}).rotate(-currentAngle, centerX, centerY);
	}
	// get the total
	var nodesTotal = 0;
	variables.categories.each(function(cat){
		nodesTotal += cat.nodesCount.toInt();
	});
	var currentAngle = startingAngle;
	variables.categories.each(function(cat){
		var color = cat.color;
		var value = cat.value;
		var nodesCount = cat.nodesCount;
		endAngle = currentAngle + 180 * nodesCount.toInt() / nodesTotal;
		meanAngle = (currentAngle + endAngle) * 0.5;
		
		var secteur = sector(centerX, centerY, pieRadius, currentAngle, endAngle, {fill: '#'+color, stroke: "#666"});
		
		if(cat.isFirst){
			needleAngle = endAngle;
		}
		
		if(!cat.isFirst){
			textOrientation = "end";
			textX = centerX - pieRadius - 10;
			textY = centerY - 12;
		} else {
			textOrientation = "start";
			textX = centerX + pieRadius + 10;
			textY = centerY - 12;
		}
		var link = paper.text(
			textX,
			textY,
			value+"\n"+nodesCount+" nodes or "+(Math.round(100*nodesCount.toInt()/nodesTotal))+"%"
		).attr({
			"text-anchor":textOrientation,
			"fill":"#333333",
			"font-size":fontSize,
			"font-weight":"bold",
			"font-family":"Tahoma, Verdana"
		});
		link[0].onmouseover = function () {
			this.setStyle('cursor','pointer');
		}
		link[0].onclick = function () {
			window.location.href=cat.link;
		}
		
		currentAngle = endAngle;
	});
	needle(centerX, centerY, pieRadius, needleAngle);
	
	// Trace SVG Viewer
	var form = new Element('form', {action: 'viewsvg.php', method: 'post', style:'text-align:right;'});
	form.inject(id, 'after');
	var hidden = new Element('input', {type: 'hidden', name:'svg', value: $(id).innerHTML});
	hidden.inject(form);
	var input = new Element('input', {type: 'submit', value: 'SVG', style: 'height:20px; font-size:8px;'});
	input.inject(form);

}
buildHalfPieCharts = function(){
	$$(".halfpiecharttable").each(function(table, index){
		var variables = {};
		variables.id = 'halfpie'+index;
		variables.div = new Element('div', {id:variables.id});
		variables.div.inject(table, 'before');
		variables.categories = [];
		table.getFirst().getChildren('tr').each(function(tr, trIndex){
			if(tr.getChildren('td').length>=3){
				var cat = {};
				var tds = tr.getChildren('td');
				cat.color = tds[0].textContent || tds[0].innerText;
				cat.value = tds[1].textContent|| tds[1].innerText;
				cat.link = tds[1].firstChild.href;
				cat.nodesCount = tds[2].textContent || tds[2].innerText;
				cat.isFirst = (trIndex==1);
				variables.categories.push(cat);
			}
		});
		
		traceHalfPieChart(variables);
	});
}
buildDistributionCharts = function(){
	$$(".scoretable").each(function(table, index){
		// Geometric variables
		var	width = 920,
			height = 200,
			marginX = 10,
			marginY = 10,
			fontSize = "9px";
			
		var nodeValues = [];
		var maxValue = 0;
		table.getFirst().getChildren('tr').each(function(tr, trIndex){
			if(tr.getChildren('td').length>=4){
				var tds = tr.getChildren('td');
				var color = tds[0].textContent || tds[0].innerText;
				var value = tds[1].textContent || tds[1].innerText;
				var nodeId = tds[2].textContent || tds[2].innerText;
				var nodeLabel = tds[3].textContent || tds[3].innerText;
				
				value = value.toFloat();
				
				maxValue = Math.max(value, maxValue);
				
				nodeObject = {color:color, val:value, nodeId:nodeId, nodeLabel:nodeLabel};
				nodeValues.push(nodeObject);
			}
		});
		function sortNodeObject(a,b){
			return b.val - a.val;
		}
		nodeValues = nodeValues.sort(sortNodeObject);
		
		var id = 'scoreDistrib'+index;
		var pieDiv  = new Element('div', {id:id});
		pieDiv.inject(table, 'before');
		table.setStyle('display', 'none');
		
		var paper = Raphael(id, width, height);
		var c = paper.rect(0, 0, width, height, 5);
		c.attr({fill: "#FCFFF9", stroke:'none'});
		nodeValues.each(function(nodeObject, i){
			var color = nodeObject.color;
			var value = nodeObject.val;
			var nodeId = nodeObject.nodeId;
			var nodeLabel = nodeObject.nodeLabel;
			
			var rectWidth = (width-2*marginX)/nodeValues.length;
			var rectHeight = (height-2*marginY) * value / maxValue;
			var rectX = marginX + i*rectWidth;
			var rectY = height - marginY - rectHeight;
			
			paper.rect(rectX, marginY, rectWidth, height-2*marginY).attr({fill:"#DDD", stroke:"#FFF"});
			if(rectHeight>0){
				paper.rect(rectX, rectY, rectWidth, rectHeight).attr({fill:"#"+color, stroke:"#666"});
			}
		});
	});
}
buildTowerCharts = function(){
	if(towerCharts){
		towerCharts.each(function(variable){
			traceTowerChart(variable);
		});
	}
}
traceTowerChart = function(variables){	
	// Geometric variables
	var	width = 920,
		height = 650,
		baselineY = height - 150,
		x_edgeTextLength = 200,
		x_arrowPointLength = 20,
		x_arrowNeck = 80,
		x_arrowShoulder = 60,
		x_arrow_curve = 40,
		x_arrowBody = 30,
		x_arrowToTower = 5,
		x_towerWidth = 120,
		x_textArrowSpacing = 5,
		y_towerSelflinkSpacing = 20,
		y_arrowSpacing = 10,
		y_arrowBodyWidth = 10,
		y_edgeMultiplicator = 0.5,
		y_siteMultiplicator = (baselineY - 40) / variables.maxNodesCount,
		y_edgeLabelOffset = 2,
		y_title_offset = -25,
		y_maskHeight = 200,
		round_corner = 10,
		internalLinks_innerRound = 20,
		x_title = width * 0.5,
		x_reference = width * 0.5 - (x_edgeTextLength + x_arrowNeck + x_arrowShoulder + x_arrowBody + x_arrowToTower + 0.5 * x_towerWidth),
		y_reference = baselineY;

	var paper = Raphael(variables.id, width, height);
	var c = paper.rect(0, 0, width, height, 5);
	c.attr({fill: "#FCFFF9", stroke:'none'});
	
	// Internal Links
	var x0 = x_reference + x_edgeTextLength + x_arrowNeck + x_arrowShoulder + x_arrowBody - x_arrowPointLength,
		y0 = y_reference - y_arrowBodyWidth,
		x2 = x0,
		y2 = y_reference,
		x1 = x2 + x_arrowPointLength,
		y1 = 0.5 * (y0 + y2),
		x3 = x_reference + x_edgeTextLength + x_arrowNeck + x_arrowShoulder,
		y3 = y2,
		x4 = x3,
		y4 = y3 + 2 * internalLinks_innerRound,
		x5 = x_reference + x_edgeTextLength + x_arrowNeck + x_arrowShoulder + x_arrowBody + x_arrowToTower + x_towerWidth + x_arrowToTower + x_arrowBody,
		y5 = y4,
		x6 = x5,
		y6 = y3,
		x7 = x5 - x_arrowBody,
		y7 = y6,
		x9 = x7,
		y9 = y0,
		x8 = x7 + x_arrowPointLength,
		y8 = 0.5 * (y7 + y9),
		x10 = x6,
		y10 = y9,
		y_internalLinksWidth = variables.category.internalLinks * y_edgeMultiplicator,
		internalLinks_outerRound = 0.5 * (y_arrowBodyWidth + 2 * internalLinks_innerRound + y_internalLinksWidth),
		x11 = x10,
		y11 = y10 + 2 * internalLinks_outerRound,
		x12 = x3,
		y12 = y11,
		x13 = x12,
		y13 = y0,
		xc0 = x_reference,
		yc0 = y0,
		xc1 = x_reference + 2 * x_edgeTextLength + 2 * x_arrowNeck + 2 * x_arrowShoulder + 2 * x_arrowBody + 2 + x_arrowToTower + x_towerWidth,
		yc1 = yc0,
		xc2 = xc1,
		yc2 = yc1 + y_maskHeight,
		xc3 = xc0,
		yc3 = yc2;
		
	paper.path([
		'M',
			x0, y0,
		'L',
			x1, y1,
		'L',
			x2, y2,
		'L',
			x3, y3,
		'A',
			internalLinks_innerRound, internalLinks_innerRound,
			180,
			0,
			0,
			x4, y4,
		'L',
			x5, y5,
		'A',
			internalLinks_innerRound, internalLinks_innerRound,
			180,
			0,
			0,
			x6, y6,
		'L',
			x7, y7,
		'L',
			x8, y8,
		'L',
			x9, y9,
		'L',
			x10, y10,
		'A',
			internalLinks_innerRound, internalLinks_innerRound,
			180,
			0,
			1,
			x11, y11,
		'L',
			x12, y12,
		'A',
			internalLinks_innerRound, internalLinks_innerRound,
			180,
			0,
			1,
			x13, y13,
		'L',
			x0, y0
	]).attr({fill:"#"+variables.category.color, "stroke-width":"1px", stroke:"#ffffff"});
		
	if(variables.category.internalLinks>0){
		var textColor = "333";
	} else {
		var textColor = "aaa";
	}
	var text = variables.category.internalLinks + " links from " + variables.category.name + " to " + variables.category.name;
	paper.text(
		x_reference + x_edgeTextLength + x_arrowNeck + x_arrowShoulder + x_arrowBody + x_arrowToTower + 0.5 * x_towerWidth,
		y_reference + 2 * internalLinks_innerRound - y_edgeLabelOffset,
		text
	).attr({
		"text-anchor":"middle",
		"fill":"#fff",
		"stroke-width":"2px",
		"stroke":"#fff",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x_reference + x_edgeTextLength + x_arrowNeck + x_arrowShoulder + x_arrowBody + x_arrowToTower + 0.5 * x_towerWidth,
		y_reference + 2 * internalLinks_innerRound - y_edgeLabelOffset,
		text
	).attr({
		"text-anchor":"middle",
		"fill":"#"+textColor,
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	
	// Tower
	x0 = x_reference + x_edgeTextLength + x_arrowNeck + x_arrowShoulder + x_arrowBody + x_arrowToTower;
	y0 = y_reference;
	x1 = x0;
	y1 = y0 - y_siteMultiplicator * variables.category.nodesCount + round_corner;
	x2 = x1 + round_corner;
	y2 = y0 - y_siteMultiplicator * variables.category.nodesCount;
	x3 = x0 + x_towerWidth - round_corner;
	y3 = y2;
	x4 = x0 + x_towerWidth;
	y4 = y1;
	x5 = x4;
	y5 = y0;
	paper.path([
		'M',
			x0, y0,
		'L',
			x1, y1,
		'A',
			round_corner, round_corner,
			90,
			0,
			1,
			x2, y2,
		'L',
			x3, y3,
		'A',
			round_corner, round_corner,
			90,
			0,
			1,
			x4, y4,
		'L',
			x5, y5,
		'L',
			x0, y0
	]).attr({fill:"#"+variables.category.color, "stroke-width":"1px", stroke:"#ffffff"});
	y_title = y2;
	
	var links_count_from_thisCountry = 0;	// or: variables.category.internalLinks;
	var links_count_to_thisCountry = 0;		// or: variables.category.internalLinks;
	variables.categories.each(function(cat){
		links_count_from_thisCountry += cat.linksFromMain;
		links_count_to_thisCountry += cat.linksToMain;
	});
	
	// Links from current category to another cat	
	y_edge_body_elevation = y_reference - y_arrowBodyWidth;
	y_edge_elevation = y_edge_body_elevation;
	variables.categories.each(function(cat){
		// Draw the edge
		x0 = x_reference + x_edgeTextLength + x_arrowNeck + x_arrowShoulder + x_arrowBody + x_arrowToTower + x_towerWidth + x_arrowToTower;			// Bottom left of the edge
		y0 = y_edge_body_elevation;
		x1 = x0;										// Top left
		y1 = y0 - y_arrowBodyWidth;
		var x0b = x0 + x_arrowPointLength;
		var y0b = 0.5 * (y0 + y1);
		x2 = x1 + x_arrowBody;							// Top : body/shoulder
		y2 = y1;
		x3 = x2 + x_arrow_curve;						// Shoulder's top Control Point on body/shoulder
		y3 = y2;
		x5 = x2 + x_arrowShoulder;						// Top : shoulder/neck
		y5 = y_edge_elevation - Math.round(y_edgeMultiplicator * cat.linksFromMain);
		x4 = x5 - x_arrow_curve;						// Shoulder's top Control Point on shoulder/body
		y4 = y5;
		x6 = x5 + x_arrowNeck - x_arrowPointLength;		// Top right of the edge
		y6 = y5;
		x7 = x6 + x_arrowPointLength;					// Arrow's point, on the right
		y7 = y6 + 0.5 * Math.round(y_edgeMultiplicator * cat.linksFromMain);
		x8 = x6;										// Bottom right of the edge
		y8 = y_edge_elevation;
		x9 = x5;										// Bottom : shoulder/neck
		y9 = y8;
		x10 = x4;										// Shoulder's bottom Control Point on shoulder/neck
		y10 = y9;
		x11 = x3;										// Shoulder's bottom Control Point on body/shoulder
		y11 = y0;
		x12 = x2;										// Bottom : body/shoulder
		y12 = y0;
		paper.path([
			'M',
				x0, y0,
			'L',
				x0b, y0b,
			'L',
				x1, y1,
			'L',
				x2, y2,
			'C',
				x3, y3,
				x4, y4,
				x5, y5,
			'L',
				x6, y6,
			'L',
				x7, y7,
			'L',
				x8, y8,
			'L',
				x9, y9,
			'C',
				x10, y10,
				x11, y11,
				x12, y12,
			'L',
				x0, y0
			
		]).attr({fill:"#"+cat.color, "stroke-width":"1px", stroke:"#ffffff"});
		if(cat.linksFromMain>0){
			var textColor = "333";
		} else {
			var textColor = "aaa";
		}
		var text = "..." + cat.linksFromMain + " links (" + Math.round(100 * cat.linksFromMain / links_count_from_thisCountry) + "%) to " + cat.name;
		paper.text(
			x7 + x_textArrowSpacing,
			y7 + y_edgeLabelOffset,
			text
		).attr({
			"text-anchor":"start",
			"fill":"#fff",
			"stroke-width":"2px",
			"stroke":"#fff",
			"font-size":9,
			"font-weight":"bold",
			"font-family":"Tahoma, Verdana"
		});
		var link = paper.text(
			x7 + x_textArrowSpacing,
			y7 + y_edgeLabelOffset,
			text
		).attr({
			"text-anchor":"start",
			"fill":"#"+textColor,
			"font-size":9,
			"font-weight":"bold",
			"font-family":"Tahoma, Verdana"
		});
		link[0].onmouseover = function () {
			this.setStyle('cursor','pointer');
		}
		link[0].onclick = function () {
			window.location.href=cat.link;
		}
		
		y_edge_body_elevation -= y_arrowBodyWidth;
		y_edge_elevation -= Math.round(y_edgeMultiplicator * cat.linksFromMain) + y_arrowSpacing;
	});
	y_title = Math.min(y_title, y_edge_elevation);
	// White bordered text
	paper.text(
		x0,
		y_edge_body_elevation - 3 * y_arrowSpacing,
		links_count_from_thisCountry + " links from"
	).attr({
		"text-anchor":"start",
		"fill":"#fff",
		"stroke-width":"2px",
		"stroke":"#fff",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x0,
		y_edge_body_elevation - 2 * y_arrowSpacing,
		variables.category.name + " to"
	).attr({
		"text-anchor":"start",
		"fill":"#fff",
		"stroke-width":"2px",
		"stroke":"#fff",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x0,
		y_edge_body_elevation - 1 * y_arrowSpacing,
		"other categories"
	).attr({
		"text-anchor":"start",
		"fill":"#fff",
		"stroke-width":"2px",
		"stroke":"#fff",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	// text
	paper.text(
		x0,
		y_edge_body_elevation - 3 * y_arrowSpacing,
		links_count_from_thisCountry + " links from"
	).attr({
		"text-anchor":"start",
		"fill":"#"+textColor,
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x0,
		y_edge_body_elevation - 2 * y_arrowSpacing,
		variables.category.name + " to"
	).attr({
		"text-anchor":"start",
		"fill":"#"+textColor,
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x0,
		y_edge_body_elevation - 1 * y_arrowSpacing,
		"other categories"
	).attr({
		"text-anchor":"start",
		"fill":"#"+textColor,
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	
	// Links from another cat to current category
	y_edge_body_elevation = y_reference - y_arrowBodyWidth;
	y_edge_elevation = y_edge_body_elevation;
	variables.categories.each(function(cat){
		// Draw the edge
		x0 = x_reference + x_edgeTextLength;			// Bottom left of the edge
		y0 = y_edge_elevation;
		x1 = x0;										// Top left
		y1 = y0 - Math.round(y_edgeMultiplicator * cat.linksToMain);
		x0b = x0 + x_arrowPointLength;
		y0b = 0.5 * (y0 + y1);
		x2 = x1 + x_arrowNeck;							// Top : neck/shoulder
		y2 = y1;
		x3 = x2 + x_arrow_curve;						// Shoulder's top Control Point on neck/shoulder
		y3 = y2;
		x5 = x2 + x_arrowShoulder;						// Top : shoulder/body
		y5 = y_edge_body_elevation - y_arrowBodyWidth;
		x4 = x5 - x_arrow_curve;						// Shoulder's top Control Point on shoulder/body
		y4 = y5;
		x6 = x5 + x_arrowBody - x_arrowPointLength;		// Top right of the edge
		y6 = y5;
		x7 = x6 + x_arrowPointLength;					// Arrow's point, on the right
		y7 = y6 + 0.5 * y_arrowBodyWidth;
		x8 = x6;										// Bottom right of the edge
		y8 = y6 + y_arrowBodyWidth;
		x9 = x5;										// Bottom : shoulder/body
		y9 = y8;
		x10 = x4;										// Shoulder's bottom Control Point on shoulder/body
		y10 = y9;
		x11 = x3;										// Shoulder's bottom Control Point on neck/shoulder
		y11 = y0;
		x12 = x2;										// Bottom : neck/shoulder
		y12 = y0;
		paper.path([
			'M',
				x0, y0,
			'L',
				x0b, y0b,
			'L',
				x1, y1,
			'L',
				x2, y2,
			'C',
				x3, y3,
				x4, y4,
				x5, y5,
			'L',
				x6, y6,
			'L',
				x7, y7,
			'L',
				x8, y8,
			'L',
				x9, y9,
			'C',
				x10, y10,
				x11, y11,
				x12, y12,
			'L',
				x0, y0
		]).attr({fill:"#"+cat.color, "stroke-width":"1px", stroke:"#ffffff"});
		if(cat.linksToMain > 0){
			textColor = "333";
		} else {
			textColor = "aaa";
		}
		var text = cat.name + " : " + cat.linksToMain + " links (" + Math.round(100 * cat.linksToMain / links_count_to_thisCountry) + "%) to ...";
		paper.text(
			x0 - x_textArrowSpacing,
			y0b + y_edgeLabelOffset,
			text
		).attr({
			"text-anchor":"end",
			"fill":"#fff",
			"stroke-width":"2px",
			"stroke":"#fff",
			"font-size":9,
			"font-weight":"bold",
			"font-family":"Tahoma, Verdana"
		});
		link = paper.text(
			x0 - x_textArrowSpacing,
			y0b + y_edgeLabelOffset,
			text
		).attr({
			"text-anchor":"end",
			"fill":"#"+textColor,
			"font-size":9,
			"font-weight":"bold",
			"font-family":"Tahoma, Verdana"
		});
		link[0].onmouseover = function () {
			this.setStyle('cursor','pointer');
		}
		link[0].onclick = function () {
			window.location.href=cat.link;
		}

		
		y_edge_body_elevation -= y_arrowBodyWidth;
		y_edge_elevation -= Math.round(y_edgeMultiplicator * cat.linksToMain) + y_arrowSpacing;
	});
	
	y_title = Math.min(y_title, y_edge_elevation - 30);
	paper.text(
		x7,
		y_edge_body_elevation - 3 * y_arrowSpacing,
		links_count_to_thisCountry + " links to"
	).attr({
		"text-anchor":"end",
		"fill":"#fff",
		"stroke-width":"2px",
		"stroke":"#fff",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x7,
		y_edge_body_elevation - 2 * y_arrowSpacing,
		variables.category.name + " from"
	).attr({
		"text-anchor":"end",
		"fill":"#fff",
		"stroke-width":"2px",
		"stroke":"#fff",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x7,
		y_edge_body_elevation - 1 * y_arrowSpacing,
		"other categories"
	).attr({
		"text-anchor":"end",
		"fill":"#fff",
		"stroke-width":"2px",
		"stroke":"#fff",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	// text
	paper.text(
		x7,
		y_edge_body_elevation - 3 * y_arrowSpacing,
		links_count_to_thisCountry + " links to"
	).attr({
		"text-anchor":"end",
		"fill":"#"+textColor,
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x7,
		y_edge_body_elevation - 2 * y_arrowSpacing,
		variables.category.name + " from"
	).attr({
		"text-anchor":"end",
		"fill":"#"+textColor,
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	paper.text(
		x7,
		y_edge_body_elevation - 1 * y_arrowSpacing,
		"other categories"
	).attr({
		"text-anchor":"end",
		"fill":"#"+textColor,
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});

	paper.text(
		x_title,
		y_title + y_title_offset,
		variables.category.name
	).attr({
		"text-anchor":"middle",
		"fill":"#333",
		"font-size":12,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
		paper.text(
		x_title,
		y_title + y_title_offset + 15,
		"(" + variables.category.nodesCount + " nodes)"
	).attr({
		"text-anchor":"middle",
		"fill":"#333",
		"font-size":9,
		"font-weight":"bold",
		"font-family":"Tahoma, Verdana"
	});
	
	// Trace SVG Viewer
	var form = new Element('form', {action: 'viewsvg.php', method: 'post', style:'text-align:right;'});
	form.inject(variables.id, 'after');
	var hidden = new Element('input', {type: 'hidden', name:'svg', value: $(variables.id).innerHTML});
	hidden.inject(form);
	var input = new Element('input', {type: 'submit', value: 'SVG', style: 'height:20px; font-size:8px;'});
	input.inject(form);
}
function initStatsViz(){
	try{
		SVGAnimatedString.prototype.contains = function(value) {
			return false;
		};
	} catch(e){}
	buildPieCharts();
	buildHalfPieCharts();
	buildDistributionCharts();
	buildTowerCharts();
}