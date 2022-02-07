const server = "https://killerddd.pl/picture_categories/";
let src, checked, hidden;
let section = document.querySelector("section");
let aside = document.querySelector("aside");
let asideMenuItems = aside.querySelectorAll("li:not(.head)");
let imagesList = document.querySelectorAll(".images-list div.category");
let isModalOpened = false;

function toggleActivity(button) {
  if (button.dataset.active == "0") {
    button.dataset.active = "1";
  } else {
    button.dataset.active = "0";
  }
  button.classList.toggle("active");
}

function changeCustomOptions(obj, id) {
  if (obj.checked) {
    document.getElementById(id).classList.remove("hidden");
  } else {
    document.getElementById(id).classList.add("hidden");
  }
}

function toggleEditionForm(el) {
  el.parentElement.parentElement.classList.toggle("edition-active");
}

function showCategoryInfo(id, name, shop) {
  fetch(
    server + "api_backend.php?id=" + id + "&shop=" + shop + "&name=" + name,
    {
      method: "GET",
    }
  )
    .then(function (res) {
      return res.json();
    })
    .then((res) => {
      generateForm(res.name, res.id, res.image, res.shop, res.custom_image);
    });
}

function generateForm(name, id, img, shop, custom_image) {
  if (!isModalOpened) {
    isModalOpened = true;
    if (custom_image == 1) {
      checked = "checked";
      hidden = "";
      if (img != "" && img != null) {
        src = `images/${shop}/${img}`;
      } else {
        src = "assets/images/no_image.png";
      }
    } else {
      checked = "";
      hidden = "hidden";
      if (img != "" && img != null) {
        src = `images/${shop}/${img}`;
      } else {
        src = "assets/images/no_image.png";
      }
    }

    var optionForm = document.createElement("div");
    optionForm.classList.add("form-wrapper");
    optionForm.dataset.elementId = id;
    optionForm.innerHTML = `
                        <div class='category-form'  action='' method='post' enctype='multipart/form-data'>
                            <fieldset style="margin-bottom: 0; padding-bottom: 0;">
                                <div class="options checkbox-wrapper">
                                        <span class="label">Obrazek własny:</span>
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" name="custom_image" onclick="changeCustomOptions(this, 'options_${id}')" ${checked}>
                                    </div>
                                </div>
                                <div class="options img-option ${hidden}" id="options_${id}">
                                        <span class="label">Obrazek:</span>
                                    <div class="image-upload-${id}">
                                        <label for="file-input-${id}">
                                           <img class="upload-img_${id}" style="width: 200px" src="${src}"/>
                                           <div class="compression-in-progress">
                                            <img src="assets/images/spinner-solid.svg">
                                            <span>Trwa kompresja obrazu...</span>
                                           </div>
                                        </label>

                                        <input id="file-input-${id}" name="image" type="file" onchange="handleImageUpload(this,${id})"/>
                                    </div>
                                </div>
                                <div class="options">
                                    <input type="hidden" name="category_id" value="${id}">
                                    <button class="button" type="submit" onclick="saveCategoryInfo('${id}','${name}','${shop}')" name="category_save" value="1" >
                                        Zapisz
                                    </button>
                                </div>
                            </fieldset>
                            </div>
            `;
    document.querySelector(".modal-container").style.display = "flex";
    document.querySelector(".modal-container .modal").style.opacity = "1";
    document.querySelector(".modal-container .modal").innerHTML +=
      optionForm.outerHTML;
  }
}

function saveCategoryInfo(category_id, name, shop) {
  let category_form = document.querySelector(
    `[data-element-id="${category_id}"] .category-form`
  );
  let custom_image = category_form.querySelector(
    'input[name="custom_image"]'
  ).checked;
  var input = category_form.querySelector('input[type="file"]');

  var data = new FormData();
  data.append("category_save", 1);
  data.append("custom_image", custom_image);
  data.append("category_id", category_id);
  data.append("name", name);
  data.append("shop", shop);
  // data.append("image", input.files[0]);
  data.append("image", currentImage);

  fetch(server + "api_backend.php", {
    method: "POST",
    body: data,
  })
    .then(function (res) {
      return res.json();
    })
    .then((res) => {
      closeModal();
      showAlert(res);
      checkImgSet(
        document.querySelector(`[data-element-id="${category_id}"]`),
        category_id,
        name,
        shop
      );
    });
}

function saveConfigInfo(shop) {
  var config = document.querySelector("section.wrapper .config");
  let mWidth = config.querySelector(".mwidth").value;
  let tWidth = config.querySelector(".twidth").value;
  let dWidth = config.querySelector(".dwidth").value;
  let position = config.querySelector(".pos-input:checked").value;
  let activity = config.querySelector(".activity-button").dataset.active;

  var data = new FormData();
  data.append("setting_save", 1);
  data.append("mobile_width", mWidth);
  data.append("tablet_width", tWidth);
  data.append("desktop_width", dWidth);
  data.append("activity", activity);
  data.append("position", position);
  data.append("shop", shop);

  fetch(server + "api_backend.php", {
    method: "POST",
    body: data,
  })
    .then(function (res) {
      return res.json();
    })
    .then((res) => {
      showAlert(res);
    });
}

function showAlert(res) {
  if (res.is_update) {
    document.querySelector(".settings-changed").style.opacity = "1";
    setTimeout(function () {
      document.querySelector(".settings-changed").style.opacity = "0";
    }, 5000);
  } else {
    document.querySelector(".settings-changed-error").style.opacity = "1";
    setTimeout(function () {
      document.querySelector(".settings-changed-error").style.opacity = "0";
    }, 5000);
  }
}

function setCategoriesFuncionality() {
  imagesList.forEach((el) => {
    if (el.querySelector(":scope > .subcategories .category") != null) {
      var caretBtn = document.createElement("button");
      caretBtn.classList.add("toggle-children");
      caretBtn.innerHTML = "<img src='assets/images/caret-down-solid.svg'>";
      el.prepend(caretBtn);
      el.querySelector(".cat-name").classList.add("toggle-children");
      el.querySelectorAll(":scope > .toggle-children").forEach((clEl) => {
        clEl.addEventListener("click", () => {
          el.classList.toggle("childrens-active");
        });
      });
    }
    checkImgSet(
      el,
      el.dataset.elementId,
      el.dataset.elementName,
      el.dataset.elementShop
    );
  });
}
setCategoriesFuncionality();

function checkImgSet(element, id, name, shop) {
  fetch(
    server + "api_backend.php?id=" + id + "&shop=" + shop + "&name=" + name,
    {
      method: "GET",
    }
  )
    .then(function (res) {
      return res.json();
    })
    .then((res) => {
      if (
        res.image != null &&
        res.image != undefined &&
        res.custom_image != 0
      ) {
        element.querySelector(".img-set").src =
          "assets/images/imgset-green.svg";
      } else {
        element.querySelector(".img-set").src = "assets/images/imgset.svg";
      }
    });
}

function setAsideItemsFuncionality() {
  asideMenuItems.forEach((el) => {
    el.addEventListener("click", () => {
      asideMenuItems.forEach((item) => {
        item.style.color = "inherit";
      });
      el.style.color = "#65bbff";
      section.style.display = "block";
      section.querySelectorAll(":scope > div").forEach((divEl) => {
        divEl.style.display = "none";
      });
      section.querySelector(`.${el.className}`).style.display = "block";
    });
  });
}
setAsideItemsFuncionality();

function openModal(id) {
  document.querySelector(".modal-container").style.display = "flex";
  document.querySelector(".modal-container .modal").style.opacity = "1";
  document.querySelector(".modal-container .modal").innerHTML +=
    document.querySelector(
      `div[data-element-id='${id}'] .form-wrapper`
    ).outerHTML;
}

function closeModal() {
  isModalOpened = false;
  document.querySelector(".modal-container").style.display = "none";
  document.querySelector(".modal-container .modal").innerHTML =
    "<button class='close' onclick='closeModal()'>X</button>";
}
let currentImage;

async function handleImageUpload(input, id) {
  const imageFile = input.files.item(0);

  const options = {
    maxSizeMB: 0.1,
    maxWidthOrHeight: 1920,
    useWebWorker: false,
    fileType: "image/webp",
  };
  try {
    var test = setInterval(function () {
      document.querySelector(".compression-in-progress").style.display = "flex";
    }, 10);

    const compressedFile = await imageCompression(imageFile, options);

    clearInterval(test);
    document.querySelector(".compression-in-progress").style.display = "none";
    const cF = new File([compressedFile], imageFile.name, {
      type: "image/webp",
    });
    input.parentElement.querySelector(".upload-img_" + id).src =
      URL.createObjectURL(cF);
    currentImage = cF;
    console.log(compressedFile);
    console.log(currentImage);
  } catch (error) {
    console.log(error);
  }
}
