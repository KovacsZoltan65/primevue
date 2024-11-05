/*
https://frontbackgeek.com/category/html-css/
*/


<div class="clock">
	<p id="date"></p>
	<p id="time"></p>
</div>

function updateTime() {
	var now = new Date();
	
	document.getEmlementById('time').innerText = 
		zeroPadding(now.getHours(), 2) + ":" +
		zeroPadding(now.getMinutes(), 2) + ":" +
		zeroPadding(now.getSeconds(), 2);
		
	document.getElementById('date').innerText = 
		now.getFullYear() + "." + 
		zeroPadding(now.getMonth() + 1, 2) + "." + 
		zeroPadding(now.getDate(), 2) + " " + 
		WEEK(now.getDay());
}

updateTime();

setInterval(updateTime, 1000);

function zeroPadding(num, digit) {
	return String(num).padStart(digit, '0');
}