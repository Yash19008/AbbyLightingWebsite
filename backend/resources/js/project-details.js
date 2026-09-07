$('#sub-tag-tiles').viewer({title: false});
$(document).ready(function () {
    $(".project-product-links").on("click", function (elem) {
        var element = document.getElementById('projects_used_title');
        var headerOffset = 200;
        var elementPosition = element.getBoundingClientRect().top;
        var offsetPosition = elementPosition + window.pageYOffset - headerOffset;
      
        window.scrollTo({
             top: offsetPosition,
             behavior: "smooth"
        });
    });
});
