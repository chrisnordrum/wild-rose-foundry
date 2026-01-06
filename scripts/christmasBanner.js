// Create Christmas Banner in HTML
const christmasBanner = document.createElement("p");
christmasBanner.innerHTML - `Only a few days until Christmas! ❄️`;

const daysUntilChristmas = () => {
    // Declare Variables
    const today = Temporal.Now.plainDateISO();
    const christmas = new Temporal.PlainDate(today.year, 12, 25);

    // Calculate Days Until Christmas
    const christmasPast = today.until(christmas, { largestUnit: 'days' });

    // If Christmas already passed this year (Calculation provided negative days), then add 1 year
    if (christmasPast.days < 0) { 
        const christmasFuture = new Temporal.PlainDate(today.year + 1, christmas.month, christmas.day);
        var christmasCountdown = today.until(christmasFuture, {largestUnit: 'days'});
    } else {
        var christmasCountdown = christmasPast;
    }

    // Update # in HTML
    christmasBanner.innerHTML = `Only ${christmasCountdown.days} days until Christmas! ❄️`;
}

daysUntilChristmas(); // Call Function on Page Load

// Append Christmas Banner to beginning of Header
const header = document.querySelector("header");
header.insertBefore(christmasBanner, header.firstChild);

// Calculate Milliseconds Until Midnight
/* Using Javascript's Date Object was simpler because it already measures in milliseconds. */
const midnight = new Date();
midnight.setHours(24,0,0,0);
const millisUntilMidnight = midnight.getTime() - new Date().getTime();

setTimeout(daysUntilChristmas, millisUntilMidnight); // Call Function at Midnight