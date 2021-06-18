
			$ = jQuery;
			$(document).ready(function(){
    			$(".togle_button").click(function(){
        			$(".main-nav").slideToggle("slow");
    			});
			});

/*****slider**************/
var $status = $('.pagingInfo');
var $slickElement = $('.slideshow');

$slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
  //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
  var i = (currentSlide ? currentSlide : 0) + 1;
  $status.text(i + '——' + slick.slideCount);
});

$slickElement.slick({
  autoplay: true,
  dots: true
});

    

// Cache do elemento para evitar constantes ciclos de procura pelo mesmo
var $input1 = $("#txtAcrescimo");

// Colocar a 0 ao início
$input1.val(0);

// Aumenta ou diminui o valor sendo 0 o mais baixo possível
$(".altera").click(function(){
    if ($(this).hasClass('acrescimo'))
        $input1.val(parseInt($input1.val())+1);
    else if ($input1.val()>=1)
        $input1.val(parseInt($input1.val())-1);
});

// Cache do elemento para evitar constantes ciclos de procura pelo mesmo
var $input = $("#txt");

// Colocar a 0 ao início
$input.val(0);

// Aumenta ou diminui o valor sendo 0 o mais baixo possível
$(".num").click(function(){
    if ($(this).hasClass('inc'))
        $input.val(parseInt($input.val())+1);
    else if ($input.val()>=1)
        $input.val(parseInt($input.val())-1);
});



function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();


/********image-gallery*************/
function myFunction(imgs) {
        var expandImg = document.getElementById("expandedImg");

        expandImg.src = imgs.src;

        expandImg.parentElement.style.display = "block";
    }



    /*******progress-bar************/
    $('#jq').LineProgressbar({
      percentage:90,
      radius: '3px',
      height: '20px',
      });



