
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
console.log(local)

document.getElementById('place_title').value = local.title
document.getElementById('place_description').value = local.description
document.getElementById('place_city').value = local.city

    let loc = window.location.search
    let getparams = new URLSearchParams(loc)
    let getLocation = getparams.get('location')

    let po = getLocation.split(',')

    var lat = Number(po[0])
    var long = Number(po[1])


if(getLocation != null){

    document.querySelector('#place_lat').value = lat
    document.querySelector('#place_longs').value = long

var mapContainer = document.getElementById('map'), // 지도를 표시할 div

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



// 마커가 지도 위에 표시되도록 설정합니다
marker.setMap(map);
/*
var iwContent = '<div style="padding:5px;">Hello World! <br><a href="https://map.kakao.com/link/map/Hello World!,33.450701,126.570667" style="color:blue" target="_blank">큰지도보기</a> <a href="https://map.kakao.com/link/to/Hello World!,33.450701,126.570667" style="color:blue" target="_blank">길찾기</a></div>', // 인포윈도우에 표출될 내용으로 HTML 문자열이나 document element가 가능합니다
iwPosition = new kakao.maps.LatLng(lat, long); //인포윈도우 표시 위치입니다

//iwPosition.La =  lat
//iwPosition.Ma =  long

// 인포윈도우를 생성합니다
var infowindow = new kakao.maps.InfoWindow({
position : iwPosition,
content : iwContent
});

console.log(infowindow)

// 마커 위에 인포윈도우를 표시합니다. 두번째 파라미터인 marker를 넣어주지 않으면 지도 위에 표시됩니다
infowindow.open(map, marker);
*/
}
