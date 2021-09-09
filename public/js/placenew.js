        // 지도를 표시할 div와  지도 옵션으로  지도를 생성합니다
        document.getElementById('kakaomap').style.display = "block";
        document.getElementById('maphidden').style.display = "none";


    let loc = window.location.search
    let getparams = new URLSearchParams(loc)
    let getLocation = getparams.get('location')

    let po = getLocation.split(',')

    var lat = Number(po[0])
    var long = Number(po[1])


if(getLocation != ''){


    document.getElementById('maphidden').style.display = "block";
    document.getElementById('kakaomap').style.display = "none";
    document.querySelector('#place_lat').value = lat
    document.querySelector('#place_longs').value = long

var  mapContainer= document.getElementById('map'); // 지도를 표시할 div

mapOption = {
center: new kakao.maps.LatLng(lat, long ), // 지도의 중심좌표
level: 12 // 지도의 확대 레벨
};

var map = new kakao.maps.Map(mapContainer, mapOption);

// 마커가 표시될 위치입니다
var markerPosition  = new kakao.maps.LatLng(lat, long);


// 마커를 생성합니다
var marker = new kakao.maps.Marker({
position: markerPosition
});

marker.setMap(map);

var rvContainer = document.getElementById('roadview'); // 로드뷰를 표시할 div

var rv = new kakao.maps.Roadview(rvContainer); // 로드뷰 객체 생성
var rc = new kakao.maps.RoadviewClient(); // 좌표를 통한 로드뷰의 panoid를 추출하기 위한 로드뷰 help객체 생성
var rvPosition = new kakao.maps.LatLng(lat, long );
rc.getNearestPanoId(rvPosition, 50, function(panoid) {
    rv.setPanoId(panoid, rvPosition);//좌표에 근접한 panoId를 통해 로드뷰를 실행합니다.
});

// 로드뷰 초기화 이벤트
kakao.maps.event.addListener(rv, 'init', function() {

    // 로드뷰에 올릴 마커를 생성합니다.
    var rMarker = new kakao.maps.Marker({
        position: myPosition,
        map: rv //map 대신 rv(로드뷰 객체)로 설정하면 로드뷰에 올라갑니다.
    });
    rMarker.setAltitude(6); //마커의 높이를 설정합니다. (단위는 m입니다.)
    rMarker.setRange(100); //마커가 보일 수 있는 범위를 설정합니다. (단위는 m입니다.)

    // 로드뷰 마커가 중앙에 오도록 로드뷰의 viewpoint 조정합니다.
    var projection = rv.getProjection(); // viewpoint(화면좌표)값을 추출할 수 있는 projection 객체를 가져옵니다.

    // 마커의 position과 altitude값을 통해 viewpoint값(화면좌표)를 추출합니다.
    var viewpoint = projection.viewpointFromCoords(rMarker.getPosition(), rMarker.getAltitude());
    rv.setViewpoint(viewpoint); //로드뷰에 뷰포인트를 설정합니다.
});

}