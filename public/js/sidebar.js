jQuery(document).ready(function(){
    /* Navbar clock */
    var currentDate = jQuery('#date'),
        currentTime = jQuery('#time');
    var months = ['January', 'February', 'March', 'April', 'May', 'June', 
        'July', 'August', 'September', 'October', 'November', 'December'];

    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    function updateDate(){
        var date = new Date();
    
        var ampm = date.getHours() < 12
            ? 'AM'
            : 'PM';
    
        var hours = date.getHours() == 0
            ? 12
            : date.getHours() > 12
            ? date.getHours() - 12
            : date.getHours();
    
        var minutes = date.getMinutes() < 10 
            ? '0' + date.getMinutes() 
            : date.getMinutes();
    
        var dayOfWeek = days[date.getDay()];
        var month = months[date.getMonth()];
        var day = date.getDate();
        var year = date.getFullYear();
    
        var dateString = dayOfWeek + ', ' + month + ' ' + day + ', ' + year;
        var timeString = hours + ':' + minutes + ' ' + ampm;
        
        currentDate.text(dateString);
        currentTime.text(timeString);
    } 

    updateDate();
    window.setInterval(update, 1000);
    
    /* Sidebar highlight */
    var pathname = window.location.pathname; 
    
    if(pathname == '/backpacker' || pathname == '/calendar-glamping' || pathname == '/calendar-backpacker'
        || pathname == '/reload-calendar-glamping' || pathname == '/reload-calendar-backpacker') {
        jQuery('#lodgingDashboard').addClass('active');
    } else if(pathname == '/todays-lodging-report' || pathname == '/this-weeks-lodging-report' || pathname == '/this-months-lodging-report'
        || pathname == '/reload-daily-lodging-report' || pathname == '/reload-weekly-lodging-report' || pathname == '/reload-monthly-lodging-report'
        || pathname == '/custom-lodging-report' || pathname == '/reload-custom-lodging-report') {
        jQuery('#lodgingReports').addClass('active');
    } else if(pathname == '/todays-restaurant-report' || pathname == '/this-weeks-restaurant-report' || pathname == '/this-months-restaurant-report'
        || pathname == '/reload-daily-restaurant-report' || pathname == '/reload-weekly-restaurant-report' || pathname == '/reload-monthly-restaurant-report'
        || pathname == '/custom-restaurant-report' || pathname == '/reload-custom-restaurant-report') {
        jQuery('#restoReportsTab').addClass('activeTab');
        jQuery('#dropdownRestoReports').css('display', 'block');
        jQuery('.nav-list > li > .dropdown-container > #restaurantReports > span').css('color', 'white');
        jQuery('.nav-list > li > .dropdown-container > #restaurantReports > i').css('color', 'white');
    } else if(pathname == '/view-payments' || pathname == '/view-charges' || pathname == '/side-by-side/charges-payments') {
        jQuery('#transactionsTab').addClass('activeTab');
        jQuery('#dropdownTransactions').css('display', 'block');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > span').css('color', 'white');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > i').css('color', 'white');
    } else if(pathname == '/view-units' || pathname == '/view-services') {
        jQuery('#lodgingTab').addClass('activeTab');
        jQuery('#dropdownLodging').css('display', 'block');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > span').css('color', 'white');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > i').css('color', 'white');
    } else if(pathname == '/view-menu-recipe' || pathname == '/view-inventory') {
        jQuery('#barRestoTab').addClass('activeTab');
        jQuery('#dropdownBarResto').css('display', 'block');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > span').css('color', 'white');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > i').css('color', 'white');
    } else if(pathname == '/view-tables' || pathname == '/view-order-slips') {
        jQuery('#POSDashboard').addClass('active');
    } else if(pathname == '/cashier-shift-report') {
        jQuery('#restoReportsTab').addClass('activeTab');
        jQuery('#dropdownRestoReports').css('display', 'block');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > span').css('color', 'white');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > i').css('color', 'white');
    } else if(pathname == '/view-orders' || pathname == '/view-restaurant-payments') {
        jQuery('#restoTransactionsTab').addClass('activeTab');
        jQuery('#dropdownRestoTransactions').css('display', 'block');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > span').css('color', 'white');
        jQuery('.nav-list > li > .dropdown-container > a[href="'+pathname+'"] > i').css('color', 'white');
    } else {
        jQuery('.nav-list > li > a[href="'+pathname+'"]').addClass('active');
    }
});