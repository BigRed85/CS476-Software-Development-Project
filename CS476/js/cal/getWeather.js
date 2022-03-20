let apis = {
    currentWeather: {
        api:"https://api.openweathermap.org/data/2.5/forecast?q=",
        parameters: "&units=metric&appid=024e1dcad17f6c8bc6a9cac8dc466441",
        url: (city) => {
            console.log(apis.currentWeather.api + city +
            apis.currentWeather.parameters)
            return apis.currentWeather.api + city +
                   apis.currentWeather.parameters;
        }
    }
};
function getCurrentLoc() { 
    return new Promise((resolve, reject) =>  navigator.geolocation
                                             .getCurrentPosition(resolve, reject))
}

//THIS IS FOR GEOLOCATE
function getCurrentCity(location) {
    const lat = location.coords.latitude;
    const lon = location.coords.longitude;
    return fetch(apis.currentWeather.url(lat, lon))
        .then(response => response.json());
        
}

//look for city only
function getWeatherCity(city) {
    return fetch(apis.currentWeather.url(city))
        .then(response => response.json());
}

