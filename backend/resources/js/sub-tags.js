import imagesLoaded from 'imagesloaded';
window.imagesLoaded = imagesLoaded;

import Isotope from 'isotope-layout';
import Masonry from 'masonry-layout';
window.Isotope = Isotope;

document.addEventListener('DOMContentLoaded', () => {
    var msnry = null;
    var element =  document.getElementById('sub-tag-tiles');
    if (typeof(element) != 'undefined' && element != null)
    {
        msnry = new Isotope('#sub-tag-tiles', {
            percentPosition: true,
           /*  masonry: {
                itemSelector: '.masonry-item',
                columnWidth: '.masonry-sizer',
                percentPosition: true,
                horizontalSize: true
            } */
        });
        new imagesLoaded( '#sub-tag-tiles', () => {
            msnry.layout();
        });


        const defaultDataFilter = document.getElementById("links-section")?.getAttribute('default-data-filter');
        if (defaultDataFilter && defaultDataFilter.length > 0) {
            msnry.arrange({
                filter: '.--t' + defaultDataFilter
            });
        }
    }

    const filterLinks = document.querySelectorAll(".section-link-filter");
    for (let i = 0; i < filterLinks.length; i++) {
        filterLinks[i].addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            // 🔹 Remove active from all
            filterLinks.forEach(link => link.classList.remove("active"));

            // 🔹 Add active to clicked one
            e.currentTarget.classList.add("active");

            if (e.currentTarget.dataset.filter === "*") {
                window.history.pushState('data', document.title, window.location.origin + '/products');
            }

            if (e.currentTarget.dataset.slug) {
                window.history.pushState(
                    'data',
                    document.title,
                    window.location.origin + '/products/category/' + e.currentTarget.dataset.slug.replace(/\s+/g, '-').toLowerCase()
                );
            }

            if (msnry) {
                msnry.arrange({
                    filter: e.currentTarget.dataset.filter
                });
            }

            try {
                document.querySelector('#extra-div-to-scroll').scrollIntoView({
                    behavior: 'smooth'
                });
            } catch(err) {}
        });
    }
});

$(".img-hover-img").on("mouseover", function (elem) {
    $(this).find('img:first').addClass('transform-scale');
    $(this).find('img:first').removeClass('transform-normal-scale');
});

$(".img-hover-img").on("mouseout", function (elem) {
    $(this).find('img:first').removeClass('transform-scale');
    $(this).find('img:first').addClass('transform-normal-scale');
});

$("#scrollToTop").on("click", function (elem) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

$("#view-projects").on("click", function (elem) {
    var element = document.getElementById('feature_project_title');
    var headerOffset = 200;
    var elementPosition = element.getBoundingClientRect().top;
    var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

    window.scrollTo({
         top: offsetPosition,
         behavior: "smooth"
    });

    // document.querySelector('#feature_project_title').scrollIntoView({
    //     behavior: 'smooth'
    // });
});


