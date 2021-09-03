        // 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
        document.getElementById('kakaomap').style.display = "block";
        document.getElementById('map').style.display = "none";

function info(){

    //stock Data
    let formData = {
        title: document.getElementById('place_title').value,
        description: document.getElementById('place_description').value,
        images: document.getElementById('place_images').value,
        city: document.getElementById('place_city').value,
    }

    localStorage.setItem('formData', JSON.stringify(formData))
    console.log(localStorage.getItem('formData'))
}

function submitform(){
    localStorage.clear();
}



//get Data
const local = JSON.parse(localStorage.getItem('formData'))

document.getElementById('place_title').value = local.title
document.getElementById('place_description').value = local.description
document.getElementById('place_city').value = local.city

    let loc = window.location.search
    let getparams = new URLSearchParams(loc)
    let getLocation = getparams.get('location')

    let po = getLocation.split(',')

    var lat = Number(po[0])
    var long = Number(po[1])


if(getLocation != ''){
    document.getElementById('map').style.display = "block";
    document.getElementById('kakaomap').style.display = "none";

    document.querySelector('#place_lat').value = lat
    document.querySelector('#place_longs').value = long


var  mapContainer= document.getElementById('map'); // 지도를 표시할 div

mapOption = {
center: new kakao.maps.LatLng(lat, long ), // 지도의 중심좌표
level: 10 // 지도의 확대 레벨
};

var map = new kakao.maps.Map(mapContainer, mapOption);

// 마커가 표시될 위치입니다
var markerPosition  = new kakao.maps.LatLng(lat, long);


// 마커를 생성합니다
var marker = new kakao.maps.Marker({
position: markerPosition
});

marker.setMap(map);

}
