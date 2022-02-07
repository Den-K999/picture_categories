let categoryConfig = {};
let shop = pirkspark_shop_data.shop;
const server = "https://killerddd.pl/picture_categories/";

var product = frontAPI.getProductsList({});
let page = 1;
let isCompleted = false;
var categoriesImg;

let mW, tW, dW, pos;
if ($(".shop_product_list") != null) {
  fetch(server + "api.php?shop=" + shop, {
    method: "GET",
  })
    .then(function (res) {
      return res.json();
    })
    .then((res) => {
      if (res.setting.activity == 1) {
        categoryConfig = res.configCategories;
        mW = res.setting.mobile_width;
        tW = res.setting.tablet_width;
        dW = res.setting.desktop_width;
        pos = res.setting.position;
        categoriesImg = $(
          '<div class="categories-img" style="opacity:0;"></div>'
        );
        $("#box_mainproducts .boxhead").after(categoriesImg);
      }
      getCategories();
    });

  let catArray = [];
  let isArrayCompleted = false;

  function getCategories() {
    frontAPI.getCategoryChildrens(
      function (categories) {
        catArray = catArray.concat(categories.list);
        if (categories.pages > page) {
          page++;
          console.log(categories);
          getCategories();
        }
        if (catArray.length == categories.count) {
          console.log(categories);
          isArrayCompleted = true;
          renderCategoriesImages();
        }
      },
      {
        lang: "pl_PL",
        id: $("body").attr("id").replace("shop_category", ""),
        urlParams: `?page=${page}&limit=50`,
      }
    );
  }

  function renderCategoriesImages() {
    catArray = catArray.sort((a, b) => a.category_id - b.category_id);
    catArray.find((x) => {
      if (x.category_id == 97) {
        console.log(x);
      }
    });
    catArray.map((el) => {
      ////
      if (
        el.category_id in categoryConfig &&
        categoryConfig[el.category_id].custom_image == "1"
      ) {
        if (
          categoryConfig[el.category_id].image != "" &&
          categoryConfig[el.category_id].image != null
        ) {
          $(".categories-img").append(
            `<a href="${
              el.url
            }"><div class="single"><div class="img-wrapper"><img class="cat-img" src="${server}images/${shop}/${
              categoryConfig[el.category_id].image
            }"></div><span>${el.translation.name}</span></div></a>`
          );
        } else {
          $(".categories-img").append(
            `<a href="${el.url}"><div class="single"><div class="img-wrapper"><img class="cat-img" src="${server}assets/images/no_image.png"></div><span>${el.translation.name}</span></div></a>`
          );
        }
      } else {
        frontAPI.getProductsFromCategory(
          function (products) {
            if (products.list.length == 0) {
              $(".categories-img").append(
                `<a href="${el.url}"><div class="single"><div class="img-wrapper"><img class="cat-img" src="https://killerddd.pl/picture_categories/assets/images/no_image.png"></div><span>${el.translation.name}</span></div></a>`
              );
            } else {
              var newProd = products.list.reduce(function (prev, curr) {
                return prev.id < curr.id ? prev : curr;
              });
              $(".categories-img").append(
                `<a href="${el.url}"><div class="single"><div class="img-wrapper"><img class="cat-img" src="/userdata/public/gfx/${newProd.main_image}/${newProd.main_image_filename}"></div><span>${el.translation.name}</span></div></a>`
              );
            }
            gridChange();
          },
          {
            urlParams: `?limit=1`,
            id: el.category_id,
          }
        );
      }
      ////
    });
    var addInterval = setInterval(() => {
      if (
        $(".categories-img a").length == 12 ||
        $(".categories-img a").length == catArray.length
      ) {
        clearInterval(addInterval);
        $("#box_mainproducts .categories-img").css("opacity", "1");
        gridChange();
      }
    }, 100);
  }

  function gridChange() {
    if ($(window).width() < 768) {
      $(".categories-img").css("gridTemplateColumns", `repeat(${mW},1fr)`);
    } else if ($(window).width() > 768 && this.$(window).width() < 1024) {
      $(".categories-img").css("gridTemplateColumns", `repeat(${tW},1fr)`);
    } else if ($(window).width() >= 1024) {
      $(".categories-img").css("gridTemplateColumns", `repeat(${dW},1fr)`);
    }

    if (pos == "bottom") {
      $(".categories-img .single").css("flex-direction", "column");
      $(".categories-img .single .img-wrapper")
        .css("width", "100%")
        .css("padding-top", "100%")
        .css("margin", "0");
    }
  }

  $(window).on("resize", function () {
    gridChange();
  });
}

// var categories1 = frontAPI.getCategoryChildrens({
//   urlParams: "?limit=50&page=1",
//   id: 1,
// });
// console.log(categories1.list)
// var categories2 = frontAPI.getCategoryChildrens({
//   urlParams: "?limit=50&page=2",
//   id: 1,
// });
// console.log(categories2.list);
// var categories3 = frontAPI.getCategoryChildrens({
//   urlParams: "?limit=50&page=3",
//   id: 1,
// });
// console.log(categories3.list);
// var categories4 = frontAPI.getCategoryChildrens({
//   urlParams: "?limit=50&page=4",
//   id: 1,
// });
// console.log(categories4.list);
