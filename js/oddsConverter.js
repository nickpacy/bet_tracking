function oddsConverter(item) {
	var us_odds = item.form.elements['us_odds'];
	var decimal_odds = item.form.elements['decimal_odds'];
	var fractional_odds = item.form.elements['fractional_odds'];
	var implied_probability = item.form.elements['implied_probability'];
	var bet = item.form.elements['bet'];
	var payout = item.form.elements['payout'];

	var d = 0;
	switch(item) {
		case us_odds: { var d = decimalFromUS(item.value); break; }
		case decimal_odds: { var d = parseFloat(item.value); break; }
		case fractional_odds: { var d = decimalFromFraction(item.value); break; }
		case implied_probability: { var d = decimalFromImpliedProbability(item.value); break; }
	}

	if(!isNaN(d) && d > 0) {
		us_odds.value = usOddsFromDecimal(d);
		decimal_odds.value = (Math.round(d*100)/100).toFixed(2);
		fractional_odds.value = fractionalFromDecimal(d);
		implied_probability.value = impliedProbabilityFromDecimal(d).toFixed(2)+'%';
	}
	bet.value = (Math.round(parseFloat(bet.value)*100)/100).toFixed(2);
	payout.value = (Math.round((d-1)*parseFloat(bet.value)*100)/100).toFixed(2);
}
function decimalFromFraction(fraction) {
	var a = fraction.split('/');
	if(a.length == 2 && !isNaN(a[0]) && !isNaN(a[1])) {
		return((a[0]/a[1])+1);
	}
	return(false);
}
function decimalFromImpliedProbability(ip) {
	return(100/parseFloat(ip));
}
function decimalFromUS(us) {
	if(us > 0) {
		return((us/100)+1);
	} else {
		return((100/us*-1)+1);
	}
}
function impliedProbabilityFromDecimal(decimal) {
	return(100/decimal);
}
function usOddsFromDecimal(decimal) {
	decimal-=1;
	if(decimal < 1) {
		return('-'+(100/decimal).toFixed(2));
	} else {
		return('+'+(decimal*100).toFixed(2));
	}
}
function fractionalFromDecimal(decimal) {
	decimal = parseFloat(decimal).toFixed(2);
	var num = (decimal-1) * 10000;
	var dom = 10000;

	num = Math.round(num);
	dom = Math.round(dom);

	var a = reduce(num,dom);
	num = a[0];
	dom = a[1];

	return(num+'/'+dom);
}
function reduce(a,b) {
	var n  = new Array(2);
	var f = GCD(a,b);
	n[0] = a/f;
	n[1] = b/f;
	return n;
}
function GCD(num1,num2) {
	var a; var b;
	if (num1 < num2) {a = num2; b = num1;}
	else if (num1 > num2) {a = num1; b = num2;}
	else if (num1 == num2) {return num1;}
  while(1) {
    if (b == 0) {
			return a;
		}
    else {
     	var temp = b;
     	b = a % b;
     	a = temp;
    }
  }
}