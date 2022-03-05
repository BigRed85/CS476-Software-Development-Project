let apis = {
    currentWeather: {
        api:"https://api.openweathermap.org/data/2.5/forecast?q=",
        parameters: "&units=metric&appid=024e1dcad17f6c8bc6a9cac8dc466441",
        url: (city) => {
            console.log(apis.currentWeather.api + city +
            apis.currentWeather.parameters)
            return apis.currentWeather.api + city +
                   apis.currentWeather.parameters
        }
    }
};
function getCurrentLoc() { 
    return new Promise((resolve, reject) =>  navigator.geolocation
                                             .getCurrentPosition(resolve, reject))
}
function getCurrentCity(location) {
    const lat = location.coords.latitude;
    const lon = location.coords.longitude;
    return fetch(apis.currentWeather.url(lat, lon))
        .then(response => response.json());
        //.then(str => new window.DOMParser().parseFromString(str, "text/xml"))
        //.then(data => console.log(data));
        
}

function getWeatherCity(city) {
    return fetch(apis.currentWeather.url(city))
        .then(response => response.json());
}

//getCurrentLoc()
//.then( coords => getCurrentCity(coords))
