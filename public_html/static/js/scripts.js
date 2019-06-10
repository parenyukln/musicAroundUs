// Функция ymaps.ready() будет вызвана, когда загрузятся все компоненты API, а также когда будет готово DOM-дерево.
ymaps.ready(init);

function init() {
	var myMap = new ymaps.Map("yandex_map", {
	    center: [55.792372, 49.122292],
	    zoom: 16
	});
	// Отображение меток на карте
	myMap.geoObjects
	      
	.add(new ymaps.Placemark([55.792372, 49.122292], {
	    iconCaption: 'Макс Корж - Жить в кайф'
	    }, {
	    preset: 'islands#nightDotIconWithCaption'
	}))
	.add(new ymaps.Placemark([55.791188, 49.131700], {
	    iconCaption: 'Ariana Grande - in my head'
	    }, {
	    preset: 'islands#nightDotIconWithCaption'
	}))
	.add(new ymaps.Placemark([55.791037, 49.121973], {
	    iconCaption: 'Rammstein - Was Ich Liebe'
	    }, {
	    preset: 'islands#nightDotIconWithCaption'
	}))
	.add(new ymaps.Placemark([55.792404, 49.116509], {
	    iconCaption: 'Aqua - Barbie Girl'
		}, {
	    preset: 'islands#nightDotIconWithCaption'
	}));
}

//прокрутка слайдера

$(document).on('ready', function() {
	if(window.matchMedia('(max-width: 600px)').matches){
		$(".autoplay").slick({
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 2000
		}); 
	} 
	if(window.matchMedia('(max-width: 1024px)').matches){
		$(".autoplay").slick({
			infinite: true,
			slidesToShow: 2,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 2000
		}); 
	} else {
			$(".autoplay").slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 2000
			}); 
	}
});
