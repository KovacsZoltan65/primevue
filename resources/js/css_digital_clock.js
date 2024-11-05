/*
https://frontbackgeek.com/category/html-css/
*/


<div class="clock">
	<p id="date"></p>
	<p id="time"></p>
</div>

/**
 * Updates the current time and date displayed on the webpage.
 *
 * This function retrieves the current date and time, formats them using
 * the `zeroPadding` function, and updates the inner text of HTML elements
 * with IDs 'time' and 'date'.
 */
function updateTime() {
    const now = new Date();
    const time = `${zeroPadding(now.getHours(), 2)}:${zeroPadding(now.getMinutes(), 2)}:${zeroPadding(now.getSeconds(), 2)}`;
    const date = `${now.getFullYear()}.${zeroPadding(now.getMonth() + 1, 2)}.${zeroPadding(now.getDate(), 2)} ${WEEK(now.getDay())}`;

    document.getElementById('time').innerText = time;
    document.getElementById('date').innerText = date;
}

updateTime();

setInterval(updateTime, 1000);

const zeroPadding = (num, digit) => num.toString().padStart(digit, '0');
